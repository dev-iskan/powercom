@extends('layout.index')

@section('meta')
    <meta name="description" content="{{ $product->name }}">
    <meta name="og:title" property="og:title" content="{{ $product->name }}">
    <meta name="twitter:card" content="{{ $product->name }}">
@endsection

@section('body')
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column is-one-third has-text-justified">
                    @if(count($product->images) > 0)
                        <div class="swiper-container border">
                            <div class="swiper-wrapper">
                                @foreach($product->images as $image)
                                    <div class="swiper-slide has-text-centered" style="">
                                        <figure class="image">
                                            <img src="{{ $image->url }}" alt="{{ $product->name }}">
                                        </figure>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Add Pagination -->
                            <div class="swiper-pagination"></div>
                        </div>
                    @else
                        <figure class="image is-1by1">
                            <img src="https://bulma.io/images/placeholders/480x480.png" alt="Powercom.uz">
                        </figure>
                    @endif
                    <br>
                    <p>
                        <strong>
                            {{ $product->name }}
                        </strong>
                    </p>
                    <br>
                    <p>
                        {{ $product->short_description }}
                    </p>
                    <br>
                    @if($product->quantity <= 0)
                        <p class="is-size-7 has-text-danger">Нет наличии</p>
                    @else
                        <p class="is-size-7">В наличии: {{ $product->quantity }} шт.</p>
                    @endif
                    <p class="is-size-5 has-text-weight-medium">
                        {{ number_format($product->price, 0) }} сум
                    </p>
                    <br>
                    <a class="button is-primary is-fullwidth has-text-white"
                       href="{{ route('cart.store', ['product_id' => $product->id]) }}">
                        Добавить в карзину
                    </a>
                </div>
                <div class="column is-two-third">
                    <p>
                        <strong>Информация о продукте</strong>
                    </p>
                    <br>
                    @if (count($product->categories) > 0)
                        <p>
                            <strong>
                                Категории:
                            </strong>
                            @foreach ($product->categories as $category)
                                
                                <a href="{{ route('products.index', ['categories' => $category->id]) }}">
                                    {{ $category->name }}
                                </a>
                                <span>-</span>
                                <span>
                                    {{ $category->short_description }}
                                </span>
                            @endforeach
                        </p>
                    @endif
                    @if ($product->brand)
                        <p>
                            <strong>
                                Бренд:
                            </strong>
                            <a href="{{ route('products.index', ['brands' => $product->brand->id]) }}">
                                {{ $product->brand->name }}
                            </a>
                        </p>
                        @if(count($product->brand->images))
                            @foreach($product->brand->images as $image)
                                {{-- <figure class="image is-1by1" style="width: 100px; height:100px;"> --}}
                                    <img src="{{ $image->url }}" alt="{{ $product->brand->name }}" style="width: 64px; height:64px;">
                                {{-- </figure> --}}
                            @endforeach
                        @endif
                    @endif
                    <br>
                    {!! $product->description !!}
                    @if (count($product->files) > 0)
                        <hr>
                        <p>
                            <strong>Дополнительная информация:</strong>
                        </p>
                        <br>
                        @foreach ($product->files as $file)
                            <a class="navbar-item" href="{{ $file->url }}">
                            <span class="icon">
                                <i class="fas fa-file-download"></i>
                            </span>
                                <span>
                                {{ $file->name }}
                            </span>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
            <br>
            <br>
        </div>
    </section>
@endsection

@section('script')
    <script>
        window.onload = function () {
            var Swiper = window.Swiper;
            var swiper = new Swiper('.swiper-container', {
                pagination: {
                    el: '.swiper-pagination',
                },
                autoplay: {
                    delay: 5000,
                },
            });
        }
    </script>
@endsection
