<?php

namespace App\Http\Controllers\Banking;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\StatementImport as Request;
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
        $response = $this->ajaxDispatch(new ImportCamtStatement(
            $request->file('import'),
            (int) $request->get('account_id')
        ));

        if ($response['success']) {
            $response['redirect'] = route('statement-imports.edit', $response['data']->id);

            $message = trans('statement_imports.messages.staged', ['count' => $response['data']->total_lines]);

            flash($message)->success();
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
        // Soft-delete the run and its staged lines (committed transactions are kept).
        $statement_import->lines()->delete();
        $statement_import->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.statement_imports', 1)]);

        flash($message)->success();

        return redirect()->route('statement-imports.index');
    }
}
