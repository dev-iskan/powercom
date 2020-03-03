@extends('layout.index')

@section('meta')
    <meta name="description" content="Powercom.uz - Регитрация">
    <meta name="og:title" property="og:title" content="Powercom.uz - Регитрация">
    <meta name="twitter:card" content="Powercom.uz - Регитрация">
@endsection

@section('body')
    <section class="section ">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-half-desktop">
                    <div class="card p-40 is-shadowless border">
                        <div class="card-header is-shadowless">
                            <p class="card-header-title is-size-4 pl-25">
                                Регистрация
                            </p>
                        </div>
                        <div class="card-content">
                            {{dump($errors)}}
                            <form action="{{route('register')}}" method="POST">
                                @csrf
                                <div class="field">
                                    <label class="label">Имя</label>
                                    <div class="control">
                                        <input class="input" name="name" type="text" placeholder="Введите имя"
                                               value="{{old('name')}}">
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">Фамилия</label>
                                    <div class="control">
                                        <input class="input" name="surname" type="text" placeholder="Введите фамилию"
                                               value="{{old('surname')}}">
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">Отчество</label>
                                    <div class="control">
                                        <input class="input" name="patronymic" type="text"
                                               placeholder="Введите отчество" value="{{old('patronymic')}}">
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">Номер телефона</label>
                                    <div class="control">
                                        <input class="input" name="phone" type="tel"
                                               placeholder="Введите номер телефона" value="{{old('phone')}}">
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">Пароль</label>
                                    <div class="control">
                                        <input class="input" name="password" type="password"
                                               placeholder="Введите новый пароль" value="{{old('password')}}">
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">Подтвердите пароль</label>
                                    <div class="control">
                                        <input class="input" name="password_confirmation" type="password"
                                               placeholder="Введите пароль еще раз">
                                    </div>
                                </div>

                                <div class="pt-40">
                                    <button type="submit" class="button is-fullwidth is-medium is-primary">
                                        Зарегистрироваться
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
