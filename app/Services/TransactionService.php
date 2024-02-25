<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TransactionService
{
      private function queryTransactions(Request $request)
      {
            $colum_search = ['transactions.date', 'transactions.total', 'transactions.bayar', 'transactions.kembali'];
            $colum_order = [NULL, 'transactions.date', 'transactions.total'];
            $order = ['transactions.id' => 'DESC'];

            $transactions = Transaction::query()
                  ->where(
                        function ($query)
                        use ($request, $colum_search) {
                              $i = 1;
                              if (isset($request->search)) {
                                    foreach ($colum_search as $column) {
                                          if ($request->search['value']) {
                                                if ($i == 1) {
                                                      $query->where($column, "LIKE", "%{$request->search['value']}%");
                                                } else {
                                                      $query->orWhere($column, "%{$request->search["value"]}%");
                                                }
                                          }

                                          $i++;
                                    }
                              }
                        }
                  );

            if (isset($request->order)) {
                  $transactions = $transactions->orderBy($colum_order[$request->order["0"]["column"]], $request->order["0"]["dir"]);
            } else {
                  $transactions = $transactions->orderBy(key($order), $order[key($order)]);
            }

            return $transactions;
      }

      public function addTransaction()
      {
            return Transaction::query()->create([
                  "user_id" => Auth::user()->id
            ]);
      }

      public function findAll(Request $request)
      {
            $results = $this->queryTransactions($request);
            if ($request->length != -1) {
                  $limit = $results->offset($request->start)->limit($request->length);
                  return $limit->get();
            }
      }

      public function countTransactions(Request $request)
      {
            return $this->queryTransactions($request)->count();
      }

      public function save($data)
      {
            $id = Session::get('X-TRANSACTION-SESSION');
            return Transaction::where('id', $id)->update($data);
      }

      public function findById($id)
      {
            return TransactionDetail::query()->find($id);
      }

      public function remove(string $id)
      {
            $data = Transaction::query()->find($id);

            $data->delete();

            $response = response_success_default("Berhasil hapus data transaksi", FALSE, route('app.sales'));

            return $response;
      }

      
}
