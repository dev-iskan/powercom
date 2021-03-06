@extends('layout.index')

@section('meta')
    <meta name="description" content="Powercom.uz - Интернет магазин">
    <meta name="og:title" property="og:title" content="Powercom.uz - Интернет магазин">
    <meta name="twitter:card" content="Powercom.uz - Интернет магазин">
@endsection

@section('body')
    <section class="section has-background-white">
        <div class="container content">
            <div class="columns">
                <div class="column is-one-third">
                    <h2 class="">МОИ ДАННЫЕ</h2>
                    <div class="border p-20">
                        <p>
                            <strong><small>Ф.И.О.:</small></strong>
                            <br>
                            {{ $client->name }} {{ $client->surname }} {{ $client->patronymic }}
                        </p>
                        <p>
                            <strong><small>Телефон номер:</small></strong>
                            <br>
                            {{ $client->phone }}
                        </p>
                        <p>
                            <strong><small>Почта:</small></strong>
                            <br>
                            {{ $client->email ?? '-' }}
                        </p>
                        <p>
                            <strong><small>Дата регистрации:</small></strong>
                            <br>
                            {{ $client->created_at }}
                        </p>
                        <a href="{{ route('logout') }}" class="button is-fullwidth">Выйти</a>
                    </div>
                </div>
                <div class="column is-two-third">
                    <h2 class="">МОИ ЗАКАЗЫ: {{ count($orders) }}</h2>
                    <table class="table is-bordered is-fullwidth">
                        <thead>
                            <th>Код</th>
                            <th>Сумма</th>
                            <th>Тип доставки</th>
                            <th>Дата</th>
                            <th>Статус</th>
                            <th>Оплачен</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->unique_id }} </td>
                                    <td>{{ number_format($order->amount, 0) }} сум</td>
                                    <td>{{ $order->delivery ? 'С доставкой' : 'Без доставки' }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>
                                        <span class="tag is-{{ $order->status->class }}">
                                            {{ $order->status->name }}
                                        </span>
                                    </td>
                                    <td>{{ $order->paid ? 'Оплачен' : 'Не оплачен' }}</td>
                                    <td>
                                        <a href="{{ route('order.show', ['id' => $order->id]) }}">Перейти</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
