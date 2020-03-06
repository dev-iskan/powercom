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
                    <div class="card p-20 is-shadowless border">
                        <div class="card-header is-shadowless">
                            <p class="card-header-title is-size-5 pl-25">
                                Регистрация
                            </p>
                        </div>
                        <div class="card-content">
                            <form action="{{route('register')}}" method="POST">
                                @csrf
                                <div class="field">
                                    <label class="label">Имя</label>
                                    <div class="control">
                                        <input required class="input" name="name" type="text" placeholder="Введите имя"
                                               value="{{old('name')}}">
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">Фамилия</label>
                                    <div class="control">
                                        <input required class="input" name="surname" type="text" placeholder="Введите фамилию"
                                               value="{{old('surname')}}">
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">Отчество</label>
                                    <div class="control">
                                        <input required class="input" name="patronymic" type="text"
                                               placeholder="Введите отчество" value="{{old('patronymic')}}">
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">Номер телефона</label>
                                    <div class="control has-icons-left">
                                        <input required class="input" name="phone" value="{{old('phone')}}" type="tel"
                                        placeholder="Введите номер телефона" minlength="12">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                    </div>
                                    @if($errors->has('phone'))
                                        <p class="help is-danger">{{ $errors->first('phone') }}</p>
                                    @endif
                                </div>

                                <div class="field">
                                    <label class="label">Пароль</label>
                                    <div class="control has-icons-left">
                                        <input required class="input" name="password" type="password"
                                        placeholder="Введите новый пароль" minlength="8">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                    </div>
                                    @if($errors->has('password'))
                                        <p class="help is-danger">{{ $errors->first('password') }}</p>
                                    @endif
                                </div>

                                <div class="field">
                                    <label class="label">Пароль</label>
                                    <div class="control has-icons-left">
                                        <input required class="input" name="password_confirmation" type="password"
                                        placeholder="Введите пароль еще раз" minlength="8">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                    </div>
                                    @if($errors->has('password'))
                                        <p class="help is-danger">{{ $errors->first('password') }}</p>
                                    @endif
                                </div>

                                <div class="columns mt-20">
                                    <div class="column">
                                        <button type="submit" class="button is-fullwidth is-primary">Зарегистрироваться
                                        </button>
                                    </div>
                                    <div class="column">
                                        <a href="{{ route('login') }}" class="button is-fullwidth is-outlined">Войти
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
