<?php

namespace App\Http\Controllers\Apps;

use Illuminate\View\View;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\TransactionRequest;
use App\Models\TransactionDetail;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{

    private TransactionService $transactionService;
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    public function index() : View
    {
        return $this->view_admin('admin.transaction.index', 'List Of Transaction', [], TRUE);
    }

    public function getTransaction(Request $request)
    {
        $transactions = $this->transactionService->findAll($request);

        $sumTransactions = $this->transactionService->countTransactions($request);

        $data = [];
        $number = $request->start;

        foreach ($transactions as $transaction)
        {
            $number++;
            $row = [];
            $row[] = $number;
            $row[] = $transaction->date;
            $row[] = "Rp. ". format_rupiah($transaction->total) ;
            $row[] = "Rp. ". format_rupiah($transaction->bayar);
            $row[] = "Rp. ". format_rupiah($transaction->kembali);
            $detail = "<a href='" . route("app.sales.show", $transaction->id) . "' class='btn btn-info btn-sm m-1'>Detail</a>";
            $delete = form_delete("formTransaction$transaction->id", route("app.sales.destroy", $transaction->id));

            $row[] = $detail . $delete;
           
            $data[] = $row;
        }

        $result = [
            "draw" => $request->draw,
            "recordsTotal" => $sumTransactions,
            "recordsFiltered" => $sumTransactions,
            "data" => $data
        ];

        return response()->json($result)->setStatusCode(200);
    }

    public function create()
    {
        $transaction = $this->transactionService->addTransaction();

        Session::put('X-TRANSACTION-SESSION', $transaction->id);
        return redirect()->route('app.transaction.store');
    }

    public function store(TransactionRequest $request)
    {
        $data = $request->validated();

        $this->transactionService->save($data);
        Session::forget('X-TRANSACTION-SESSION');
        return redirect()->route('app.sales.index');
    }

    public function show($id)
    {
        $data = [
            "transaction" => $this->transactionService->findById($id),
        ];

        return $this->view_admin("admin.transaction.detail", "Detail Product", $data, FALSE);
    }

    public function destroy(string $id) : JsonResponse
    {
        $data = $this->transactionService->remove($id);

        return response()->json($data)->setStatusCode(200);
    }
    

}
