<div class="card">
    <div class="card-image">
        <figure class="image is-1by1">
            @if(count($product->images) > 0)
                <img src="{{ $product->images[0]->url }}" alt="{{ $product->name }}">
            @else
                <img src="https://bulma.io/images/placeholders/480x480.png" alt="Powercom.uz">
            @endif
        </figure>
    </div>
    <div class="card-content">
        <div class="content">
            <p class="product-name" title="{{ $product->name }}">{{ $product->name }}</p>
            @if($product->quantity <= 0)
                <p class="is-size-7 has-text-danger">Нет наличии</p>
            @else
                <p class="is-size-7">В наличии: {{ $product->quantity }} шт.</p>
            @endif
        </div>
    </div>
    <footer class="card-footer">
        <p class="card-footer-item"> {{ number_format($product->price, 0) }} сум </p>
        <div class="card-footer-item button is-white" style="height: 100%">
            <span class="icon has-text-primary">
                <i class="fas fa-shopping-cart"></i>
            </span>
        </div>
    </footer>
</div>