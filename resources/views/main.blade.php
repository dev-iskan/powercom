@extends('layout.index')

@section('meta')
    <meta name="description" content="Powercom.uz - Интернет магазин">
    <meta name="og:title" property="og:title" content="Powercom.uz - Интернет магазин">
    <meta name="twitter:card" content="Powercom.uz - Интернет магазин">
@endsection

@section('body')
<div class="swiper-container hero">
    <div class="swiper-wrapper">
        @foreach($articles as $article)
            <div class="swiper-slide has-text-centered">
                <figure class="image">
                    @if(count($article->images) > 0)
                        <img src="{{ $article->images[0]->url }}" alt="{{ $article->name }}">
                    @endif
                </figure>
                <div class="content">
                    <h1>{{ $article->name }}</h1>
                    <p>{{ $article->short_description }}</p>
                    <a href="{{ route('article', ['id' => $article->id]) }}">Перейти</a>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
</div>
<section class="section">
    <h3 class="is-size-4 has-text-weight-bold has-text-centered">КАК ЗАКАЗАТЬ?</h3>
    <br>
    <div class="container">
        <div class="columns">
            <div class="column is-one-quarter">
                <div class="card is-shadowless border">
                    <div class="card-content">
                        <article class="media">
                            <figure class="media-left">
                                <span class="icon">
                                    <i class="fas fa-lg fa-sync"></i>
                                </span>
                            </figure>
                            <div class="media-content">
                                <div class="content">
                                    <p>Выбираете товар</p>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
            <div class="column is-one-quarter">
                <div class="card is-shadowless border">
                    <div class="card-content">
                        <article class="media">
                            <figure class="media-left">
                                <span class="icon">
                                    <i class="fas fa-lg fa-map"></i>
                                </span>
                            </figure>
                            <div class="media-content">
                                <div class="content">
                                    <p>Указываете адрес доставки</p>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
            <div class="column is-one-quarter">
                <div class="card is-shadowless border">
                    <div class="card-content">
                        <article class="media">
                            <figure class="media-left">
                                <span class="icon">
                                    <i class="fas fa-lg fa-credit-card"></i>
                                </span>
                            </figure>
                            <div class="media-content">
                                <div class="content">
                                    <p>Производите оплату</p>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
            <div class="column is-one-quarter">
                <div class="card is-shadowless border">
                    <div class="card-content">
                        <article class="media">
                            <figure class="media-left">
                                <span class="icon">
                                    <i class="fas fa-lg fa-flag-checkered"></i>
                                </span>
                            </figure>
                            <div class="media-content">
                                <div class="content">
                                    <p>И мы доставим ваш заказ!</p>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section">
    <h3 class="is-size-4 has-text-weight-bold has-text-centered">НОВЫЕ ТОВАРЫ</h3>
    <br>
    <div class="container">
        <div class="columns is-multiline is-centered">
            @foreach ($products as $product)
                <div class="column is-one-third-tablet is-one-quarter-desktop">
                    @include('components.card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>
<section class="section">
    <h3 class="is-size-4 has-text-weight-bold has-text-centered">ПОПУЛЯРНЫЕ ТОВАРЫ</h3>
    <br>
    <div class="container">
        <div class="columns is-multiline is-centered">
            @foreach ($products as $product)
                <div class="column is-one-third-tablet is-one-quarter-desktop">
                    @include('components.card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>
<hr>
<section class="section pt-20">
    <div class="content">
        <div class="container">
            <h2 class="has-text-centered">О САЙТЕ</h1>
            <div class="columns has-text-justified">
                <div class="column">
                    <p>
                        Целью создания данного сайта является открытие электронно-коммерческого и информационного портала нашей компании для клиентов. Развитие нового направления бизнеса для нас для удобства клиентов, а именно предложения услуг и товаров со склада в г. Ташкенте, в дальнейшем возможно в регионах Узбекистана. Прежде всего мы начали с поставки на склад оборудования по энергоснабжению. На базе дистрибьютерских и/или партнерских соглашений с производителями в том числе с авторизацией на оказание гарантийных и пост-гарантийных услуг: KSTAR (UPS и аккумуляторные батареи), TEKSAN GENERATORS (Дизель и бензиновые электростанции), GENMAC (Дизель и бензиновые электростанции), LEOCH (аккумуляторные батареи), Anadolu Motor (Бензиновые электростанции).
                    </p>
                </div>
                <div class="column">
                    <p>
                        В ближайших планах нашей компании создание склада продукции по серверам, IP-телефонии, камерам видеонаблюдения и прочей офисной техники. Штат нашей компании превышает 50 сотрудников, инженеров по связи, инженеров-электриков и энергетиков составляет около 20 человек. Что говорит о надежности компании в бесперебойном обеспечении консультаций, гарантийного, пост-гарантийного сервисов нашим клиентам.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    window.onload = function() {
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