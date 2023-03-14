<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{


    /**
     * @var array
     */
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'picture',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'full_name',
    ];

    public function Post()
    {
        return $this->hasMany(Post::class);
    }

    Public function Comment(){
        return $this -> hasMany(Comment::class);
    }

    /**
     * @var string
     */
    protected $table = 'user';

}
