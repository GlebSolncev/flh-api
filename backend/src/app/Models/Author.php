<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Author extends Model
{
    /**
     * @var string
     */
    protected $table = 'authors';

    /**
     * @var string[]
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'link',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    public function projects(){
        return $this->hasMany(Project::class);
    }
}