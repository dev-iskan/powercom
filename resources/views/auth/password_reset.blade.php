@extends('layout.index')

@section('meta')
    <meta name="description" content="Powercom.uz - Вход">
    <meta name="og:title" property="og:title" content="Powercom.uz - Вход">
    <meta name="twitter:card" content="Powercom.uz - Вход">
@endsection

@section('body')
    <section class="section ">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-one-third-desktop">
                    <div class="card p-20 is-shadowless border">
                        <figure class="has-text-centered mt-20">
                            <img src="{{ URL::asset('/image/logo.svg') }}" style="width: 160px;" alt="Powercom.uz">
                        </figure>
                        <div class="card-content">
                            <form action="{{route('form_password_reset')}}" method="POST">
                                @csrf
                                <div class="field">
                                    <label class="label">Код</label>
                                    <div class="control has-icons-left">
                                        <input required class="input" name="code"
                                               placeholder="Введите код">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-sms"></i>
                                        </span>
                                    </div>
                                    @if($errors->has('code'))
                                        <p class="help is-danger">{{ $errors->first('code') }}</p>
                                    @endif
                                </div>
                                <div class="field">
                                    <label class="label">Пароль</label>
                                    <div class="control has-icons-left">
                                        <input required class="input" name="password"
                                               type="password"
                                               placeholder="Введите новый пароль">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                    </div>
                                    @if($errors->has('password'))
                                        <p class="help is-danger">{{ $errors->first('password') }}</p>
                                    @endif
                                </div>
                                <input type="hidden" name="phone" value="{{session('password_reset')}}">

                                <div class="columns mt-20">
                                    <div class="column">
                                        <button type="submit" class="button is-fullwidth is-primary">Восстановить пароль
                                        </button>
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
