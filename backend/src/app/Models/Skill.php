<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Skill extends Model
{
    /**
     * @var string
     */
    protected $table = 'skills';

    /**
     * @var string[]
     */
    protected $fillable = [
        'api_id',
        'title',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    public function projects(){
        return $this->belongsToMany(Project::class, 'project_skill', 'skill_id');
    }
}