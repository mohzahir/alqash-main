@extends('theme-views.layouts.app')

@section('title', translate('Personal_Details').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))
@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <div class="row g-3">

                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')

                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5>{{translate('Edit_Personal_Details')}}</h5>
                                <a href="{{ route('user-profile') }}" class="btn-link text-secondary d-flex align-items-baseline">
                                    <i class="bi bi-chevron-left fs-12"></i> {{translate('Go_back')}}
                                </a>
                            </div>

                            <div class="mt-4">
                                <form  action="{{route('user-update')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row gy-4">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="f_name2">{{translate('First_Name')}}</label>
                                                <input type="text" id="f_name" class="form-control" value="{{$customerDetail['f_name']}}" name="f_name" placeholder="{{translate('Contact Person Name')}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="l_name2">{{translate('Last_Name')}}</label>
                                                <input type="text" id="l_name" class="form-control" value="{{$customerDetail['l_name']}}" name="l_name" placeholder="{{translate('Contact Person Name')}}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="store_name">{{translate('Store_Name')}}</label>
                                                <input type="text" id="store_name" class="form-control" value="{{$customerDetail['store_name']}}" name="store_name" placeholder="{{translate('store_name')}}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="store_adress">{{translate('Store_Address')}}</label>
                                                <input type="text" id="store_adress" class="form-control" value="{{$customerDetail['store_adress']}}" name="store_adress" placeholder="{{translate('store_adress')}}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="stores_count">{{translate('Stores Count')}} *</label>
                                            <select class="form-control select2" name="stores_count" id="stores_count">
                                                <option value="">{{translate('Select Stores Count')}}</option>
                                                @for($i = 0 ; $i<=50 ; $i++)
                                                    <option {{$customerDetail['stores_count'] == $i ? 'selected' : ''}} value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="store_type_id">{{translate('Store Type')}} *</label>
                                            <select class="form-control select2" name="store_type_id" id="store_type_id">
                                                <option value="">{{translate('Select Store Type')}}</option>
                                                @php($types = \App\Model\StoreType::all())
                                                @foreach($types as $type)
                                                    <option {{$customerDetail['store_type_id'] == $type->id ? 'selected' : ''}} value="{{$type->id}}">{{$type->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="phone2">{{translate('Phone')}}</label>
                                                <input type="text" id="phone" name="phone" class="form-control" value="{{$customerDetail['phone']}}" placeholder="{{translate('Ex:  01xxxxxxxxx')}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email2">{{translate('Email')}}</label>
                                                <input type="email" id="email2" class="form-control" value="{{$customerDetail['email']}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="password2">{{translate('Password')}}</label>
                                                <div class="input-inner-end-ele">
                                                    <input type="password" minlength="6" id="password" class="form-control" name="password" placeholder="{{translate('Ex: 6+ character')}}">
                                                    <i class="bi bi-eye-slash-fill togglePassword"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="confirm_password2">{{translate('Confirm_Password')}}</label>
                                                <div class="input-inner-end-ele">
                                                    <input type="password" minlength="6" id="confirm_password" name="confirm_password" class="form-control" placeholder="{{translate('Ex: 6+ character')}}">
                                                    <i class="bi bi-eye-slash-fill togglePassword"></i>
                                                </div>
                                            </div>
                                            <div id='message'></div>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <div class="form-group" style="margin-left:20%">
                                                <label>{{translate('Attachment')}}</label>
                                                <div class="d-flex flex-column gap-3">
                                                    <div class="upload-file">
                                                        <input type="file" class="upload-file__input"  name="image" accept="image/*">
                                                        <div class="upload-file__img">
                                                            <div class="temp-img-box" @if($customerDetail['image'] != null) style="display:none;" @endif>
                                                                <div class="d-flex align-items-center flex-column gap-2">
                                                                    <i class="bi bi-upload fs-30"></i>
                                                                    <div class="fs-12 text-muted">{{translate('change_your_profile')}}</div>
                                                                </div>
                                                            </div>
                                                            <img src="{{ asset('public/storage/profile/'.$customerDetail['image']) }}" class="dark-support img-fit-contain border" alt="">
                                                        </div>
                                                    </div>
                                                    <h5 class="text-uppercase mb-1">{{translate('User_image')}}</h5>
                                                    <div class="text-muted">{{translate('Image ratio should be 1:1')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                         <div class="col-lg-6 mb-4 mt-3">
                                                <div class="d-flex flex-column gap-3 align-items-center" style="margin-top:7%">
                                                    <div class="upload-file">
                                                        <input type="file" class="upload-file__input" name="banner" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                        <div class="upload-file__img style--two">
                                                            <div class="temp-img-box" @if($customerDetail['banner'] != null) style="display:none;" @endif>
                                                                <div class="d-flex align-items-center flex-column gap-2">
                                                                    <i class="bi bi-upload fs-30"></i>
                                                                    <div class="fs-12 text-muted">{{translate('Upload_File')}}</div>
                                                                </div>
                                                            </div>
                                                            <img src="{{ asset('public/storage/customer/banner/'.$customerDetail['banner']) }}" class="dark-support img-fit-contain border" alt="">
                                                        </div>
                                                    </div>

                                                    <div class="text-center">
                                                        <h5 class="text-uppercase mb-1">{{translate('Store_Banner')}}</h5>
                                                        <div class="text-muted">{{translate('Image_Ratio')}} 3:1</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6 mb-4">
                                                <div class="d-flex flex-column gap-3 align-items-center">
                                                    <div class="upload-file">
                                                        <input type="file" class="upload-file__input" name="secondary_banner" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required="">
                                                        <div class="upload-file__img style--two">
                                                            <div class="temp-img-box" @if($customerDetail['secondary_banner'] != null) style="display:none;" @endif>
                                                                <div class="d-flex align-items-center flex-column gap-2">
                                                                    <i class="bi bi-upload fs-30"></i>
                                                                    <div class="fs-12 text-muted">Upload File</div>
                                                                </div>
                                                            </div>
                                                            <img src="{{ asset('public/storage/customer/banner/'.$customerDetail['secondary_banner']) }}" class="dark-support img-fit-contain border" alt="">
                                                        </div>
                                                    </div>

                                                    <div class="text-center">
                                                        <h5 class="text-uppercase mb-1">{{translate('commercial_id')}}</h5>
                                                        <div class="text-muted">Image Ratio 3:1</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                             <div class="col-lg-6 mb-4">
                                                <div class="d-flex flex-column gap-3 align-items-center">
                                                    <div class="upload-file">
                                                        <input type="file" class="upload-file__input" name="logo" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                        <div class="upload-file__img">
                                                            <div class="temp-img-box" @if($customerDetail['logo'] != null) style="display:none;" @endif>
                                                                <div class="d-flex align-items-center flex-column gap-2">
                                                                    <i class="bi bi-upload fs-30"></i>
                                                                    <div class="fs-12 text-muted">{{translate('Upload_File')}}</div>
                                                                </div>
                                                            </div>
                                                            <img src="{{ asset('public/storage/customer/logo/'.$customerDetail['logo']) }}" class="dark-support img-fit-contain border" alt="">
                                                        </div>
                                                    </div>

                                                    <div class="text-center">
                                                        <h5 class="text-uppercase mb-1">{{translate('tax_card')}}</h5>
                                                        <div class="text-muted">{{translate('Image_Ratio')}} 1:1</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end gap-3">
                                                <button type="reset" class="btn btn-secondary">{{translate('Reset')}}</button>
                                                <button type="submit" class="btn btn-primary">{{translate('Update_Profile')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                
                                
                                <div class="row mt-5" > <br>
                                     
                                        @if($customerDetail->image1 != null)
                                            <div class="col-sm-4">
                                                <h3 class="mb-3">Customer Banner</h3>
                                                <div class="avatar overflow-hidden profile-sidebar-avatar border border-primary p-1">
                                                   <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                    src="{{asset('storage/app/public/profile')}}/{{$customerDetail->image1}}" alt="" class="img-fit dark-support">
                                                </div> 
                                            </div>
                                        @endif
                                        
                                        @if($customerDetail->image2 != null)
                                            <div class="col-sm-4">
                                                <h3 class="mb-3">Customer Bottom Banner</h3>
                                                <div class="avatar overflow-hidden profile-sidebar-avatar border border-primary p-1">
                                                    <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                    src="{{asset('storage/app/public/profile')}}/{{$customerDetail->image2}}" alt="" class="img-fit dark-support">
                                                </div> 
                                            </div>
                                        @endif
                                      
                                    
                                      
                                        @if($customerDetail->image3 != null)
                                            <div class="col-sm-4">
                                                <h3 class="mb-3">Customer Logo</h3>
                                                <div class="avatar overflow-hidden profile-sidebar-avatar border border-primary p-1">
                                                    <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                    src="{{asset('storage/app/public/profile')}}/{{$customerDetail->image3}}" alt="" class="img-fit dark-support">
                                                </div> 
                                            </div>
                                        @endif
                                </div>
                               
                
                
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
    <!-- End Main Content -->
@endsection

@push('script')
    <script>
        function checkPasswordMatch() {
            var password = $("#password").val();
            var confirmPassword = $("#confirm_password").val();
            $("#message").removeAttr("style");
            $("#message").html("");
            if (confirmPassword == "") {
                $("#message").attr("style", "color:black");
                $("#message").html("{{translate('Please ReType Password')}}");

            } else if (password == "") {
                $("#message").removeAttr("style");
                $("#message").html("");

            } else if (password != confirmPassword) {
                $("#message").html("{{translate('Passwords do not match')}}!");
                $("#message").attr("style", "color:red");
            } else if (confirmPassword.length <= 6) {
                $("#message").html("{{translate('password Must Be 6 Character')}}");
                $("#message").attr("style", "color:red");
            } else {

                $("#message").html("{{translate('Passwords match')}}.");
                $("#message").attr("style", "color:green");
            }
        }
        $(document).ready(function () {
            $("#confirm_password").keyup(checkPasswordMatch);
        });
    </script>
@endpush

