@extends('layout.index')

@section('meta')
    <meta name="description" content="{{ $article->name }}">
    <meta name="og:title" property="og:title" content="{{ $article->name }}">
    <meta name="twitter:card" content="{{ $article->name }}">
@endsection

@section('body')
<figure class="image">
    @if(count($article->images) > 0)
        <img src="{{ $article->images[0]->url }}" alt="{{ $article->name }}">
    @endif
</figure>
<section class="section">
    <div class="container">
        <h1 class="title has-text-centered">
            {{ $article->name }}
            <br>
            <span class="tag is-size-7">{{ $article->updated_at->format('d-m-Y') }}</span>
        </h1>
        <br>
        <div class="content">
            {!! $article->description !!}
        </div>
    </div>
</section>
@endsection