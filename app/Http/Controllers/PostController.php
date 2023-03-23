<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

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
        /** @var Collection $posts */
        return $posts;
    }

    public function loadComment($id,Request $request) {
        $lastCommentId = $request->query('last_comment_id');
        $numComments = $request->query('comments');

        $query = Comment::where('post_id', $id)
            ->orderBy('id', 'desc');

        if ($lastCommentId) {
            $query = $query->where('id', '<', $lastCommentId);
        }

        if ($numComments) {
            $query = $query->limit($numComments);
        }

        $comments = $query->get();

        return $comments;
    }

    public function show($id, Request $request)
    {
        $post = Post::with('user')->findOrFail($id);

        if (!$post) {
            abort(404);
        }

        if ($request->input('comments')) {
            $commentCount = $request->input('comments');
            $post->load(['Comment'=>function($query) use ($commentCount){
                $query->orderBy('id', 'desc')
                    ->take($commentCount);
            }]);


        }

        return $post;
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


    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $user = Auth::user();

        // controllo se l'utente possiede il post
        if ($post->user_id != $user->id) {
            return response()->json(['error' => 'You do not have permission to edit this post'], 403);
        }

        // Vvalidazione dati
        $this->validate($request, [
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        // Update
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();

        // ritorno il post modificato con le info del creatore
        $post = Post::with('user')->findOrFail($id);
        return $post;
    }


}
