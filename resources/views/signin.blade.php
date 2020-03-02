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
                            Войти в Powercom
                        </p>
                    </div>
                    <div class="card-content">
                        <form action="">
                              <div class="field">
                                <label class="label">Номер телефона</label>
                                <div class="control">
                                  <input class="input" type="tel" placeholder="Введите номер телефона">
                                </div>
                              </div>
                              
                              <div class="field">
                                <label class="label">Пароль</label>
                                <div class="control">
                                  <input class="input" type="password" placeholder="Введите пароль">
                                </div>
                              </div>

                              <div class="pt-40">
                                <button class="button is-fullwidth is-medium is-primary">Войти</button>
                              </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection