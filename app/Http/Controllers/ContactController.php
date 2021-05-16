<?php

namespace App\Http\Controllers;

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

    public function confirmPage(Request $request)
    {
        return response()->json();
    }

    public function send(Request $request)
    {
    }
}
