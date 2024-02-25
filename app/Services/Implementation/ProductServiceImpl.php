<?php

namespace App\Services\Implementation;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\Cores\ErrorService;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\Request;

class ProductServiceImpl implements ProductService
{
      public function __construct(
            private Product $product,
      ) {
      }
      private function queryProducts(Request $request)
      {
            $colum_search = ['products.product_name', 'products.product_price_sell'];
            $colum_order = [NULL, 'products.product.name', 'products.product.price_sell'];
            $order = ['products.id' => 'DESC'];

            $products = Product::query()
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
                  $products = $products->orderBy($colum_order[$request->order["0"]["column"]], $request->order["0"]["dir"]);
            } else {
                  $products = $products->orderBy(key($order), $order[key($order)]);
            }

            return $products;
      }
      public function addProduct(ProductRequest $request)
      {

            try {
                  $data = $request->validated();

                  $product = Product::create($data);

                  $response = response_success_default("Berhasil Menambahkan Data Product", $product->id, route('app.product', $product->id));
            } catch (Exception $exception) {
                  ErrorService::error($exception, "Gagal Menambahkan Data Product");

                  $response = response_errors_default();
            }

            return $response;
      }
      public function findAll(Request $request)
      {
            $results = $this->queryProducts($request);
            if ($request->length != -1) {
                  $limit = $results->offset($request->start)->limit($request->length);
                  return $limit->get();
            }
      }

      public function countProducts(Request $request)
      {
            return $this->queryProducts($request)->count();
      }
      public function findById($id)
      {
            return $this->product->query()->find($id);
      }
      public function updateProduct(ProductRequest $request, $id)
      {
            try {
                  $productId = $this->product->query()->find($id);
                  $data = $request->validated();

                  $productId->update($data);

                  $response = response_success_default("Berhasil update data product!", $productId, route("app.product", $productId));
            } catch (Exception $exception) {
                  ErrorService::error($exception, "Gagal Menambahkan Data Product");

                  $response = response_errors_default();
            }

            return $response;
      }
      public function remove($id)
      {
            $data = Product::query()->find($id);

            $data->delete();

            $response = response_success_default("Berhasil hapus data product", FALSE, route('app.product'));

            return $response;
      }
}
