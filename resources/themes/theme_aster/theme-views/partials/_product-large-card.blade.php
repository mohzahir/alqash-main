@php($overallRating = $product->reviews ? \App\CPU\ProductManager::get_overall_rating($product->reviews) : 0)
<div class="product border rounded text-center d-flex flex-column gap-10 ov-hidden cursor-pointer"
     >
    <!-- Product top -->
    <div class="product__top" style="--width: 100%;" onclick="location.href='{{route('product',$product->slug)}}'">
        @if (auth('customer')->check())
            @if($product->discount > 0)
                <span class="product__discount-badge">
                    -@if ($product->discount_type == 'percent')
                        {{round($product->discount, $web_config['decimal_point_settings'])}}%
                    @elseif($product->discount_type =='flat')
                        {{\App\CPU\Helpers::currency_converter($product->discount)}}
                    @endif
                </span>
            @endif
        @endif

        

        @if(isset($product->flash_deal_status) && $product->flash_deal_status)
            <div class="product__power-badge">
                <img src="{{ theme_asset('assets/img/svg/power.svg') }}" alt=""
                     class="svg text-white">
            </div>
        @endif
        @php($wishlist = count($product->wish_list)>0 ? 1 : 0)
       
        @php($compare_list = count($product->compare_list)>0 ? 1 : 0)
       
        <div class="product__actions d-flex flex-column gap-2">
            @if (auth('customer')->check())
             @php($wishlist_status = App\Model\Wishlist::where(['product_id'=>$product->id, 'customer_id'=>auth('customer')->id()])->count())
                @php($wishCount =  App\Model\Wishlist::where('customer_id',auth('customer')->user()->id)->get()->count())
               
                <a href="javascript:" class="btn-wishlist stopPropagation add_to_wishlist wishlist-{{$product['id']}} {{($wishlist_status > 0?'wishlist_icon_active':'')}}" data-bs-toggle="modal" data-bs-target="#exampleModal{{$product['id']}}" id="wishlist-{{$product['id']}}">
                        <i class="bi bi-heart"></i>
                    </a>
                  
                    
            @else
                <a href="javascript:" onclick="addWishlist('{{$product['id']}}','{{route('store-wishlist')}}')"
                   id="wishlist-{{$product['id']}}"
                   class="btn-wishlist stopPropagation add_to_wishlist wishlist-{{$product['id']}} {{($wishlist == 1?'wishlist_icon_active':'')}}"
                   title="Add to wishlist">
                    <i class="bi bi-heart"></i>
                </a>
            @endif
            
            <a href="javascript:"
               class="btn-compare stopPropagation add_to_compare compare_list-{{$product['id']}} {{($compare_list == 1?'compare_list_icon_active':'')}}"
               onclick="addCompareList('{{$product['id']}}','{{route('store-compare-list')}}')"
               id="compare_list-{{$product['id']}}" title="Add to compare">
                <i class="bi bi-repeat"></i>
            </a>
            <a href="javascript:" class="btn-quickview stopPropagation"
               onclick="quickView('{{$product->id}}', '{{route('quick-view')}}')" title="{{ translate('quick_view') }}">
                <i class="bi bi-eye"></i>
            </a>
        </div>

        <div class="product__thumbnail">
            <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                 onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" loading="lazy"
                 class="img-fit dark-support rounded" alt="">
        </div>
        @if(($product['product_type'] == 'physical') && ($product['current_stock'] < 1))
            <div class="product__notify">
                {{ translate('sorry') }}, {{ translate('this_item_is_currently_sold_out') }}
            </div>
        @endif

        @if(isset($product->flash_deal_status) && $product->flash_deal_status)
            <div class="product__countdown d-flex gap-2 gap-sm-3 justify-content-center"
                 data-date="{{ $product->flash_deal_end_date }}">
                <div class="days d-flex flex-column gap-2"></div>
                <div class="hours d-flex flex-column gap-2"></div>
                <div class="minutes d-flex flex-column gap-2"></div>
                <div class="seconds d-flex flex-column gap-2"></div>
            </div>
        @endif
    </div>

    <!-- Product Summery -->
    <div class="product__summary d-flex flex-column align-items-center gap-1 pb-3 cursor-pointer">
        <!--<div class="d-flex gap-2 align-items-center">-->
        <!--    <div class="star-rating text-gold fs-12">-->
        <!--        @for ($i = 1; $i <= 5; $i++)-->
        <!--            @if ($i <= (int)$overallRating[0])-->
        <!--                <i class="bi bi-star-fill"></i>-->
        <!--            @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))-->
        <!--                <i class="bi bi-star-half"></i>-->
        <!--            @else-->
        <!--                <i class="bi bi-star"></i>-->
        <!--            @endif-->
        <!--        @endfor-->
        <!--    </div>-->
        <!--    <span>( {{count($product->reviews)}} )</span>-->
        <!--</div>-->

        <!--<div class="text-muted fs-12">-->
        <!--    @if($product->added_by=='seller')-->
        <!--        {{ isset($product->seller->shop->name) ? \Illuminate\Support\Str::limit($product->seller->shop->name, 20) : '' }}-->
        <!--    @elseif($product->added_by=='admin')-->
        <!--        {{$web_config['name']->value}}-->
        <!--    @endif-->
        <!--</div>-->

        <h6 class="product__title text-truncate">
            {{ \Illuminate\Support\Str::limit($product['name'], 25) }}
        </h6>
        
         <h8 class="product__title text-truncate">
            {{$product->disc}}
        </h8>
        @if(auth('customer')->check())
        <div class="product__price d-flex justify-content-center flex-wrap column-gap-2">
            @if($product->discount > 0)
                <del class="product__old-price">{{\App\CPU\Helpers::currency_converter($product->unit_price)}}</del>
            @endif
            <ins class="product__new-price">
                {{\App\CPU\Helpers::currency_converter($product->unit_price-\App\CPU\Helpers::get_product_discount($product,$product->unit_price))}}
            </ins>
        </div>
        

        @endif
            <form class="cart add_to_cart_form" data-form-id="{{$product['id']}}" action="{{ route('cart.add') }}"
                  data-varianturl="{{ route('cart.variant_price') }}"
                  data-errormessage="{{translate('please_choose_all_the_options')}}"
                  data-outofstock="{{translate('Sorry').', '.translate('Out_of_stock')}}.">
                @csrf
                <div class="">
                    <input type="hidden" name="id" value="{{ $product->id }}">
                        @if (count(json_decode($product->colors)) > 0)
                            @foreach (json_decode($product->colors) as $key => $color)
                                        <input type="radio" hidden="hidden"
                                               id="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                               name="color" value="{{ $color }}"
                                            {{ $key == 0 ? 'checked' : '' }}
                                        >
                            @endforeach
                        @endif

                        @foreach (json_decode($product->choice_options) as $key => $choice)
                            @foreach ($choice->options as $key => $option)
                                        <input type="radio" hidden="hidden"
                                               id="{{ $choice->name }}-{{ $option }}"
                                               name="{{ $choice->name }}"
                                               value="{{ $option }}"
                                               @if($key == 0) checked @endif >
                            @endforeach
                        @endforeach

                        <input type="hidden"
                               class="quantity__qty product_quantity__qty"
                               name="quantity"
                               value="{{ $product->minimum_order_qty ?? 1 }}"
                               min="{{ $product->minimum_order_qty ?? 1 }}"
                               max="{{$product['product_type'] == 'physical' ? $product->current_stock : 100}}">


                    <div class="mx-w d-flex flex-wrap mt-4"
                         style="--width: 24rem">
                            <button type="button"
                                    class="update_cart_button btn btn-primary fs-16"
                                    onclick="addToCartNew(this,'add-to-cart-form')">{{translate('add_to_Cart')}}</button>
                    </div>
                </div>
            </form>        
    </div>
