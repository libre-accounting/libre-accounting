---
name: libre-accounting-api
description: Read and change data in a Libre Accounting instance through its REST API. Use when the user asks about their bookkeeping (invoices, bills, payments, expenses, income, customers, vendors, bank accounts, balances, reports) or wants to create invoices or bills, record expenses or payments, or organise and re-categorise transactions in Libre Accounting.
---

# Libre Accounting API

Talk to a Libre Accounting instance over its REST API with the scripts in
this skill's `scripts/` directory. Field-level details (validation rules,
search syntax, searchable and sortable columns, response shapes) are in
[reference.md](reference.md); read it before any write you have not done
in this conversation yet.

## Setup

The scripts read `~/.config/libre-accounting/credentials` (or environment
variables of the same names, which win):

```
LIBRE_ACCOUNTING_URL=https://accounting.example.com
LIBRE_ACCOUNTING_EMAIL=api-user@example.com
LIBRE_ACCOUNTING_PASSWORD=...
LIBRE_ACCOUNTING_COMPANY_ID=1      # optional, defaults to the user's first company
LIBRE_ACCOUNTING_READ_ONLY=1       # optional, blocks everything but GET
```

`LIBRE_ACCOUNTING_CONFIG=/path/file` (or `XDG_CONFIG_HOME`) points the
scripts at a different file, e.g. one per instance.

If it is missing, ask the user to create it themselves (`chmod 600`). Never
ask for the password in chat, never write it yourself, and never print it.
Authentication is HTTP Basic with the user's own email and password; there
are no API tokens. The user needs the `read-api` permission: the built-in
**admin** role has it with full rights, the **accountant** role has it but is
read-only. For writes, suggest a dedicated user with the admin role or a
custom role that has `read-api` plus the create/update permissions needed.

Check the connection with `scripts/la-api GET ping`.

## Scripts

`scripts/la-api [options] METHOD PATH` sends one request. PATH is relative to
`/api` (`contacts`, `documents/12`). Body goes to stdout (pretty JSON), and
on HTTP >= 400 the status goes to stderr with exit code 1.

| Option | Meaning |
|---|---|
| `-q key=value` | query parameter, URL-encoded for you (repeatable) |
| `-j JSON` | JSON body; `-j @file.json` or `-j -` for stdin |
| `-F 'attachment[]=@receipt.pdf'` | multipart file upload (PATCH is spoofed via POST for you) |
| `-S key=value` | multipart text field; use this, not `-F`, for every non-file field |
| `-a` | GET every page, print one merged `{"data": [...]}` |
| `-c ID` | company id for this call |
| `-r` | raw body, no formatting |

`scripts/la-update [-n] [-A file] RESOURCE ID CHANGES_JSON` is the only safe
way to edit an existing record. RESOURCE is `transactions`, `invoices`,
`bills`, `customers`, `vendors`, `categories` or `items`. It GETs the
record, rebuilds the full PATCH body, keeps existing attachments and logos,
merges your changes (`'{}'` for none), and sends it. `-n` prints the body
without sending. `-A path` adds a file (receipt, contract) to a transaction,
invoice or bill next to the existing ones.

`scripts/la-next-number invoice|bill|transaction` prints the next free
number (the API never generates numbers).

## Rules for writes

Accounting data is the user's financial record. Before any POST, PATCH or
DELETE:

1. Show the user what will change: for one record the key fields, for bulk
   changes a table (id, date, amount, description, old -> new). Get a clear
   yes. One yes can cover a whole listed batch, not later batches.
2. For updates, run `la-update -n` first when the change is unusual.
3. Never DELETE without an explicit request naming the records. Deleting a
   document also deletes its payments.
4. After writing, GET the record again and confirm the result.

Never edit with a hand-built PATCH: the API has no partial updates. A PATCH
must carry every required field, and anything omitted is wiped or reset.
Receipts are deleted, a contact's logo is deleted, recurring schedules are
dropped, and an item's taxes are removed. On older servers a missing
`enabled` also counts as "disable", and PUT fails unique checks against the
record itself. `la-update` handles all of this and always uses PATCH.

## API rules that bite

- **Contacts and documents need `?search=type:<type>` on every call**, even
  GET by id and POST: `type:customer`/`type:vendor`,
  `type:invoice`/`type:bill`. Without it the server answers 403. Payments
  under `documents/{id}/transactions` don't need it.
