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
                    @if(count($cart->items))
                        @foreach ($cart->items as $cart_item)
                            <div class="card mb-20 is-shadowless border">
                                <div class="card-content">
                                    <article class="media">
                                        <figure class="media-left">
                                            <p class="image is-64x64">
                                                @if(count($cart_item['data']->images) > 0)
                                                    <img src="{{ $cart_item['data']->images[0]->url }}"
                                                         alt="{{ $cart_item['data']->name }}">
                                                @else
                                                    <img src="https://bulma.io/images/placeholders/128x128.png">
                                                @endif
                                            </p>
                                        </figure>
                                        <div class="media-content">
                                            <div class="content">
                                                <p class="cart-product-name">
                                                    <strong>{{ $cart_item['data']->name }}</strong>
                                                    <br>
                                                    {{ $cart_item['data']->short_description }}
                                                </p>
                                                <small>
                                                    @if($cart_item['data']->quantity <= 0)
                                                        Нет наличии
                                                    @else
                                                        В наличии: {{ $cart_item['data']->quantity }} шт.
                                                    @endif
                                                </small>
                                            </div>
                                            <nav class="level is-mobile">
                                                <div class="level-left">
                                                    <div class="buttons has-addons">
                                                        <a class="button"
                                                           href="{{ route('cart.decrement', ['product_id' => $cart_item['data']->id]) }}">-</a>
                                                        <button class="button"
                                                                disabled>{{ $cart_item['quantity']}}</button>
                                                        <a class="button"
                                                           href="{{ route('cart.store', ['product_id' => $cart_item['data']->id]) }}">+</a>
                                                    </div>
                                                </div>
                                            </nav>
                                        </div>
                                        <div class="media-right">
                                            <a class="delete"
                                               href="{{ route('cart.destroy', ['product_id' => $cart_item['data']->id]) }}"></a>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        @endforeach
                    @else
                        Нету товаров в корзине
                    @endif

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
                                @if(count($cart->items))
                                    @foreach($cart->items as $cart_item)
                                        <tr>
                                            <td>
                                                <p>{{ $cart_item['data']->name }}</p>
                                            </td>
                                            <td>
                                                <p>{{ number_format($cart_item['price'], 0) }} сум</p>
                                            </td>
                                            <td>
                                                <p>{{$cart_item['quantity']}} шт.</p>
                                            </td>
                                            <td>
                                                <p>{{ number_format($cart_item['price'] * $cart_item['quantity'], 0) }}
                                                    сум</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="is-size-5 has-text-weight-bold has-text-right">
                                            Корзина пустая
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="is-size-5 has-text-weight-bold has-text-right">
                                        Всего:{{$cart->total_quantity}} шт {{ number_format($cart->total_price, 0) }}
                                        сум
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="has-text-weight-bold"></div>
                            <form action="{{route('orders.store')}}" method="POST">
                                @csrf
                                <label for="">Выберите тип доставки</label>
                                <div class="select">
                                    <select name="delivery" onchange="changeDelivery(this)">
                                        <option value="1" selecteds>С доставкой</option>
                                        <option value="0">Без доставки</option>
                                    </select>
                                </div>
                                <div class="control mt-20">
                                    <input class="input" type="text" name="full_name"
                                           value="{{old('full_name') ?? auth()->user()->client->full_name}}"
                                           placeholder="Ф.И.О. заказчика">
                                    @if($errors->has('full_name'))
                                        <p class="help is-danger">{{$errors->get('full_name')[0]}}</p>
                                    @endif
                                </div>
                                <div class="control mt-20">
                                    <input class="input" type="tel" name="phone"
                                           value="{{old('phone') ?? auth()->user()->client->phone}}"
                                           placeholder="Телефон номер заказчика">
                                    @if($errors->has('phone'))
                                        <p class="help is-danger">{{$errors->get('phone')[0]}}</p>
                                    @endif
                                </div>
                                <div class="control mt-20">
                                    <input id="address" class="input" type="text" name="address"
                                           value="{{old('address')}}"
                                           placeholder="Аддрес доставки">
                                    @if($errors->has('address'))
                                        <p class="help is-danger">{{$errors->get('address')[0]}}</p>
                                    @endif
                                </div>
                                <button class="button is-primary is-fullwidth mt-20">
                                    Оформить заказ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        function changeDelivery(e) {
            const input = document.getElementById('address');
            if (e.value === '1') {
                input.classList.remove('hidden');
            } else {
                input.classList.add('hidden');
            }
        }
    </script>
@endsection