</div>






  @if (auth('customer')->check())
                                                  
                                                    <div class="modal fade" id="exampleModal{{$product['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel{{$product['id']}}" aria-hidden="false">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <form action="{{ route('storeCategoryWishlist') }}" method="post">
                                                                        @csrf
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <input type="text" name="name" class="form-control" placeholder="Wishlist Category Name">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <input type="submit" value="Create" class="form-control btn btn-success" style="background-color: #1dc16f;">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @php($wishlistcategory = App\Model\WishlistCategory::where('user_id',auth('customer')->id())->get())
                                                                    @if(isset($wishlistcategory) && $wishlistcategory->count() > 0)
                                                                        <div class="row mb-3">
                                                                            @foreach($wishlistcategory as $category)
                                                                            
                                                                            @php([$wishlistPro = App\Model\Wishlist::where('customer_id',auth('customer')->id())->where('wishlist_category_id',$category->id)->where('product_id',$product->id)->first(),  $wishlistCount = App\Model\Wishlist::where('customer_id',auth('customer')->id())->get()->count()])
                                                                            
                                                                            @if($wishlistPro && $wishlistPro->count() > 0)
                                                                            
                                                                              <div class="col-sm-4 mb-4 proAdd{{$category['id']}}" id="pro{{$category['id']}}" style="background-color: #080; margin-right: 10px; padding: 4px; text-align: center; width:30%;">
                                                                                    <a onclick="addWishlist('{{$product['id']}}','{{route('store-wishlist')}}','{{$category['id']}}')" id="wishlist-{{$product['id']}}"  class="cursor-pointer wishlist-{{$product['id']}}  title="{{translate('add_to_wishlist')}}">
                                                                                        {{ $category->name }}
                                                                                    </a>
                                                                                </div>
                                                                            @else
                                                                            
                                                                              <div class="col-sm-4 mb-4 proAdd{{$category['id']}}" style="background-color: #ad8e29; margin-right: 10px; padding: 4px; text-align: center; width:30%; ">
                                                                                    <a onclick="addWishlist('{{$product['id']}}','{{route('store-wishlist')}}','{{$category['id']}}')" id="wishlist-{{$product['id']}}"  class="cursor-pointer wishlist-{{$product['id']}}  title="{{translate('add_to_wishlist')}}">
                                                                                        {{ $category->name }}
                                                                                    </a>
                                                                                </div>
                                                                            @endif
                                                                              
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                
                                                                </div>
                                                            </div>
                                                    </div>
                                                @endif