<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SkillService
 *
 * @package App\Services
 */
class SkillService extends AbstractService
{

    /**
     * @param Collection $collection
     * @return Collection
     */
    public function getCollectionForResponse(Collection $collection): Collection
    {
        return $collection->map(function ($model) {
            return $this->getModelForResponse($model);
        });
    }

    /**
     * @param Model|null $model
     * @return Model
     */
    protected function getModelForResponse(Model $model = null): Model
    {
        if (!$model) return new $this->model;

        return $model->setVisible([
            'id',
            'title',
        ]);
    }

    /**
     * @return Collection
     */
    public function getForResponse(): Collection
    {
        return $this->model->all()->map(function ($model) {
            return $this->getModelForResponse($model);
        });
    }

    /**
     * @param array $data
     * @param array $findBy
     * @return Model|null
     */
    public function createOrUpdate(array $data, array $findBy = []): Model|null
    {
        if (!$findBy) return null;

        $model = $this->model->where($findBy)->first();
        if ($model) return $model;

        $model = (new $this->model)->fill($data);
        $model->save();

        return $model;
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Skill::class;
    }
}