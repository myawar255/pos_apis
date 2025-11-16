@extends('layouts.admin')

@section('title', __('product.Barcode') . ' ' . __('product.Product_List'))
@section('content-header', __('product.Barcode') . ' ' . __('product.Product_List'))

@section('content-actions')
<button type="button" class="btn btn-primary" onclick="window.print()">
    <i class="fas fa-print"></i> {{ __('common.Print') }}
</button>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if($products->isEmpty())
            <p class="mb-0 text-muted">{{ __('product.no_products_available') }}</p>
        @else
            <div class="barcode-grid">
                @foreach ($products as $product)
                    @php($svg = code39_svg($product->barcode))
                    <div class="barcode-card">
                        <h5 class="barcode-title">{{ $product->name }}</h5>
                        @if ($svg)
                            <div class="barcode-image">{!! $svg !!}</div>
                            <div class="barcode-text">{{ $product->barcode }}</div>
                        @else
                            <div class="barcode-error text-danger">
                                {{ __('product.unable_to_render_barcode') }}
                            </div>
                            <div class="barcode-text">{{ $product->barcode }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@section('css')
<style>
    .barcode-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    }

    .barcode-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
    }

    .barcode-title {
        font-size: 1rem;
        margin-bottom: 0.75rem;
    }

    .barcode-image svg {
        width: 100%;
        height: 80px;
    }

    .barcode-text {
        margin-top: 0.5rem;
        letter-spacing: 0.2em;
        font-weight: 600;
    }

    @media print {
        body {
            background: #fff !important;
        }

        .main-sidebar,
        .main-header,
        .content-header,
        .navbar,
        .card > .card-header,
        .btn {
            display: none !important;
        }

        .barcode-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .barcode-card {
            border: none;
            padding: 0.5rem;
        }

        .content {
            margin: 0;
        }
    }
</style>
@endsection
