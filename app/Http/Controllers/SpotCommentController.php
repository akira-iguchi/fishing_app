<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpotComment;
use App\Models\Spot;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpotCommentController extends Controller
{
    public function store(Request $request, $id) {
        $form = $request->all();

        // バリデーション
        $rules = [
            'comment' => 'required|max:150',
            'comment_image' => 'nullable|image'
        ];

        $comment = [
            'comment.required'=> 'コメントを入力してください'
        ];

        $validator = Validator::make($form, $rules, $comment);

        if($validator->fails()){
            session()->flash('error_message', '入力に不備があります');
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $spot = Spot::findOrFail($id);
            $spot_comment = new SpotComment;
            $spot_comment->spot_id = $spot->id;
            $spot_comment->user_id = auth()->id();
            if ($request->hasFile('comment_image')) {
                $filePath = $request->file('comment_image')->store('public');
                $spot_comment->comment_image = basename($filePath);
                // $upload_info = Storage::disk('s3')->putFile('/test', $request->file('image'), 'public');

                //S3へのファイルアップロード処理の時の情報が格納された変数$upload_infoを用いてアップロードされた画像へのリンクURLを変数$pathに格納する
                // $path = Storage::disk('s3')->url($upload_info);
                // $spot->image = $path;
            }
            $spot_comment->comment = $request->comment;
            $spot_comment->save();
            session()->flash('flash_message', 'コメントしました');
            return redirect()->route('spots.show', [$spot]);
        }
    }

    public function destroy($id, $comment)
    {
        // idの値で投稿を検索して取得
        $comment = SpotComment::findOrFail($comment);

        $spot = Spot::findOrFail($id);

        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $comment->user_id) {
            $comment->delete();
            session()->flash('flash_message', 'コメントを削除しました');
            return redirect()->route('spots.show', [$spot]);
        } else {
            session()->flash('flash_message', 'コメントを削除できませんでした');
            return redirect()->route('spots.show', [$spot]);
        }
    }
}
