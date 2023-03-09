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

    public function delete($commentId)
    {
        DB::table('Comment')->where('id', $commentId)->delete();
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

