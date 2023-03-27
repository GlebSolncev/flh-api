<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Database\Eloquent\Model;

class AuthorService extends AbstractService
{

    public function getForResponse(Model $model = null){
        if(!$model) return null;

        return $model->setVisible([
            'id',
            'username',
            'first_name',
            'last_name',
            'link',
        ]);
    }

    protected function getModelClass(): string
    {
        return Author::class;
    }
}