@extends('layouts.admin.app')

@section('content')
    <div class="py-4">
        <button class="btn btn-success btn-sm" id="btn-create-transaction">Tambah Barang</button>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="tableTransaction">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product as $item)
                            <tr id="index_{{ $item->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->product->product_name }}</td>
                                <td>{{ $item->product->product_price_sell }}</td>
                                <td>
                                    <a href="javascript:void(0)" data-id="{{ $item->id }}" class="btn btn-danger btn-sm"
                                        id="btn-delete-product">DELETE</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form class="row gap-4" method="POST" action="{{ route('app.sales.create') }}">
        @csrf
        @method('POST')
        
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <input type="date" name="date" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-7 h-50 d-flex card">
            <div class="card-body">
                <div id="display_total" class="fs-1">Rp. 0 </div>
                <input type="hidden" name="total" id="total" class="form-control">
            </div>
        </div>

        <div class="col-md-4 card">
            <div class="card-body">
                <p id="sumOfProduct">Total Barang : <span id="totalProductCount">0</span></p>
                <p>Subtotal : <span name='subtotal' id="subtotal">0</span> </p>
                <div class="d-flex flex-column gap-2">
                    <label for="potongan" class="bold pb-1">Potongan</label>
                    <input type="text" name="discount" id="potongan" placeholder="Rp." class="form-control">

                    <label for="bayar" class="bold pb-1">Bayar</label>
                    <input type="text" name='bayar' id="bayar" placeholder="Rp." class="form-control">

                    <label for="kembali" class="bold pb-1">Kembali</label>
                    <input type="text" name="kembali" placeholder="Rp." id="kembali" class="form-control" readonly>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-5">Simpan Transaksi</button>
            </div>
        </div>
    </form>

    @includeIf('admin.transaction_detail.product')
@endsection

@push('script')
    <script>
        CORE.dataTableServer("tableProducts", "/app/transaction/product");

        $('body').on('click', '#btn-create-transaction', function() {
            $('#modal-create').modal('show');
        });

        $(document).on('click', '#add-product', function() {
            let productId = $(this).data('id');
            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/app/transaction',
                type: 'POST',
                data: {
                    product_id: productId,
                    _token: csrfToken
                },
                success: (response) => {
                    let rowCount = $('#tableTransaction tbody tr').length;

                    let product = `
                    <tr id="index_${response.data.id}">
                        <td>${rowCount + 1}</td>
                        <td>${response.product.product_name}</td>
                        <td>${response.product.product_price_sell}</td>
                        <td>
                            <a href="javascript:void(0)" data-id=${response.data.id} id="btn-delete-product" class="btn btn-danger btn-sm">DELETE</a>
                        </td>
                    </tr>
                `;

                    $('#tableTransaction tbody').append(product);
                    $('#modal-create').modal('hide');

                    // Hitung Jumlah Product
                    let sumOfProduct = $('#tableTransaction tbody tr').length;
                    $('#totalProductCount').text(sumOfProduct);

                    // Hitung Subtotal
                    let subtotal = calculateSubtotal();
                    $('#subtotal').text(subtotal);

                    // Hitung Total
                    let total = calculateTotal();
                    $('#total').val(total);

                    // Display Total
                    let displayTotalValue = displayTotal();
                    $('#display_total').text(displayTotalValue);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Galat parse');
                }
            });
        });

        // Hapus Product
        $('body').on('click', '#btn-delete-product', function() {
            let productId = $(this).data('id');
            let token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                url: `/app/transaction/${productId}`,
                method: "DELETE",
                cache: false,
                data: {
                    data: productId,
                    "_token": token
                },
                success: () => {
                    $(`#index_${productId}`).remove();

                    let sumOfProduct = $('#tableTransaction tbody tr').length;
                    $('#totalProductCount').text(sumOfProduct);

                    let subtotal = calculateSubtotal();
                    $('#subtotal').text(subtotal);

                    let total = calculateTotal();
                    $('#total').val(total);

                    let displayTotalValue = displayTotal();
                    $('#display_total').text(displayTotalValue);
                }

            });
        });

        $(document).ready(function() {
            $('#potongan').on('input', function() {
                let potongan = parseFloat($(this).val());
                if (isNaN(potongan)) {
                    potongan = 0;
                }
                let subtotal = parseFloat($('#subtotal').text().replace('Rp ', '').replaceAll('.', '')
                    .replace(',', '.'));
                let total = subtotal - potongan;
                $('#total').val(total);
                $('#display_total').text(formatRupiah(total));

                updateKembali();
                displayTotal();
            });

            $('#bayar').on('input', function() {
                updateKembali();
            });
        });

        function updateKembali() {
            let bayar = parseFloat($('#bayar').val().replace('.', '').replace(',', '.'));
            let total = parseFloat($('#total').val().replace('Rp ', '').replace('.', '').replace(',', ''));
            let kembali = bayar - total;

            if (isNaN(kembali) || kembali < 0) {
                kembali = 0;
            }
            $('#kembali').val(kembali);
        }


        function calculateTotal() {
            let total = 0;
            $('#tableTransaction tbody tr').each(function() {
                let price = parseFloat($(this).find('td:eq(2)').text().replace('Rp ', '').replaceAll('.', '')
                    .replace(',', '.'));
                total += price;
            });

            let potongan = parseFloat($('#potongan').val().replace('.', '').replace(',', '.'));
            if (isNaN(potongan)) {
                potongan = 0;
            }
            total -= potongan;

            $('#total').val(formatRupiah(total));
            return total;
        }

        function displayTotal() {
            let displayTotal = 0;
            $('#tableTransaction tbody tr').each(function() {
                let price = parseFloat($(this).find('td:eq(2)').text().replace('Rp ', '').replace('.', '').replace(
                    ',', '.'));
                displayTotal += price;
            });

            let potongan = parseFloat($('#potongan').val().replace('.', '').replace(',', '.'));
            if (isNaN(potongan)) {
                potongan = 0;
            }
            displayTotal -= potongan;

            return formatRupiah(displayTotal);
        }

        function calculateSubtotal() {
            let subtotal = 0;
            $('#tableTransaction tbody tr').each(function() {
                let price = parseFloat($(this).find('td:eq(2)').text().replace('Rp ', '').replace('.', '').replace(
                    ',', '.'));
                subtotal += price;
            });
            return formatRupiah(subtotal);
        }

        function formatRupiah(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID');
        }
    </script>
@endpush
