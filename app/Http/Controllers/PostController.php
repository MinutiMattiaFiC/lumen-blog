<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function selectAll(){
        $post = DB::table('Post');
        return view('post',['post'=>$post]);
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
            'user_id' => 'required|integer',
            'content' => 'required|string',
            'title' => 'required|string'
        ]);

        // Inserimento dei dati nel database
        $post = DB::table('Post')->insert([
            'user_id' => $request->input('user_id'),
            'content' => $request->input('content'),
            'title' => $request->input('title'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')

        ]);


        // Restituzione della risposta vuota
        return response()->json([]);
    }


    public function deletePost($id) {
        DB::table('Post')->where('id', $id)->delete();
        return response()->json([]);
    }

}
