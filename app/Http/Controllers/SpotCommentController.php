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
    public function store(SpotCommentRequest $request, SpotComment $spot_comment)
    {
        \Log::info($request->all());
        $spot_comment->fill($request->except('comment_image'));
        $spot_comment->spot_id = $request->spot_id;
        $spot_comment->user_id = Auth::id();
        if ($request->hasFile('comment_image')) {
            $upload_info = Storage::disk('s3')
                ->putFile('/comment', $request->file('comment_image'), 'public');
            $path = Storage::disk('s3')->url($upload_info);
            $spot_comment->comment_image = $path;
        }
        $spot_comment->save();

        $new_comment = SpotComment::where('id', $spot_comment->id)->with('user')->first();

        return response($new_comment, 201);
    }

    public function destroy($spot, $comment)
    {
        $comment = SpotComment::findOrFail($comment);

        if (\Auth::id() === $comment->user_id) {
            $comment->delete();
            return response()->json();
        } else {
            return redirect('/')->with('flash_message', '自信の投稿のみ削除できます');
        }
    }
}
