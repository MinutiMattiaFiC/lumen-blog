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

    /**
     * @var string
     */
    protected $table = 'user';

}
