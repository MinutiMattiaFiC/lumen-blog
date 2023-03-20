<?php

namespace App\Http\Controllers;



use Couchbase\User;
use Illuminate\Http\JsonResponse;
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

    public function showComment(Request $request){
        $postId = $request->input('post_id');

        $comments = Comment::where('post_id', $postId)
            ->join('user', 'Comment.user_id', '=', 'user.id')
            ->select('Comment.id', 'Comment.content', 'user.email')
            ->get();

        return $comments;
    }
    public function delete($commentId) : JsonResponse
    {
        $user = Auth::user();
        $comment = $user->Comment()->find($commentId);

        if (!$comment) {
            return response()->json(['error' => 'Comment not found or it s not yours'], 404);
        }

        $comment->delete();
        return response()->json([]);
    }


    public function create(Request $request) : JsonResponse
    {
        // Validazione dei dati di input
        $this->validate($request, [
            'content' => 'required|string',
            'post_id' => 'required|integer'
        ]);

        /** @var \App\Models\user $user */
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
    public function update(Request $request, $commentId)
    {
        /** @var Comment $comment */
        $comment = Comment::findOrFail($commentId);

        // controllo se possiedo il commento
        if ($comment->user_id != Auth::user()->id) {
            return response(['error' => 'You are not authorized to edit this comment'], 403);
        }

        $comment->content = $request->input('content');
        $comment->save();

        return $comment;
    }

}
