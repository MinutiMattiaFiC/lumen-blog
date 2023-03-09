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
        'text',
    ];

    /**
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'user_id',
        'post_id',
    ];

    /**
     * @var string
     */
    protected $table = 'comments';

    /**
     * @var array
     */
    protected $with = [
        'user',
    ];


}

