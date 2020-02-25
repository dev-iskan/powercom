@extends('layout.index')

@section('meta')
    <meta name="description" content="Powercom.uz - Интернет магазин">
    <meta name="og:title" property="og:title" content="Powercom.uz - Интернет магазин">
    <meta name="twitter:card" content="Powercom.uz - Интернет магазин">
@endsection

@section('body')
<section class="hero is-info is-large">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                Powercom.uz
            </h1>
            <h2 class="subtitle">
                Интернет магазин
            </h2>
        </div>
    </div>
</section>
<section class="section">
    <h3 class="is-size-4 has-text-weight-bold has-text-centered">НОВЫЕ ТОВАРЫ</h3>
    <br>
    <div class="container">
        <div class="columns is-multiline">
            @foreach ($products as $product)
                <div class="column is-one-third-tablet is-one-quarter-desktop">
                    @include('components.card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection