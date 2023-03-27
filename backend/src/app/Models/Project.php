<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class Project extends Model
{
    /**
     * @var string
     */
    protected $table = 'projects';

    /**
     * @var string[]
     */
    protected $fillable = [
        'api_id',
        'title',
        'description',
        'amount',
        'currency',
    ];


    /**
     * @var bool
     */
    public $timestamps = false;


    public function skills(){
        return $this->belongsToMany(Skill::class, 'project_skill', 'project_id');
    }

    public function author(){
        return $this->belongsTo(Author::class);
    }
}