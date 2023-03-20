<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'content',
        'post_id',
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
    public function User(){
        return $this->belongsTo(user::class);
    }
    public function Post(){
        return $this->belongsTo(Post::class);
    }

    /**
     * @var string
     */
    protected $table = 'Comment';




}

