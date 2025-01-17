
@extends('layouts.back-end.app-seller')
@section('title', \App\CPU\translate('Shop Edit'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
     <!-- Custom styles for this page -->
     <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
     <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
    <!-- Content Row -->
    <div class="content container-fluid">

    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img width="20" src="{{asset('/public/assets/back-end/img/shop-info.png')}}" alt="">
            {{\App\CPU\translate('Edit_Shop_Info')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 ">{{\App\CPU\translate('Edit_company_Info')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('seller.shop.update',[$shop->id])}}" method="post"
                          style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="title-color">{{\App\CPU\translate('company_name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{$shop->name}}" class="form-control" id="name"
                                            required>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="title-color">{{\App\CPU\translate('Contact')}} <span class="text-info">( * {{\App\CPU\translate('country_code_is_must')}} {{\App\CPU\translate('like_for_BD_880')}} )</span></label>
                                    <input type="number" name="contact" value="{{$shop->contact}}" class="form-control" id="name"
                                            required>
                                </div>
                                <div class="form-group">
                                    <label for="stores_count">{{translate('Stores Count')}} *</label>
                                    <select class="form-control select2" name="stores_count" id="stores_count">
                                        <option value="">{{translate('Select Stores Count')}}</option>
                                        @for($i = 0 ; $i<=50 ; $i++)
                                            <option {{ $i == $shop->stores_count ? 'selected' : '' }} value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="store_type_id">{{translate('Store Type')}} *</label>
                                    <select class="form-control select2" name="store_type_id" id="store_type_id">
                                        <option value="">{{translate('Select Store Type')}}</option>
                                        @php($types = \App\Model\StoreType::all())
                                        @foreach($types as $type)
                                            <option {{ $type->id == $shop->store_type_id ? 'selected' : '' }} value="{{$type->id ?? ''}}">{{$type->name ?? ''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="title-color">{{\App\CPU\translate('company_address')}} <span class="text-danger">*</span></label>
                                    <textarea type="text" rows="4" name="address" value="" class="form-control" id="address"
                                            required>{{$shop->address}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="title-color">{{translate('Upload')}} {{\App\CPU\translate('company_tax_card')}}</label>
                                    <div class="custom-file text-left">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img class="upload-img-view" id="viewer"
                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                    src="{{asset('storage/app/public/shop/'.$shop->image)}}" alt="Product thumbnail"/>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4 mt-2">
                                <div class="form-group">
                                    <div class="flex-start">
                                        <label for="name" class="title-color">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('company_banner')}} </label>
                                        <div class="mx-1" for="ratio">
                                            <span class="text-info">{{\App\CPU\translate('Ratio')}} : ( 6:1 )</span>
                                        </div>
                                    </div>
                                    <div class="custom-file text-left">
                                        <input type="file" name="banner" id="BannerUpload" class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="BannerUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <center>
                                        <img class="upload-img-view upload-img-view__banner" id="viewerBanner"
                                             onerror="this.src='{{asset('public/assets/back-end/img/400x400/img2.jpg')}}'"
                                             src="{{asset('storage/app/public/shop/banner/'.$shop->banner)}}"alt="banner image"/>
                                    </center>
                                </div>
                            </div>

                            @if(theme_root_path() == "theme_aster")
                            <div class="col-md-6 mb-4 mt-2">
                                <div class="form-group">
                                    <div class="flex-start">
                                        <label for="name" class="title-color">{{translate('Upload')}} {{translate('company_commercial_id')}} </label>
                                        <div class="mx-1" for="ratio">
                                            <span class="text-info">{{translate('Ratio')}} : ( 6:1 )</span>
                                        </div>
                                    </div>
                                    <div class="custom-file text-left">
                                        <input type="file" name="bottom_banner" id="BottomBannerUpload" class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="BottomBannerUpload">{{translate('choose')}} {{translate('file')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <center>
                                        <img class="upload-img-view upload-img-view__banner" id="viewerBottomBanner"
                                             onerror="this.src='{{asset('public/assets/back-end/img/400x400/img2.jpg')}}'"
                                             src="{{asset('storage/app/public/shop/banner/'.$shop->bottom_banner)}}"alt="banner image"/>
                                    </center>
                                </div>
                            </div>
                            @endif

                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a class="btn btn-danger" href="{{route('seller.shop.view')}}">{{\App\CPU\translate('Cancel')}}</a>
                            <button type="submit" class="btn btn--primary" id="btn_update">{{\App\CPU\translate('Update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('script')

   <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readBannerURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewerBanner').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readBottomBannerURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewerBottomBanner').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });

        $("#BannerUpload").change(function () {
            readBannerURL(this);
        });
        $("#BottomBannerUpload").change(function () {
            readBottomBannerURL(this);
        });
   </script>

@endpush
