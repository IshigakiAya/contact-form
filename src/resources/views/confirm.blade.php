@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/confirm.css') }}">
@endsection

@section('content')
<div class="confirm__content">
    <h2 class="confirm__header">
        Confirm
    </h2>
    <form class="form" action="/thanks" method="post">
        @csrf
        <div class="confirm-table">
            <table class="confirm-table__inner">
                <tr class="confirm-table__row">
                    <th class="confirm-table__header">お名前</th>
                    <td class="confirm-table__text">
                        <input type="text" name="name" value="{{ $contact->first_name }} {{ $contact->last_name}}" readonly />
                    </td>
                </tr>
                <tr class="confirm-table__row">
                    <th class="confirm-table__header">性別</th>
                    <td class="confirm-table__text">
                        <input type="hidden" name="gender" value="{{ $contact->gender }}" readonly />
                        @if ($contact->gender == 1)
                            男性
                        @elseif ($contact->gender == 2)
                            女性
                        @else
                            その他
                        @endif
                    </td>
                </tr>
                <tr class="confirm-table__row">
                    <th class="confirm-table__header">メールアドレス</th>
                    <td class="confirm-table__text">
                        <input type="email" name="email" value="{{ $contact->email }}" readonly />
                    </td>
                </tr>
                <tr class="confirm-table__row">
                    <th class="confirm-table__header">電話番号</th>
                    <td class="confirm-table__text">
                        <input type="tel" name="tel" value="{{ $contact->tel }}" readonly />
                    </td>
                </tr>
                <tr class="confirm-table__row">
                    <th class="confirm-table__header">住所</th>
                    <td class="confirm-table__text">
                        <input type="text" name="address" value="{{ $contact->address }}" readonly />
                    </td>
                </tr>
                <tr class="confirm-table__row">
                    <th class="confirm-table__header">建物名</th>
                    <td class="confirm-table__text">
                        <input type="text" name="building" value="{{ $contact->building }}" readonly />
                    </td>
                </tr>
                <tr class="confirm-table__row">
                    <th class="confirm-table__header">お問い合わせの種類</th>
                    <td class="confirm-table__text">
                        <input type="hidden" name="category_id" value="{{ $contact->category_id }}" readonly />
                        @if ($contact->category_id == 1)
                        商品のお届けについて
                        @elseif ($contact->category_id == 2)
                        商品の交換について
                        @elseif ($contact->category_id == 3)
                        商品トラブル
                        @elseif ($contact->category_id == 4)
                        ショップへのお問い合わせ
                        @else
                            その他
                        @endif
                    </td>
                </tr>
                <tr class="confirm-table__row">
                    <th class="confirm-table__header">お問い合わせ内容</th>
                    <td class="confirm-table__text">
                        {{-- 1. 表示用（CSSで改行を有効にする） --}}
                        <div style="white-space: pre-wrap;">{{ $contact->detail }}</div>
                        {{-- 2. データ送信用（hiddenで裏側で保持） --}}
                        <input type="hidden" name="detail" value="{{ $contact->detail }}" readonly />
                    </td>
                </tr>
            </table>
        </div>
        <div class="confirm-table__button">
            <button class="confirm-table__button-submit" type="submit">送信</button>
            <a href="{{ route('contact.index')}}" class="confirm-table__link-modify">修正</a>
            {{-- <input class="confirm-table__link-modify" onclick="history.back()" value="修正"> --}}
            {{--history.back()は、ブラウザの履歴を1つ戻る機能を持つメソッド--}}
        </div>
    </form>
@endsection