@extends('layouts.admin.app')

@section('content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('app.product.update', $product->id) }}" method="POST" with-submit-crud>
                @method('PUT')
                @csrf
                  @include('admin/product/form-product')
                <button class="btn btn-success btn-sm mt-3">+ Add Product</button>
            </form>

        </div>
    </div>
@endsection
