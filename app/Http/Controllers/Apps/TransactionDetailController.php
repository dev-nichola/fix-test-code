<?php

namespace App\Http\Controllers\Apps;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Models\TransactionDetail;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Services\TransactionDetailService;

class TransactionDetailController extends Controller
{

    private ProductService $productService;
    private TransactionDetailService $transactionDetailService;

    public function __construct(ProductService $productService, TransactionDetailService $transactionDetailService)
    {
        $this->productService = $productService;
        $this->transactionDetailService = $transactionDetailService;
    }

    public function index()
    {

        $transactionSession = Session::get('X-TRANSACTION-SESSION');
    
        if(!isset($transactionSession))
        {   
            return redirect()->route('app.transaction.new');   
        }
    
        $product = TransactionDetail::where('transaction_id', $transactionSession)->get();
    
        return $this->view_admin("admin.transaction_detail.index", "Create Transaction", compact('product',), TRUE);
    }


    public function getProducts(Request $request): JsonResponse
    {
        $products = $this->productService->findAll($request);
        $sumProduct = $this->productService->countProducts($request);

        $data = [];
        $number = $request->start;

        foreach ($products as $product) {
            $number++;
            $row = [];
            $row[] = $number;
            $row[] = $product->product_name;
            $row[] = "Rp. " . format_rupiah($product->product_price_capital);
            $row[] = "Rp. " . format_rupiah($product->product_price_sell);
            $addProduct = "<a href='javascript:void(0)' data-id='{$product->id}' class='btn btn-primary btn-sm m-1' id='add-product' >Add Product</a>";

            $row[] = $addProduct;

            $data[] = $row;
        }

        $result = [
            "draw" => $request->draw,
            "recordsTotal" => $sumProduct,
            "recordsFiltered" => $sumProduct,
            "data" => $data
        ];

        return response()->json($result)->setStatusCode(200);
    }

    public function store(Request $request)
    {
        $productId = $request->input('product_id');

        $data = $this->transactionDetailService->addProduct($productId);

        $product = $data->product;

        return response()->json([
            'data' => $data,
            'product' => $product
        ])->setStatusCode(200);
    }
    public function destroy($productId)
    {
        $data = $this->transactionDetailService->delete($productId);

        return response()->json(compact('data'))->setStatusCode(200);
    }
}
