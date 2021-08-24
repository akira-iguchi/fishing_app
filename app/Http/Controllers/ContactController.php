<?php

namespace App\Http\Controllers;

use App\Mail\ContactSendMail;
use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function index()
    {
        return response()->json();
    }

    public function confirm(ContactRequest $request)
    {
        return $request->all();
    }

    public function confirmPage()
    {
        return response()->json();
    }

    public function send(ContactRequest $request)
    {
        $inputs = $request->all();

        //入力されたメールアドレスにメールを送信
        \Mail::to(config('mail.mailers.smtp.username'))->send(new ContactSendMail($inputs));

        //再送信を防ぐためにトークンを再発行
        $request->session()->regenerateToken();

        return response()->json();
    }

    public function thanks()
    {
        return response()->json();
    }
}
