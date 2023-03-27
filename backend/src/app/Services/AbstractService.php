<?php

namespace App\Services;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
abstract class AbstractService
{

    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->model = Container::getInstance()->make($this->getModelClass());
    }

    /**
     * @param array $data
     * @param array $findBy
     * @return Model|null
     */
    public function createOrUpdate(array $data, array $findBy = [])
    {
        if(!$findBy) return null;
        $model = $this->model->where($findBy)->first();
        if($model) return $model;

        $this->model->fill($data);
        $this->model->save();

        return $this->model;
    }


    /**
     * @return string
     */
    abstract protected function getModelClass(): string;
}