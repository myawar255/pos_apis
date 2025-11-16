@extends('layouts.admin')

@section('title', __('product.Product_List'))
@section('content-header', __('product.Product_List'))
@section('content-actions')
<a href="{{ route('products.barcodes') }}" class="btn btn-secondary mr-2">{{ __('product.Print_Barcodes') }}</a>
<a href="{{route('products.create')}}" class="btn btn-primary">{{ __('product.Create_Product') }}</a>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
<div class="card product-list">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('product.ID') }}</th>
                    <th>{{ __('product.Name') }}</th>
                    <th>{{ __('product.Image') }}</th>
                    <th>{{ __('product.Barcode') }}</th>
                    <th>{{ __('product.Price') }}</th>
                    <th>{{ __('product.Quantity') }}</th>
                    <th>{{ __('product.Status') }}</th>
                    <th>{{ __('product.Created_At') }}</th>
                    <th>{{ __('product.Updated_At') }}</th>
                    <th>{{ __('product.Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td>{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    <td><img class="product-img" src="{{ Storage::url($product->image) }}" alt=""></td>
                    <td>{{$product->barcode}}</td>
                    <td>{{$product->price}}</td>
                    <td class="product-quantity" data-product-id="{{ $product->id }}">{{$product->quantity}}</td>
                    <td>
                        <span class="right badge badge-{{ $product->status ? 'success' : 'danger' }}">{{$product->status ? __('common.Active') : __('common.Inactive') }}</span>
                    </td>
                    <td>{{$product->created_at}}</td>
                    <td>{{$product->updated_at}}</td>
                    <td>
                        <button
                            type="button"
                            class="btn btn-warning btn-update-quantity mb-1"
                            data-url="{{ route('products.update-quantity', $product) }}"
                            data-product-name="{{ $product->name }}"
                            data-current-quantity="{{ $product->quantity }}"
                        >
                            <i class="fas fa-boxes"></i>
                        </button>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary mb-1"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger btn-delete" data-url="{{route('products.destroy', $product)}}"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $products->render() }}
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script type="module">
    $(document).ready(function() {
        const quantityPromptTemplate = @json(__('product.quantity_prompt', ['product' => ':product']));
        const quantityUpdatedMessage = @json(__('product.quantity_updated'));
        const updateQuantityTitle = @json(__('product.Update_Quantity'));
        const confirmLabel = @json(__('common.Update'));
        const cancelLabel = @json(__('product.No'));

        $(document).on('click', '.btn-delete', function() {
            var $this = $(this);
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: '{{ __('product.sure ') }}', // Wrap in quotes
                text: '{{ __('product.really_delete ') }}', // Wrap in quotes
                icon: 'warning', // Fix the icon string
                showCancelButton: true,
                confirmButtonText: '{{ __('product.yes_delete ') }}', // Wrap in quotes
                cancelButtonText: '{{ __('product.No ') }}', // Wrap in quotes
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.post($this.data('url'), {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}' // Wrap in quotes
                    }, function(res) {
                        $this.closest('tr').fadeOut(500, function() {
                            $(this).remove();
                        });
                    });
                }
            });
        });

        $(document).on('click', '.btn-update-quantity', function() {
            const button = $(this);
            const url = button.data('url');
            const productName = button.data('product-name');
            const currentQuantity = button.data('current-quantity');

            Swal.fire({
                title: updateQuantityTitle,
                text: quantityPromptTemplate.replace(':product', productName),
                input: 'number',
                inputValue: currentQuantity,
                inputAttributes: {
                    min: 0,
                    step: 1
                },
                showCancelButton: true,
                confirmButtonText: confirmLabel,
                cancelButtonText: cancelLabel,
                preConfirm: (value) => {
                    return new Promise((resolve, reject) => {
                        if (value === '' || value === null) {
                            Swal.showValidationMessage('{{ __('validation.required', ['attribute' => 'quantity']) }}');
                            reject();
                            return;
                        }

                        const parsed = parseInt(value, 10);
                        if (isNaN(parsed) || parsed < 0) {
                            Swal.showValidationMessage('{{ __('validation.min.numeric', ['attribute' => 'quantity', 'min' => 0]) }}');
                            reject();
                            return;
                        }

                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                _method: 'PATCH',
                                _token: '{{ csrf_token() }}',
                                quantity: parsed
                            },
                            success: function(response) {
                                resolve(response);
                            },
                            error: function(xhr) {
                                let message = '{{ __('product.error_updating') }}';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }
                                Swal.showValidationMessage(message);
                                reject();
                            }
                        });
                    });
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const { quantity, product_id } = result.value;
                    button.data('current-quantity', quantity);
                    const $cell = $(`.product-quantity[data-product-id="${product_id}"]`);
                    $cell.text(quantity);
                    Swal.fire({
                        icon: 'success',
                        title: quantityUpdatedMessage,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });
    });
</script>
@endsection
