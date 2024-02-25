<?php

namespace App\Services\Implementation;

use App\Models\TransactionDetail;
use App\Services\TransactionDetailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TransactionDetailServiceImpl implements TransactionDetailService
{
      // public Request $request;
      // public function __construct(Request $request)
      // {
      //       $this->request = $request;
      // }
      public function addProduct(string $id)
      {
            $getTransactionNow = Session::get('X-TRANSACTION-SESSION');
            return TransactionDetail::query()->create([
                  "transaction_id" => $getTransactionNow,
                  "product_id" => $id
            ]);
      }

      public function delete(string $id)
      {
            $data = TransactionDetail::query()->find($id);

            return $data->delete();
      }
}
