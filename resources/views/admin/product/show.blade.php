@extends('layouts.admin.app')

@section('content')
    <div class="card">
        <div class="card-body">

            <a href="{{ route('app.product.edit', $product->id) }}" class="btn btn-info btn-sm mb-3">Edit</a>

            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th>Product Name</th>
                            <td>: {{ $product->product_name }}</td>
                        </tr>
                        <tr>
                            <th>Product Description</th>
                            <td>: {{ $product->product_description }}</td>
                        </tr>
                        <tr>
                            <th>Product Capital Price</th>
                            <td>: {{ $product->product_price_capital }}</td>
                        </tr>
                        <tr>
                              <th>Product Selling Price</th>
                              <td>: {{ $product->product_price_sell }}</td>
                          </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
