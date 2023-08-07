<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;


class CommentController extends Controller
{
    //
    public function store(Request $request, $gameId)
    {
        $content = $request->validate([
            'content' => 'required', 
            'like_type' => 'nullable|in:1,2',
        ]);

        $comment = new Comment([
            'content' => $content['content'],
            'like_type' => $content['like_type'],
            'game_id' => $gameId,
            'user_id' => auth()->user()->id
        ]);

        $comment->save();

        return redirect()->route('games.show', ['game' => $gameId])->with('notice', '留言成功！');
    }

    public function destroy($gameId, $commentId){
    $comment = Comment::findOrFail($commentId);
    // 其他逻辑处理
    $comment->delete();
    return redirect()->route('games.show', ['game' => $gameId])->with('notice', '留言删除成功！');
    }
}
