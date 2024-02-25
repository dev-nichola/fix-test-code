<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    private ProductService $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index() : View
    {
        return $this->view_admin('admin.product.index', "Product Management", [], true);
    }

    public function getProducts(Request $request) : JsonResponse
    {
        $products = $this->productService->findAll($request);
        $sumProduct = $this->productService->countProducts($request);

        $data = [];
        $number = $request->start;

        foreach ($products as $product)
        {
            $number++;
            $row = [];
            $row[] = $number;
            $row[] = $product->product_name;
            $row[] = "Rp. ". format_rupiah($product->product_price_capital) ;
            $row[] = "Rp. ". format_rupiah($product->product_price_sell);
            $detail = "<a href='" . route("app.product.show", $product->id) . "' class='btn btn-info btn-sm m-1'>Detail</a>";
            $delete = form_delete("formProduct$product->id", route("app.product.destroy", $product->id));

            $row[] = $detail . $delete;
           
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

    public function create()
    {
        return $this->view_admin('admin.product.create', "Create Product", [], false);
    }

    public function store(ProductRequest $request) : JsonResponse
    {
        $data = $this->productService->addProduct($request);

        return response()->json($data)->setStatusCode(200); // 201
    }

    public function show($id) : View
    {  
        $products = [
            "product" => $this->productService->findById($id),
        ];

       return $this->view_admin("admin.product.show", "Detail Product", $products, FALSE);
    }

    public function edit(string $id) : View
    {
        $products = [
            "product" => $this->productService->findById($id),
        ];

        return $this->view_admin("admin.product.edit", "Update Product", $products, FALSE);
    }   

    public function update(ProductRequest $request,string $id) : JsonResponse
    {
        $data = $this->productService->updateProduct($request, $id);

        return response()->json($data)->setStatusCode(200);
    }

    public function destroy(string $id) : JsonResponse
    {
        $data = $this->productService->remove($id);

        return response()->json($data)->setStatusCode(200);
    }
}
