@extends('layout.index')

@section('meta')
    <meta name="description" content="Powercom.uz - Вход">
    <meta name="og:title" property="og:title" content="Powercom.uz - Вход">
    <meta name="twitter:card" content="Powercom.uz - Вход">
@endsection

@section('body')
    <section class="section is-medium has-background-light">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-half-desktop">
                    <div class="card p-40">
                        <div class="card-header is-shadowless">
                            <p class="card-header-title is-size-4 pl-25">
                                Проверка номера телефона
                            </p>
                        </div>
                        <div class="card-content">
                            <form action="{{route('verify')}}" method="POST">
                                @csrf
                                <div class="field">
                                    <label class="label">Код подтверждения</label>
                                    <div class="control">
                                        <input class="input" name="code" type="number"
                                               placeholder="Введите код подтверждения">
                                    </div>
                                </div>

                                <div class="pt-40">
                                    <button type="submit" class="button is-fullwidth is-medium is-primary">Подтвердить
                                    </button>
                                    <a href="{{route('send_code')}}" class="button is-fullwidth is-text mt-5">Повторно
                                        отправить код еще раз</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
