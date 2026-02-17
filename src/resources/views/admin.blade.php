@extends('layouts.app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection


@section('nav')
<form class="header-nav__link" method="post" action="{{route('logout') }}">
    @csrf
    <input class="header__link" type="submit" value="logout">
    {{--ログアウトボタンを押すと、logoutルートにPOSTリクエストが送信される--}}
    {{--ログアウト処理は、Laravelによって行われる--}}
</form>
@endsection


@section('content')
<div class="admin">
    <h2 class="admin__heading content__heading">
    {{-- クラス名を複数指定することで、他のページと共通の記述ができる --}}
        Admin
    </h2>
    <div class="admin__inner">
        {{--検索フォーム--}}
        <form class="search-form" action="{{ route('admin.search') }}" method="get">
            @csrf
            <input class="search-form__keyword-input" type="text" name="keyword" value="{{ request('keyword') }}" placeholder="名前やメールアドレスを入力してください">
                {{--value="{{ request('keyword') }}"：検索フォームに入力された値を保持する--}}
            <div class="search-form__gender">
                <select class="search-form__gender-select" name="gender" value="{{ request('gender') }}">
                    <option value="" selected disabled>性別</option>
                    {{--option value=""は空にする--}}
                    {{--selected：デフォルトで選択--}}
                    {{--disabled：これを選んだまま送信できなくする--}}
                    <option value="">全て</option>
                    <option value="1">男性</option>
                    <option value="2">女性</option>
                    <option value="3">その他</option>
                </select>
            </div>
            <div class="search-form__category">
                <select class="search-form__category-select" name="category_id" value="{{ request('category_id') }}">
                    <option value="" selected disabled>お問い合わせの種類</option>
                    {{--option value=""は空にする--}}
                    {{--selected：デフォルトで選択--}}
                    {{--disabled：これを選んだまま送信できなくする--}}
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->content }}</option>
                        {{-- シーダーに登録した情報(category)を呼び出して選択肢を作成している --}}
                    @endforeach
                </select>
            </div>
            <input class="search-form__date" type="date" name="created_at" value="{{ request('created_at') }}">
            <div class="search-form__actions">
                <input class="search-form__search-btn btn" type="submit" value="検索">
                <input class="search-form__reset-btn btn" type="submit" value="リセット" name="reset">
                {{-- リセットボタン（コントローラにも記述必要）--}}
            </div>
        </form>

        {{--エクスポートボタン--}}
        <div class="export-form">
            <form action="{{ route('admin.export') }}" method="post">
                @csrf
                {{-- 現在のURLパラメータ（検索条件）をhiddenで隠し持っておく --}}
                {{-- request()->query() クエリパラメータ（URLの検索条件）を取得。AdminControllerのsearchメソッドにて定義。 --}}
                @foreach(request()->query() as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <input class="export__btn btn" type="submit" value="エクスポート">
            </form>

        {{--ページネーション--}}
            {{ $contacts->appends(request()->query())->links('vendor.pagination.custom') }}
            {{-- appends(request()->query())
            ページネーションで検索条件を維持するために使われる。検索フォームと連動していれば、検索条件を保持したままページングできる。--}}
            <style>
                svg.w-5.h-5{
                    width: 30px;
                    height: 30px;
                }
            </style>
        </div>

    {{--一覧--}}
        <table class="admin__table">
            <tr class="admin__row">
                <th class="admin__label">お名前</th>
                <th class="admin__label">性別</th>
                <th class="admin__label">メールアドレス</th>
                <th class="admin__label">お問い合わせの種類</th>
                <th class="admin__label"></th>
            </tr>
            @foreach ($contacts as $contact)
            <tr class="admin__row">
                <td class="admin__data">
                    {{ $contact->last_name }} {{ $contact->first_name }}
                </td>
                <td class="admin__data">
                    @if ($contact->gender == 1)
                        男性
                    @elseif ($contact->gender == 2)
                        女性
                    @else
                        その他
                    @endif
                </td>
                <td class="admin__data">
                    {{ $contact->email }}
                </td>
                <td class="admin__data">
                    {{$contact->category->content}}
                </td>
                <td class="admin__data">
                    {{-- 詳細ボタンを押すと、モーダルが表示される --}}
                    <a class="admin__detail-btn" href="#{{$contact->id}}">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>

        @foreach($contacts as $contact)
        {{-- モーダル本体 --}}
        <div class="modal" id="{{$contact->id}}">
            <a href="#!" class="modal-overlay"></a>
            {{-- モーダル外の黒背景部分（クリックで閉じられる） --}}
            <div class="modal__inner">
                {{-- モーダルの中身 --}}
                <div class="modal__content">
                    <form class="modal__detail-form" action="{{ route('admin.destroy', $contact->id)}}" method="post">
                    {{-- 詳細を表示。下部の削除ボタンを押すと削除できる仕組み --}}
                    @csrf
                        <div class="modal-form__group">
                            <label class="modal-form__label" for="">お名前</label>
                            <p>{{$contact->last_name}}{{$contact->first_name}}</p>
                        </div>

                        <div class="modal-form__group">
                            <label class="modal-form__label" for="">性別</label>
                            <p>
                                @if($contact->gender == 1)
                                男性
                                @elseif($contact->gender == 2)
                                女性
                                @else
                                その他
                                @endif
                            </p>
                        </div>

                        <div class="modal-form__group">
                            <label class="modal-form__label" for="">メールアドレス</label>
                            <p>{{$contact->email}}</p>
                        </div>

                        <div class="modal-form__group">
                            <label class="modal-form__label" for="">電話番号</label>
                            <p>{{$contact->tel}}</p>
                        </div>

                        <div class="modal-form__group">
                            <label class="modal-form__label" for="">住所</label>
                            <p>{{$contact->address}}</p>
                        </div>

                        <div class="modal-form__group">
                            <label class="modal-form__label" for="">お問い合わせの種類</label>
                            <p>{{$contact->category->content}}</p>
                        </div>

                        <div class="modal-form__group">
                            <label class="modal-form__label" for="">お問い合わせ内容</label>
                            <p>{{$contact->detail}}</p>
                        </div>

                        <input class="modal-form__delete-btn btn" type="submit" value="削除">
                    </form>
                </div>
                <a href="#" class="modal__close-btn">×</a>
                {{-- href="#"は、クリック後にページのトップに遷移させるための処理。ページ固定したい場合はJavascriptで定義する --}}
            </div>
        </div>
        @endforeach
    </div>
</div>

</div>
@endsection