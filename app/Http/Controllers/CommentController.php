<?php

namespace App\Http\Controllers;



use Illuminate\Support\Facades\DB;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Lumen\Routing\Controller as BaseController;
class CommentController extends Controller
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
        // Validazione dei dati di input
        $this->validate($request, [
            'content' => 'required|string',
            'post_id' => 'required|integer'
        ]);

        // Ottenere l'utente loggato
        $user = Auth::user();

        // Creazione del commento associato all'utente loggato e al post indicato
        $comment = new Comment([
            'content' => $request->input('content'),
            'post_id' => $request->input('post_id')
        ]);
        $user->Comment()->save($comment);

        // Restituzione della risposta vuota
        return response()->json([]);

    }

}
