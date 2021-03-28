<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use App\Models\SpotComment;
use App\Http\Requests\SpotCommentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpotCommentController extends Controller
{
    public function index(Spot $spot)
    {
        return $spot->spot_comments()->with('user')->orderBy('id', 'desc')->get();
    }

    public function store(SpotCommentRequest $request, Spot $spot, SpotComment $spot_comment)
    {
        $spot_comment->fill($request->except('comment_image'));
        $spot_comment->spot_id = $spot->id;
        $spot_comment->user_id = Auth::id();
        if ($request->hasFile('comment_image')) {
            // $filePath = $request->file('comment_image')->store('public');
            // $spot_comment->comment_image = basename($filePath);
            $upload_info = Storage::disk('s3')->putFile('/comment', $request->file('comment_image'), 'public');
            $path = Storage::disk('s3')->url($upload_info);
            $spot_comment->comment_image = $path;
        }
        $spot_comment->save();
    }

    public function destroy($id, $comment)
    {
        $comment = SpotComment::findOrFail($comment);

        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $comment->user_id) {
            $comment->delete();
        }
    }
}
