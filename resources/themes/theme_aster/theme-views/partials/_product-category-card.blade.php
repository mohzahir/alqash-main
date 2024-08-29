<div class="swiper-slide">
    <div>
    <!-- Single Product -->
    <a href="javascript:"
       class="store-product d-flex flex-column gap-2 align-items-center ov-hidden">
        <div class="store-product__top border rounded mb-2">

            @if(isset($product->flash_deal_status) && $product->flash_deal_status == 1)
            <div class="product__power-badge">
                <img src="{{theme_asset('assets/img/svg/power.svg')}}" alt="" class="svg text-white">
            </div>
            @endif
            @if(auth('customer')->check())
                @if($product->discount > 0)
                    <span class="product__discount-badge">-
                         @if ($product->discount_type == 'percent')
                            {{round($product->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                        @elseif($product->discount_type =='flat')
                            {{\App\CPU\Helpers::currency_converter($product->discount)}}
                        @endif
                    </span>
                @endif
            @endif
            <span class="store-product__action preventDefault"  onclick="location.href='{{route('product',$product->slug)}}'">
                <i class="bi bi-eye fs-12"></i>
            </span>

            <img width="155"
                 src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                 onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" alt=""
                 loading="lazy" class="dark-support rounded">
        </div>
        <a class="fs-16 text-truncate text-muted text-capitalize" href="{{route('product',$product->slug)}}" >
            <h6 class="product__title text-truncate">
            {{ \Illuminate\Support\Str::limit($product['name'], 25) }}
        </h6>
        
         <h8 class="product__title text-truncate">
            {{$product->disc}}
        </h8>
            @if(auth('customer')->check())
            <div class="product__price d-flex justify-content-center align-items-center flex-wrap column-gap-2 mt-1">
                @if($product->discount > 0)
                    <del class="product__old-price">
                        {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                    </del>
                @endif
                <ins class="product__new-price fs-14">
                    {{\App\CPU\Helpers::currency_converter($product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price)))}}
                </ins>
            </div>

        </a>
    </a>
    @endif
    </div>
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
 
