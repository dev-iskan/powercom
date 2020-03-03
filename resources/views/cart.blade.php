@extends('layout.index')

@section('meta')
    <meta name="description" content="Powercom.uz - Корзина">
    <meta name="og:title" property="og:title" content="Powercom.uz - Корзина">
    <meta name="twitter:card" content="Powercom.uz - Корзина">
@endsection

@section('body')
<section class="section ">
    <div class="container">
        <div class="columns">
            <div class="column is-half">
                @foreach ($products as $product)
                <div class="card mb-20 is-shadowless border">
                    <div class="card-content">
                        <article class="media">
                            <figure class="media-left">
                                <p class="image is-64x64">
                                    @if(count($product->images) > 0)
                                        <img src="{{ $product->images[0]->url }}" alt="{{ $product->name }}">
                                    @else
                                        <img src="https://bulma.io/images/placeholders/128x128.png">
                                    @endif
                                </p>
                            </figure>
                            <div class="media-content">
                                <div class="content">
                                    <p class="cart-product-name">
                                        <strong>{{ $product->name }}</strong>
                                        <br>
                                        {{ $product->short_description }}
                                    </p>
                                    <small>
                                        @if($product->quantity <= 0)
                                            Нет наличии
                                        @else
                                            В наличии: {{ $product->quantity }} шт.
                                        @endif
                                    </small>
                                </div>
                                <nav class="level is-mobile">
                                <div class="level-left">
                                    <div class="buttons has-addons">
                                        <button class="button">-</button>
                                        <button class="button" disabled>10</button>
                                        <button class="button">+</button>
                                    </div>
                                </div>
                                </nav>
                            </div>
                            <div class="media-right">
                                <button class="delete"></button>
                            </div>
                        </article>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="column is-half">
                <div class="card is-shadowless border">
                    <div class="card-content">
                        <p class="is-size-5 has-text-weight-bold has-text-centered">Информация о заказе</p>
                        <br>
                        <table class="table is-bordered is-fullwidth">
                            <thead>
                                <th>
                                    Название
                                </th>
                                <th>
                                    Цена
                                </th>
                                <th>
                                    Количество
                                </th>
                                <th>
                                    Сумма
                                </th>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <p>{{ $product->name }}</p>
                                        </td>
                                        <td>
                                            <p>{{ number_format($product->price, 0) }} сум</p>
                                        </td>
                                        <td>
                                            <p>10 шт.</p>
                                        </td>
                                        <td>
                                            <p>{{ number_format($product->price * 10, 0) }} сум</p>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="is-size-5 has-text-weight-bold has-text-right">
                                        Всего: 2,000,000 сум
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="has-text-weight-bold"></div>
                        <div class="select">
                            <select>
                                <option>Выберите тип доставки</option>
                                <option>С доставкой</option>
                                <option>Без доставки</option>
                            </select>
                        </div>
                        <div class="control mt-20">
                            <input class="input" type="text" placeholder="Аддрес доставки">
                        </div>
                        <div class="control mt-20">
                            <input class="input" type="text" placeholder="Ф.И.О. заказчика">
                        </div>
                        <div class="control mt-20">
                            <input class="input" type="tel" placeholder="Телефон номер заказчика">
                        </div>
                        <button class="button is-primary is-fullwidth mt-20">
                            Оформить заказ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection