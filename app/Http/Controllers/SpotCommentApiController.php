<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use App\Models\SpotComment;
use App\Http\Requests\SpotCommentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpotCommentApiController extends Controller
{
    public function index(Spot $spot)
    {
        $comments = $spot->spot_comments()->with('user')->get()->sortByDesc('created_at');

        return [
            'comments' => $comments,
        ];
    }

    public function store(SpotCommentRequest $request, Spot $spot, SpotComment $spot_comment)
    {
        $spot_comment->fill($request->except('comment_image'));
        $spot_comment->spot_id = $spot->id;
        $spot_comment->user_id = 1;
        if ($request->hasFile('comment_image')) {
            $filePath = $request->file('comment_image')->store('public');
            $spot_comment->comment_image = basename($filePath);
            // $upload_info = Storage::disk('s3')->putFile('/test', $request->file('image'), 'public');

            //S3へのファイルアップロード処理の時の情報が格納された変数$upload_infoを用いてアップロードされた画像へのリンクURLを変数$pathに格納する
            // $path = Storage::disk('s3')->url($upload_info);
            // $spot->image = $path;
        }
        $spot_comment->save();
    }

    public function destroy($id, $comment)
    {
        $comment = SpotComment::findOrFail($comment);

        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $comment->user_id) {
            $comment->delete();
            return ['flash_message' => 'コメントを削除しました'];
        } else {
            return ['error_message' => 'コメントを削除できませんでした'];
        }
    }
}
