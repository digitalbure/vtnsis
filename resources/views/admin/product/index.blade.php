@extends('admin.layouts.app')

@section('content')
    <!-- Page content area start -->
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
        @if($status == null)
            <div class="alert alert-warning" role="alert">
                {{__("Editor's Choice Can Show Up To 11 Items In Frontend.")}}
            </div>
        @endif
        <div class="" id="appendList">
            <div class="d-flex flex-column-reverse flex-sm-row justify-content-center justify-content-md-between align-items-center flex-wrap g-10 pb-18">
                <div class="search-one flex-grow-1 max-w-258">
                    <input type="text" id="search-key" placeholder="{{__('Search here')}}...">
                    <button class="icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.71401 15.7857C12.6194 15.7857 15.7854 12.6197 15.7854 8.71428C15.7854 4.80884 12.6194 1.64285 8.71401 1.64285C4.80856 1.64285 1.64258 4.80884 1.64258 8.71428C1.64258 12.6197 4.80856 15.7857 8.71401 15.7857Z" stroke="#707070" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M18.3574 18.3571L13.8574 13.8571" stroke="#707070" stroke-width="1.35902" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                <table class="table zTable zTable-last-item-right" id="product-datatable">
                    <thead>
                    <tr>
                        <th><div>{{__('Sl.')}}</div></th>
                        <th><div>{{__('Image')}}</div></th>
                        <th><div>{{__('Title')}}</div></th>
                        <th><div>{{__('Type')}}</div></th>
                        <th><div>{{__('Category')}}</div></th>
                        <th><div>{{__('Accessibility')}}</div></th>
                        <th><div>{{__('Status')}}</div></th>
                        @if($status == null)
                            <th><div class="text-nowrap">{{__("Editor's Choice")}}</div></th>
                        @endif
                        <th><div>{{__('Created By')}}</div></th>
                        <th><div>{{__('Action')}}</div></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
    <input type="hidden" value="{{ route('admin.product.status',$status) }}" id="product-route">
    <input type="hidden" value="{{ $status ?? 0}}" id="status">

    <div class="modal fade" id="status-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bd-c-stroke-color bd-ra-12 py-25 px-20">

            </div>
        </div>
    </div>

    <div class="modal fade" id="is-feature-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bd-c-stroke-color bd-ra-12 py-25 px-20">

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('admin/js/custom/product.js') }}"></script>
@endpush
