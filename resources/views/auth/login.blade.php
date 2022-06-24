@extends('layouts.app')

@section('content')
    @component('components.full-page-section')
        @component('components.card')
            @slot('title')
                Авторизация
            @endslot

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label class="label" for="login">Ваш логин</label>
                    <div class="control">
                        <input id="login" type="text" class="input @error('login') is-danger @enderror" name="login" value="{{ old('login') }}" required autocomplete="login" autofocus>
                    </div>
                    @error('login')
                    <p class="help is-danger" role="alert">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="password">Ваш пароль</label>
                    <div class="control">
                        <input id="password" type="password" class="input @error('password') is-danger @enderror" name="password" required autocomplete="current-password">
                    </div>
                    @error('password')
                    <p class="help is-danger" role="alert">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="field">
                    <div class="control">
                        <label class="checkbox">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            Запомнить меня
                        </label>
                    </div>
                </div>

                <hr>

                <div class="field is-grouped">
                    <div class="control">
                        <button type="submit" class="button is-primary">
                            Войти
                        </button>
                    </div>

                        <div class="is-align-self-center">
                            С любовью <i class="fa fa-heart text-color--red"></i> <a href="https://t.me/mapleart">Малышкин Алексей</a>
                        </div>

                </div>
            </form>
        @endcomponent
    @endcomponent
@endsection
