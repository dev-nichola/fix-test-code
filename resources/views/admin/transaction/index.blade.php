@extends('layouts.admin.app')

@section('content')
  <div class="card">
    <div class="card-body table-responsive">      
        <table class="table table-bordered" id="tableTransaction">
          <thead>
            <tr>
              <th>No</th>
              <th>Date Of Transactions</th>
              <th>Total</th>
              <th>Pay</th>
              <th>Charge</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

    </div>
  </div>

@endsection

@if (check_authorized("006P"))
  @push('script')
    <script>
      CORE.dataTableServer("tableTransaction", "/app/sales/get");
    </script>
  @endpush
@endif
