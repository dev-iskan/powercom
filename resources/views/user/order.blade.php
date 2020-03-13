@extends('layout.index')

@section('meta')
    <meta name="description" content="Powercom.uz - Интернет магазин">
    <meta name="og:title" property="og:title" content="Powercom.uz - Интернет магазин">
    <meta name="twitter:card" content="Powercom.uz - Интернет магазин">
@endsection

@section('body')
    <section class="section">
        <div class="container">
            <div class="content">
                <h2 class="has-text-centered">ЗАКАЗ: {{ $order->unique_id }}</h2>
                <br>
                <div class="columns">
                    <div class="column is-one-third">
                        <div class="border p-20">
                            <p>
                                <strong><small>Дата заказа:</small></strong>
                                <br>
                                {{ $order->created_at }}
                            </p>
                            <p>
                                <strong><small>Код заказа:</small></strong>
                                <br>
                                {{ $order->unique_id }}
                            </p>
                            <p>
                                <strong><small>Статус заказа:</small></strong>
                                <br>
                                <span class="tag is-{{ $order->status->class }}">
                                    {{ $order->status->name }}
                                </span>
                            </p>
                            <p>
                                <strong><small>Тип доставки:</small></strong>
                                <br>
                                {{ $order->delivery ? 'С доставкой' : 'Без доставки' }}
                            </p>
                            <p>
                                <strong><small>Сумма заказа:</small></strong>
                                <br>
                                {{ number_format($order->amount, 0) }} сум
                            </p>
                            <p>
                                <strong><small>Заказ оплачен:</small></strong>
                                <br>
                                {{ $order->paid ? 'Да' : 'Нет' }}
                            </p>
                            @if($order->status->id > 2)
                                <p>
                                    <strong><small>Дата получения (завершения) заказа:</small></strong>
                                    <br>
                                    {{ $order->finished_at }}
                                </p>
                            @endif
                            @if ($order->delivery)
                                <hr>
                                <p>
                                    <strong><small>Заказчик:</small></strong>
                                    <br>
                                    {{ $order->order_delivery->full_name }}
                                </p>
                                <p>
                                    <strong><small>Телефон номер заказчика:</small></strong>
                                    <br>
                                    +{{ $order->order_delivery->phone }}
                                </p>
                                <p>
                                    <strong><small>Адрес заказчика:</small></strong>
                                    <br>
                                    {{ $order->order_delivery->address }}
                                </p>
                                @if($order->order_delivery->delivered)
                                    <p>
                                        <strong><small>Дата доставки:</small></strong>
                                        <br>
                                        {{ $order->order_delivery->delivered_at }}
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="column is-two-third">
                        <table class="table is-bordered is-fullwidth">
                            <thead>
                                <th>Товар</th>
                                <th>Цена</th>
                                <th>Количество</th>
                                <th>Сумма</th>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ number_format($item->price, 0) }} сум</td>
                                        <td>{{ number_format($item->quantity, 0) }} шт.</td>
                                        <td>{{ number_format($item->quantity * $item->price, 0) }} сум</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="is-size-6 has-text-weight-bold has-text-right">
                                        Всего: {{ number_format($order->amount, 0) }} сум
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        @if(!$order->paid)
                            <p>Орпатить через:</p>
                            <div class="columns">
                                <div class="column is-narrow">
                                    <form method="post" action="https://checkout.paycom.uz">
                                        <input type="hidden" name="merchant" value="{{ config('local.payme_billing_service.kassa_id') }}">
                                        <input type="hidden" name="amount" value="{{ $order->amount * 100 }}">
                                        <input type="hidden" name="account[order_id]" value="{{ $order->unique_id }}">
                                        <button type="submit" class="button is-large">
                                            <img src="{{ URL::asset('/image/payme.png') }}" alt="payme" width="100">
                                        </button>
                                    </form>
                                </div>
                                <div class="column is-narrow">
                                    <form action="https://my.click.uz/services/pay" method="get">
                                        <input type="hidden" name="merchant_id" value="{{ config('local.click_billing.merchant_id') }}">
                                        <input type="hidden" name="service_id" value="{{ config('local.click_billing.service_id') }}">
                                        <input type="hidden" name="merchant_user_id" value="{{ config('local.click_billing.user_id') }}">
                                        <input type="hidden" name="transaction_param" value="{{ $order->unique_id }}">
                                        <input type="hidden" name="amount" value="{{ $order->amount }}">
                                        <input type="hidden" name="return_url" value="{{ route('order.show', ['id' => $order->id]) }}">
                                        <button type="submit" class="button is-large">
                                            <img src="{{ URL::asset('/image/click.png') }}" alt="click" width="100">
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
