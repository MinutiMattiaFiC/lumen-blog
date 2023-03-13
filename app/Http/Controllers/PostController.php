<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\user;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function selectAll()
    {

        $posts = DB::table('Post')
            ->join('user', 'Post.user_id', '=', 'user.id')
            ->leftJoin('Comment', 'Post.id', '=', 'Comment.post_id')
            ->select(
                'Post.id',
                'Post.title',
                'Post.content',
                'user.email',
                'user.first_name',
                'user.last_name',
                DB::raw('COUNT(Comment.id) as Comment_count'),
                DB::raw("CONCAT(user.first_name, ' ', user.last_name) as full_name"),
                'user.picture'
            )
            ->groupBy('Post.id')
            ->orderByDesc('Post.created_at')
            ->get();

        return response()->json($posts);

    }

    public function show($id)
    {
        $post = DB::table('Post')->where('id', $id)->first();
        return view('post', ['post' => $post]);
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

        // Inserimento dei dati nel database
        $post = DB::table('Post')->insert([
            'user_id' => $user->id,
            'content' => $request->input('content'),
            'title' => $request->input('title'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')

        ]);

        // Restituzione della risposta vuota
        return response()->json([]);
    }


   public function deletePost($id)
    {
        // Recupera l'utente autenticato
        $user = Auth::guard('api')->user();

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
