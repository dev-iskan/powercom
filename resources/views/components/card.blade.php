<a class="has-text-dark" href="{{ route('product', ['id' => $product->id]) }}">
    <div class="card product-card is-shadowless border">
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
                <p>{{ number_format($product->price, 0) }} сум</p>
            </div>
        </div>
        <footer class="card-footer">
            {{-- <a class="card-footer-item button is-white is-radiusless" style="height: 100%"
                href="{{ route('product', ['id' => $product->id]) }}">
                <span class="icon has-text-grey">
                    <i class="fas fa-book-open"></i>
                </span>
            </a> --}}
            <a class="card-footer-item button is-white" style="height: 100%"
                href="{{ route('cart.store', ['product_id' => $product->id]) }}">
                <span class="icon has-text-grey">
                    <i class="fas fa-shopping-cart"></i>
                </span>
            </a>
        </footer>
    </div>
</a>