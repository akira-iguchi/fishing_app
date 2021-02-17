<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use Validator;

class SpotsController extends Controller
{

    public function index()
    {
        $spots = Spot::all();
        return view('spots.index', ['spots' => $spots]);
    }

    public function show($id)
    {
        // idの値でメッセージを検索して取得
        $spot = Spot::findOrFail($id);

        // メッセージ詳細ビューでそれを表示
        return view('spots.show', [
            'spot' => $spot,
        ]);
    }

    public function create()
    {

        $spot = new Spot;

        // メッセージ作成ビューを表示
        return view('spots.create', [
            'spot' => $spot,
        ]);
    }

    public function store(Request $request) {
        $form = $request->all();

        // バリデーション
        $rules = [
            'name' => 'required|max:20',
            'explanation' => 'required|max:300',
            'address' => 'max:50',
            'latitude' => 'required',
            'longitude' => 'required',
            'image' => 'nullable|image'
        ];

        $message = [
            'name.required'=> '釣り場名を入力してください',
            'explanation.required'=> '説明を入力してください',
        ];

        $validator = Validator::make($form, $rules, $message);

        if($validator->fails()){
            return redirect('/spots/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            $spot = new Spot;
            $spot->name = $request->input('name');
            $spot->explanation = $request->input('explanation');
            $spot->address = $request->input('address');
            if ($request->hasFile('image')) {
                $filePath = $request->file('image')->store('public');
                $spot->image = basename($filePath);
            }
            $spot->latitude = $request->input('latitude');
            $spot->longitude = $request->input('longitude');
            $spot->user_id = auth()->id();
            $spot->save();
            return redirect('/spots');
        }
    }

}
