<?php

namespace App\Http\Controllers\Banking;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\StatementImport as Request;
use App\Jobs\Banking\DeleteBankStatementImport;
use App\Jobs\Banking\ImportCamtStatement;
use App\Models\Banking\Account;
use App\Models\Banking\BankStatementImport;

class StatementImports extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $statement_imports = BankStatementImport::with('account')->collect();

        return $this->response('banking.statement-imports.index', compact('statement_imports'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('statement-imports.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $accounts = Account::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $account_id = request('account_id', setting('default.account'));

        return view('banking.statement-imports.create', compact('accounts', 'account_id'));
    }

    /**
     * Parse the uploaded CAMT.053 file and stage its lines for review.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        $job = new ImportCamtStatement(
            $request->file('import'),
            (int) $request->get('account_id')
        );

        $response = $this->ajaxDispatch($job);

        if ($response['success']) {
            $response['redirect'] = route('statement-imports.edit', $response['data']->id);

            flash(trans('statement_imports.messages.staged', ['count' => $response['data']->total_lines]))->success();

            // Non-blocking warnings surfaced after a successful import.
            if ($job->truncated > 0) {
                flash(trans('statement_imports.messages.truncated', ['count' => $job->truncated]))->warning()->important();
            }

            if ($job->iban_mismatch) {
                flash(trans('statement_imports.messages.iban_mismatch'))->warning()->important();
            }
        } else {
            $response['redirect'] = route('statement-imports.create');

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for reviewing the staged lines of the specified import.
     *
     * @param  BankStatementImport  $statement_import
     *
     * @return Response
     */
    public function edit(BankStatementImport $statement_import)
    {
        return view('banking.statement-imports.edit', compact('statement_import'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BankStatementImport  $statement_import
     *
     * @return Response
     */
    public function destroy(BankStatementImport $statement_import)
    {
        $response = $this->ajaxDispatch(new DeleteBankStatementImport($statement_import));

        $response['redirect'] = route('statement-imports.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.statement_imports', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }
}
