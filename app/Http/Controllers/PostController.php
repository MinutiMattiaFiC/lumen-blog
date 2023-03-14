<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\user;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function showAll()
    {

        $posts = Post::select(
            'Post.id',
            'Post.title',
            'Post.content',
            'user.email',
            'user.first_name',
            'user.last_name',
            'user.picture',
            \DB::raw('COUNT(Comment.id) as Comment_count'),
            \DB::raw("CONCAT(user.first_name, ' ', user.last_name) as full_name")
        )
            ->join('user', 'Post.user_id', '=', 'user.id')
            ->leftJoin('Comment', 'Post.id', '=', 'Comment.post_id')
            ->groupBy('Post.id')
            ->orderByDesc('Post.created_at')
            ->get();

        return response()->json($posts);


    }


    public function show($id, Request $request)
    {
        $comments = null; //se nella richiesta non è presente il parametro comments non vera caricato nessun commento
        $post = Post::with('user')->findOrFail($id);

        if (!$post) {
            abort(404);
        }

        if($request->input('comments')){
            $commentCount = $request->input('comments');
            $comments = $post->Comment()
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->take($commentCount)
                ->get();
        }

        return response()->json([
            'post' => $post,
            'comments' => $comments,
        ]);
    }







    public function create(Request $request)
    {
        // Validazione dei dati di input
        $this->validate($request, [
            'content' => 'required|string',
            'title' => 'required|string'
        ]);

        // Ottenere l'utente loggato
        $user = Auth::user();

        // Creazione del post associato all'utente loggato
        $post = new Post([
            'content' => $request->input('content'),
            'title' => $request->input('title')
        ]);
        $user->Post()->save($post);

        // Restituzione della risposta vuota
        return response()->json([]);
    }

    public function deletePost($id)
    {
        // Recupera l'utente autenticato
        $user = Auth::user();

        // Verifica che il post appartenga all'utente
        $post = DB::table('Post')->where('id', $id)->first();
        if (!$post || $post->user_id !== $user->id) {
            return response()->json(['error' => 'Post not found or does not belong to user'], 404);
        }

        // Cancella il post
        DB::table('Post')->where('id', $id)->delete();
        return response()->json([]);
    }


}
