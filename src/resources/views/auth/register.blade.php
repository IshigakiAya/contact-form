@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('nav')
    <nav>
        <ul class="header__nav">
            <li class="header-nav__item">
                <a class="header-nav__link" href="/login">login</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="admin-form__content">
    <div class="admin-form__header">
        <h2>Register</h2>
    </div>
        <form class="form" action="/register" method="post">
        @csrf
        <div class="form__wrapper">
            <div class="form__group">
                <div class="form-group__label">お名前</div>
                <div class="form-group__input">
                    <input type="text" name="name" class="form__input" placeholder="例:山田  太郎">
                </div>
                <div class="form__error">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form-group__label">メールアドレス</div>
                <div class="form-group__input">
                    <input type="email" name="email" class="form__input" placeholder="例:test@example.com">
                </div>
                <div class="form__error">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form-group__label">パスワード</div>
                <div class="form-group__input">
                    <input type="password" name="password" class="form__input" placeholder="例:coachtech1106">
                </div>
                <div class="form__error">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">登録</button>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection