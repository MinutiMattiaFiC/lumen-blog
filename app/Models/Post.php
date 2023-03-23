<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;


    /**
     * @var array
     */
    protected $fillable = [
        'text',
        'title',
        'content',
    ];

    /**
     * @var array
     */
    protected $guarded = [
        'id',
        'user_id',
    ];

    /**
     * @var array
     */
    protected $hidden = [

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    Public function Comment(){
        return $this -> hasMany(Comment::class);
    }

    /**
     * @var string
     */
    protected $table = 'Post';

}
