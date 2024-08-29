@php($overallRating = $product->reviews ? \App\CPU\ProductManager::get_overall_rating($product->reviews) : 0)
<!-- Single Product -->
<div class="product d-flex flex-column gap-10" >
    <!-- Product top -->
    <div class="product__top border rounded" onclick="location.href='{{route('product',$product->slug)}}'">
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


        @php($wishlist = count($product->wish_list)>0 ? 1 : 0)
        @php($compare_list = count($product->compare_list)>0 ? 1 : 0)
        <div class="product__actions d-flex flex-column gap-2">
            <a href="javascript:" onclick="addWishlist('{{$product['id']}}','{{route('store-wishlist')}}')"
               id="wishlist-{{$product['id']}}"
               class="btn-wishlist stopPropagation add_to_wishlist wishlist-{{$product['id']}} {{($wishlist == 1?'wishlist_icon_active':'')}}"
               title="{{ translate('add_to_wishlist') }}">
                <i class="bi bi-heart"></i>
            </a>
            <a href="javascript:"
               class="btn-compare stopPropagation add_to_compare compare_list-{{$product['id']}} {{($compare_list == 1?'compare_list_icon_active':'')}}"
               title="{{ translate('add_to_compare') }}"
               onclick="addCompareList('{{$product['id']}}','{{route('store-compare-list')}}')"
               id="compare_list-{{$product['id']}}">
                <i class="bi bi-repeat"></i>
            </a>
            <a href="javascript:" onclick="quickView('{{$product->id}}', '{{route('quick-view')}}')"
               class="btn-quickview stopPropagation" title="{{ translate('quick_view') }}">
                <i class="bi bi-eye"></i>
            </a>
        </div>

        <div>
            <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                 onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                 loading="lazy" class="img-fit dark-support rounded" alt="">
        </div>
        
                    <form class="cart add_to_cart_form" action="{{ route('cart.add') }}"
                  id="add-to-cart-form" 
                  data-varianturl="{{ route('cart.variant_price') }}"
                  data-errormessage="{{translate('please_choose_all_the_options')}}"
                  data-outofstock="{{translate('Sorry').', '.translate('Out_of_stock')}}.">
                @csrf
                <div class="">
                    <input type="hidden" name="id" value="{{ $product->id }}">




                    <div class="mx-w d-flex flex-wrap mt-4"
                         style="--width: 24rem">



                            <button type="button"
                                    class="update_cart_button btn btn-primary fs-16"
                                    onclick="addToCart('add-to-cart-form')">{{translate('add_to_Cart')}}</button>
                       
                    </div>

                </div>
            </form>
    </div>

    <!-- Product Summery -->
    <div class="product__summary d-flex flex-column gap-1 cursor-pointer">
        <!--<div class="d-flex gap-2 align-items-center">-->
        <!--    <div class="star-rating text-gold fs-12">-->
        <!--        @for ($i = 1; $i <= 5; $i++)-->
        <!--            @if ($i <= (int)$overallRating[0])-->
        <!--                <i class="bi bi-star-fill"></i>-->
        <!--            @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1)-->
        <!--                <i class="bi bi-star-half"></i>-->
        <!--            @else-->
        <!--                <i class="bi bi-star"></i>-->
        <!--            @endif-->
        <!--        @endfor-->
        <!--    </div>-->
        <!--    <span>( {{$product->reviews->count()}} )</span>-->
        <!--</div>-->

        <!--<div class="text-muted fs-12">-->
        <!--    @if($product->added_by=='seller')-->
        <!--        {{ isset($product->seller->shop->name) ? \Illuminate\Support\Str::limit($product->seller->shop->name, 20) : '' }}-->
        <!--    @elseif($product->added_by=='admin')-->
        <!--        {{$web_config['name']->value}}-->
        <!--    @endif-->
        <!--</div>-->

        <h6 class="product__title text-truncate" style="--width: 80%">
            {{ \Illuminate\Support\Str::limit($product['name'], 18) }}
        </h6>
        
        <h8 class="product__title text-truncate">
            {{$product->disc}}
        </h8>
        
        @if(auth('customer')->check())
        <div class="product__price d-flex flex-wrap column-gap-2">
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
