<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function confirm(ContactRequest $request)
    {
        $contact = $request->only(['name', 'email','tel','content']);

        return view('confirm', compact('contact'));
        //return view('confirm', ['contact' => $contact]);と同じ//
    }

    public function store(ContactRequest $request)
    {
        $contact = $request->only(['name','email','tel','content']);
        Contact::create($contact);
        return view('thanks');
        //完了画面ではフォーム入力データを表示しないので「compact('contact')」の記述は不要//
    }
}
