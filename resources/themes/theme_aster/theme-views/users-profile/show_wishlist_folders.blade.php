@extends('theme-views.layouts.app')

@section('title', translate('My_Wishlists Folders').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="row g-3">

                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')

                <div class="col-lg-9">
                    

                    <div class="card h-lg-100">
                        <div class="card-body p-lg-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5>{{translate('My_Wish_List Folders')}}</h5>
                                
                            </div>

                            <div class="mt-4" id="set-wish-list">
                               <div class="table-responsive d-none d-md-block">
    <table class="table align-middle table-striped">
        <tbody>
        @if($wishlist_Category ->count()>0)
            @foreach($wishlist_Category  as $key=>$wishlist)
               
                    <td>
                        <div class="media gap-3 align-items-center mn-w200">
                          
                            <div class="media-body">
                                <form method="get" action="{{ route('wishlists') }}">
                                    @csrf
                                    
                                    <input type="text" value="{{$wishlist->id}}" name="id" style="display:none">
                                    
                                 <button class="btn-sm" style="width:50%; background-color:#54c58e;border-color:#fff;border-radius:5%;color:#fff">
                                     {{$wishlist->name}}
                                        
                                    </button>
                                </form>                               
                            </div>
                           
                                <div class="media-body">
                                    <h6 class="text-truncate"
                                        style="--width: 10ch">{{$wishlist->wishlists->count()}} </h6>
                                </div>
                           
                        </div>
                    </td>
                    <td>
                       
                    </td>
                    <td>
                        {{ $wishlist->created_at }}
                    </td>
                    <td>
                        <div class="d-flex gap-2 align-items-center mt-1">
                         
                            
                            
                            
                            
                            
                            <!-- Button trigger modal  Update-->
                            <a href="" class="btn btn-outline-success rounded-circle btn-action add_to_compare" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $wishlist->id }}">
                              <i class="bi bi-pencil-square"></i>
                            </a>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{ $wishlist->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">{{translate('Update Wishlist Category')}} {{ $wishlist->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <form action="{{ route('updateCategoryWishlist') }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" value="{{ $wishlist->id }}" name="id" style="display:none">
                                        
                                        <div class="form-group mb-3">
                                            <input type="text" value="{{ $wishlist->name }}" name="name" class="form-control text-center" plceholder="WishList Category Name">
                                        </div>
                                        
                                   
                                  </div>
                                  <div class="modal-footer">
                                    <!--<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                                     <div class="form-group">
                                            <input type="submit" value="{{translate('Update')}}" class="form-control" style="background-color:#54c58e">
                                        </div>
                                  </div>
                                   </form>
                                </div>
                              </div>
                            </div>











   <!-- Button trigger modal Trush-->
                            <a href="" class="btn btn-outline-danger rounded-circle btn-action" data-bs-toggle="modal" data-bs-target="#exampleModal1{{ $wishlist->id }}">
                              <i class="bi bi-trash3-fill"></i>
                            </a>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal1{{ $wishlist->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">{{translate('Delete Wishlist Category')}} {{ $wishlist->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <form action="{{ route('deleteCategoryWishlist') }}" method="post">
                                        @csrf
                                       
                                        <input type="text" value="{{ $wishlist->id }}" name="id" style="display:none">
                                        
                                        <div class="form-group mb-3">
                                            <input type="text" value="Are You Need To Delete {{ $wishlist->name }}" class="form-control text-center" disabled>
                                        </div>
                                        
                                   
                                  </div>
                                  <div class="modal-footer">
                                    <!--<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                                     <div class="form-group">
                                            <input type="submit" value="{{translate('Delete')}}" class="form-control" style="background-color:#54c58e">
                                        </div>
                                  </div>
                                   </form>
                                </div>
                              </div>
                            </div>
                            <!--<button type="button"  class="btn btn-outline-danger rounded-circle btn-action">-->
                            <!--    <i class="bi bi-trash3-fill"></i>-->
                            <!--</button>-->
                        </div>
                    </td>
                    </tr>
              
            @endforeach
        @endif
        @if($wishlist_Category->count()==0)
            <tr>
                <td><h5 class="text-center">{{translate('not_found_anything')}}</h5></td>
            </tr>
        @endif
        </tbody>
    </table>
</div>



<div class="card-footer border-0">
    {{$wishlist_Category ->links()}}
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
