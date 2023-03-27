<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SkillService extends AbstractService
{

    public function getCollectionForResponse(Collection $collection){
        return $collection->map(function($model){
            return $this->getModelForResponse($model);
        });
    }

    protected function getModelForResponse(Model $model = null){
        if(!$model) return null;

        return $model->setVisible([
            'id',
            'title',
        ]);
    }

    public function getForResponse(){
        return $this->model->all()->map(function($model){
            return $this->getModelForResponse($model);
        })->toJson(1);
    }
    public function createOrUpdate(array $data, array $findBy = [])
    {
        if(!$findBy) return null;

        $model = $this->model->where($findBy)->first();
        if($model) return $model;

        $model = (new $this->model)->fill($data);
        $model->save();

        return $model;
    }
    protected function getModelClass(): string
    {
        return Skill::class;
    }
}