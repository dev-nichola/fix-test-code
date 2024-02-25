<?php 

namespace App\Services;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

interface ProductService
{
      public function addProduct(ProductRequest $request);
      public function findAll(Request $request);
      public function countProducts(Request $request);
      public function findById($id);
      public function updateProduct(ProductRequest $request,$id);
      public function remove($id);
      
}