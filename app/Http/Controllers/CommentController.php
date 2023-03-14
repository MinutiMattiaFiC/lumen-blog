<?php

namespace App\Http\Controllers;



use Illuminate\Support\Facades\DB;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Lumen\Routing\Controller as BaseController;
class CommentController
{
    public function show($commentId)
    {
        $comment = DB::table('Comment')->where('id', $commentId)->first();
        return view('comment', ['comment' => $comment]);
    }

    public function showComment($request){
        $postId = $request->input('post_id');

        $comments = DB::table('Comment')
            ->join('user', 'Comment.user_id', '=', 'user.id')
            ->select('Comment.id', 'Comment.content', 'user.first_name', 'user.email')
            ->where('Comment.post_id', '=', $postId)
            ->get();

        return response()->json($comments);
    }
    public function delete($commentId)
    {
        $user = Auth::user();
        $comment = $user->Comment()->find($commentId);

        if (!$comment) {
            return response()->json(['error' => 'Comment not found or it s not yours'], 404);
        }

        $comment->delete();
        return response()->json([]);
    }


    public function create(Request $request)
    {

        // Inserimento dei dati nel database
        $comment = DB::table('Comment')->insert([
            'user_id' => $request->input('user_id'),
            'content' => $request->input('content'),
            'post_id' => $request->input('post_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')

        ]);


        // Restituzione della risposta vuota
        return response()->json([]);
    }

}