- **Check that a search actually filtered.** An unknown search key or syntax
  error silently drops the *whole* search and returns everything. Before
  acting on search results, verify on the client side that every row
  matches (e.g. with `jq`). For documents, the issue date key is
  `invoiced_at` (invoices) or `billed_at` (bills), not `issued_at`.
- Amounts: plain numbers with a `.` decimal point, no thousands separators,
  no currency symbols (`1234.56`). Anything else may silently become 0.
- Dates: `"YYYY-MM-DD HH:MM:SS"`. Transfers' `transferred_at` is `"YYYY-MM-DD"`.
- Booleans like `enabled`: send `1`/`0`, not `true`/`false`.
- `currency_code` must be an enabled currency of the company, and
  `currency_rate` is always required (`1` for the default currency).
- A missing id returns 404 `No query results for model ...` (older servers
  return the same message with a 500).
- Rate limit: 60 requests per minute for the **whole instance**, all users
  together. Use `-q limit=100` on lists and avoid tight loops; on 429 wait for
  `Retry-After`.
- Look up ids instead of guessing: `GET categories`, `GET accounts`,
  `GET taxes`, `GET currencies`. Taxes nested in items may lack names; resolve
  them through `GET taxes`. Payment method codes (e.g.
  `offline-payments.bank_transfer.2`) are a JSON string inside a setting:
  `scripts/la-api GET settings/offline-payments.methods | jq '.data.value | fromjson | map({code, name})'`.

## Recipes

Run scripts from this skill's directory (`scripts/...`).

**Find things**
```bash
scripts/la-api -a -q 'search=type:expense paid_at>=2026-09-01 paid_at<2026-10-01' -q limit=100 GET transactions \
  | jq '.data[] | {id, paid_at, amount, description, category: .category.name, contact: .contact.name}'
scripts/la-api -a -q 'search=type:invoice not status:paid,cancelled,draft' -q limit=100 GET documents \
  | jq '.data[] | {id, document_number, contact_name, due_at, amount, paid: ([.transactions.data[].amount] | add // 0)}'
scripts/la-api -q 'search=type:customer "acme"' GET contacts
```

**Record an expense** (optionally with a receipt)
```bash
scripts/la-next-number transaction          # e.g. TRA-00042
scripts/la-api -j '{"type":"expense","number":"TRA-00042","account_id":1,"paid_at":"2026-10-15 00:00:00",
  "amount":49.99,"currency_code":"PLN","currency_rate":1,"category_id":6,"contact_id":3,
  "payment_method":"offline-payments.bank_transfer.2","description":"Server, October"}' POST transactions
```
To include the receipt, either create the expense first and then run
`scripts/la-update -A /path/receipt.pdf transactions <id> '{}'`, or send
everything as multipart: `-S type=expense -S number=... ... -F 'attachment[]=@/path/receipt.pdf'`.

**Organise expenses** (re-categorise, fix descriptions, link vendors)
1. List the candidates with a search, then filter and check them with `jq`.
2. Show the user the proposed table of changes and get approval.
3. Apply each one: `scripts/la-update transactions 123 '{"category_id": 6}'`.
   Attach a missing receipt with `-A /path/file.pdf`. Create a missing
   category first:
   `scripts/la-api -j '{"name":"Hosting","type":"expense","color":"#3b82f6"}' POST categories`.
4. Re-list and confirm. Transfers and reconciled transactions can't be
   edited; `la-update` refuses them or the server rejects them.

**Create an invoice**
```bash
scripts/la-next-number invoice              # e.g. INV-00042
scripts/la-api -q search=type:invoice -j '{
  "type":"invoice","document_number":"INV-00042","status":"draft",
  "issued_at":"2026-10-19 00:00:00","due_at":"2026-11-02 00:00:00",
  "amount":0,"currency_code":"PLN","currency_rate":1,"category_id":3,
  "contact_id":2,"contact_name":"Acme Ltd","contact_email":"billing@acme.com",
  "notes":"Thank you",
  "items":[{"item_id":7,"name":"Consulting","description":"October","quantity":10,"price":120,"tax_ids":[1]}]
}' POST documents
```
- `amount` is always `0`; the server computes totals.
- Every item needs `name`, `quantity` and `price` > 0. Always send
  `description` too (`""` is fine), because older servers return a 500
  without it.
- Without `item_id` the server adds a new product to the catalogue, so search
  `GET items` first and reuse the existing `item_id`.
- `contact_name` (and the other `contact_*` fields) are copied onto the
  invoice; fill them from the contact.
- `category_id` is an income category for invoices, expense for bills.
- The API does not send invoices by email or mark them sent; use
  `status: "sent"` on create, or `la-update invoices ID '{"status":"sent"}'`.

**Record a payment** against an invoice or bill
```bash
scripts/la-api -j '{"type":"income","number":"TRA-00043","account_id":1,"paid_at":"2026-10-20 00:00:00",
  "amount":1000,"currency_code":"PLN","currency_rate":1,"category_id":3,
  "payment_method":"offline-payments.bank_transfer.2"}' POST documents/42/transactions
```
Use `type: "expense"` for bills. The document becomes `partial` or `paid`.
Over-payment fails with a 500. Payments can't be edited, only deleted
(`DELETE documents/42/transactions/77`) and re-created.

**Bills** work like invoices with `search=type:bill`, `type: "bill"`,
statuses `draft|received|partial|paid|cancelled`, a vendor contact and an
expense category. Mark a draft bill received with
`scripts/la-api -q search=type:bill GET documents/{id}/received`. Older
servers return a 500 there; use
`scripts/la-update bills ID '{"status":"received"}'` instead.

**Stock** is opt-in per product: `GET items/{id}` shows `track_stock`,
`stock_quantity` (null when untracked) and `components`. Invoices take
units out when sent, bills bring them in when received; nothing moves on
drafts. Negative stock is allowed.
```bash
scripts/la-api -q 'search=track_stock:1' -a GET items | jq '.data[] | {id, name, stock_quantity}'
scripts/la-api GET items/12/stock-movements | jq '.data[] | {moved_at, type, quantity, via_item_name, description}'
scripts/la-update items 12 '{"track_stock": 1, "stock_quantity": 40}'           # switch on with an opening count
scripts/la-api -j '{"change": -2, "description": "Breakage"}' POST items/12/stock-adjustments
scripts/la-api -j '{"quantity": 38, "moved_at": "2026-03-31"}' POST items/12/stock-adjustments   # set to a counted figure
```
- `stock_quantity` on an already-tracked item is ignored on update; only
  `stock-adjustments` moves stock by hand. Show the current figure and the
  change before sending one.
- Kits: `POST items/{kit}/components` with `{"component_id": 7, "quantity": 2}`
  lists what a product is built from (one level; a kit cannot contain a kit).
  Leave the kit untracked and selling it takes the parts out of stock. Track
  the kit instead and build units ahead with
  `la-api -j '{"quantity": 5}' POST items/{kit}/stock-builds`; invoices then
  consume the finished units. Changing a recipe recalculates past sales of
  that kit, so warn before editing one with history.

**Reports**
```bash
scripts/la-api GET reports | jq '.data[] | {id, name, class}'
scripts/la-api -q 'search=year:2026 basis:cash' GET reports/4 | jq '.data | {tables, dates, row_names, row_values, footer_totals}'
```
- The show response is not wrapped: report metadata is at the top level and
  the computed report is in `.data`.
- The default reports are Income Summary, Expense Summary, Income vs Expense,
  Profit & Loss and Tax Summary.
- Filters go in `search`: `year:`, `basis:cash|accrual`, `account_id:`,
  `category_id:`, `customer_id:`, `vendor_id:`.
- The list endpoint computes every report on the page, so prefer
  `GET reports/{id}`.
- Periods come from each report's `settings.period`. The summaries are
  monthly and Profit & Loss is quarterly, so for a single month use a
  summary report or sum the transactions.
- On the accrual basis, income is invoice line totals after discounts and
  before tax, and drafts are excluded, so it won't match invoice gross
  totals. The cash basis counts payments.
- If `.data` is `[]`, or any filter gives a 500, the server predates the
  report API fix. Say so and compute the figures from transactions instead.

## Errors

- 401 `Invalid credentials.`: wrong email or password. A disabled user gets
  a 302 redirect to the login page instead.
- 403 `User does not have any of the necessary access rights.`: missing
  `search=type:...` on contacts/documents, or the role lacks the permission.
- 422 `{"errors": {field: [...]}}`: fix the fields and retry. If a number
  `has already been taken`, increase it by one and retry, a few times at
  most; `la-next-number` gives the same answer until a create succeeds.
- 404 `No query results for model ...`: no record with that id.
- 500: a business rule (e.g. over-payment, deleting a contact that has
  documents), a missing id on older servers, or a server bug. The message
  usually says which.
