@extends('layout.index')

@section('meta')
    <meta name="description" content="Powercom.uz">
    <meta name="og:title" property="og:title" content="Powercom.uz">
    <meta name="twitter:card" content="Powercom.uz">
@endsection

@section('body')
<section class="section is-medium has-text-centered">
    <figure class="has-text-centered mt-20">
        <img src="{{ URL::asset('/image/logo.svg') }}" style="width: 160px;" alt="Powercom.uz">
    </figure>
    <p>Страница не найдена</p>
</section>
@endsection