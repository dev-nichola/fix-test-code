@extends('layouts.admin.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th>Data Product</th>
                            <td>: {{ $transaction->product->product_name }}</td>    
                        </tr>
                        <tr>
                              <th>Price Product</th>                            
                              <td>: {{ $transaction->product->product_price_sell}}</td>     
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
