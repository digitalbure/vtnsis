@extends('admin.layouts.app')

@section('content')
    <div class="p-sm-30 p-15">
        <div class="d-flex align-items-center justify-content-between flex-wrap g-15 pb-26">
            <h2 class="fs-24 fw-600 lh-29 text-primary-dark-text">{{__(@$pageTitle)}}</h2>
            <div class="">
                <div class="breadcrumb__content p-0">
                    <div class="breadcrumb__content__right">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb sf-breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{__(@$pageTitle)}}</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15" id="appendList">
            @include('admin.earning.partial.render-product-earning-history')
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/magnific-popup.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{ asset('admin/js/custom/earning-product-history.js') }}"></script>
@endpush
