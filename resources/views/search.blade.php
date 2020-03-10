@extends('layout.index')

@section('meta')
    <meta name="description" content="Powercom.uz - Поиск">
    <meta name="og:title" property="og:title" content="Powercom.uz - Поиск">
    <meta name="twitter:card" content="Powercom.uz - Поиск">
@endsection

@section('body')
    <section class="section">
        <h3 class="is-size-4 has-text-weight-bold has-text-centered">РЕЗУЛТАТЫ ПОИСКА</h3>
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
@endsection
