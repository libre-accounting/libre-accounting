<?php

namespace Tests\Unit;

use App\Utilities\Camt053ParseException;
use App\Utilities\Camt053Parser;
use PHPUnit\Framework\TestCase;

class Camt053ParserTest extends TestCase
{
    protected function fixture(string $name): string
    {
        return file_get_contents(__DIR__ . '/Fixtures/' . $name);
    }

    /** @test */
    public function it_parses_statement_header_for_v02()
    {
        $result = Camt053Parser::parse($this->fixture('camt053_v02.xml'));

        $statement = $result['statement'];

        $this->assertSame('STMT-ACC-001', $statement['statement_id']);
        $this->assertSame('PL61109010140000071219812874', $statement['iban']);
        $this->assertSame('EUR', $statement['currency_code']);
        $this->assertSame(1000.00, $statement['opening_balance']);
        $this->assertSame(1234.50, $statement['closing_balance']);
        $this->assertSame('2026-01-01 00:00:00', $statement['statement_from']);
        $this->assertSame('2026-01-14 23:59:59', $statement['statement_to']);
    }

    /** @test */
    public function it_parses_credit_and_debit_entries_for_v02()
    {
        $result = Camt053Parser::parse($this->fixture('camt053_v02.xml'));

        $lines = $result['lines'];

        $this->assertCount(2, $lines);

        // Credit -> income, counterparty is the debtor.
        $income = $lines[0];
        $this->assertSame('income', $income['type']);
        $this->assertSame(500.00, $income['amount']);
        $this->assertSame('EUR', $income['currency_code']);
        $this->assertSame('2026-01-03 00:00:00', $income['booked_at']);
        $this->assertSame('BANKREF-CR-1', $income['bank_reference']);
        $this->assertSame('E2E-INV-1001', $income['end_to_end_id']);
        $this->assertSame('Acme Customer Sp. z o.o.', $income['counterparty_name']);
        $this->assertSame('DE89370400440532013000', $income['counterparty_iban']);
        $this->assertSame('Payment for invoice INV-1001', $income['remittance_info']);

        // Debit -> expense, counterparty is the creditor, structured remittance.
        $expense = $lines[1];
        $this->assertSame('expense', $expense['type']);
        $this->assertSame(265.50, $expense['amount']);
        $this->assertSame('Office Supplies Ltd', $expense['counterparty_name']);
        $this->assertSame('FR1420041010050500013M02606', $expense['counterparty_iban']);
        $this->assertSame('BILL-2001', $expense['remittance_info']);
    }

    /** @test */
    public function it_parses_prefixed_namespace_for_v08()
    {
        $result = Camt053Parser::parse($this->fixture('camt053_v08_batched.xml'));

        $this->assertSame('STMT-ACC-099', $result['statement']['statement_id']);
        $this->assertSame('PLN', $result['statement']['currency_code']);
        $this->assertSame(5000.00, $result['statement']['opening_balance']);
        $this->assertSame(4700.00, $result['statement']['closing_balance']);
    }

    /** @test */
    public function it_splits_a_batched_entry_into_one_line_per_transaction()
    {
        $result = Camt053Parser::parse($this->fixture('camt053_v08_batched.xml'));

        $lines = $result['lines'];

        // One Ntry with two TxDtls must yield two lines, each its own amount.
        $this->assertCount(2, $lines);

        $this->assertSame(120.00, $lines[0]['amount']);
        $this->assertSame('Vendor A', $lines[0]['counterparty_name']);
        $this->assertSame('E2E-A', $lines[0]['end_to_end_id']);

        $this->assertSame(180.00, $lines[1]['amount']);
        $this->assertSame('Vendor B', $lines[1]['counterparty_name']);
        $this->assertSame('E2E-B', $lines[1]['end_to_end_id']);

        // Both are expenses (DBIT) in PLN.
        foreach ($lines as $line) {
            $this->assertSame('expense', $line['type']);
            $this->assertSame('PLN', $line['currency_code']);
        }
    }

    /** @test */
    public function it_throws_on_empty_content()
    {
        $this->expectException(Camt053ParseException::class);

        Camt053Parser::parse('   ');
    }

    /** @test */
    public function it_throws_on_invalid_xml()
    {
        $this->expectException(Camt053ParseException::class);

        Camt053Parser::parse('<Document><not-closed>');
    }

    /** @test */
    public function it_throws_when_no_statement_present()
    {
        $this->expectException(Camt053ParseException::class);

        Camt053Parser::parse('<?xml version="1.0"?><Document><BkToCstmrStmt></BkToCstmrStmt></Document>');
    }
}
