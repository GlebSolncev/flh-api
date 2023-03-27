<?php

namespace App\Services;

use App\Models\Project;
use App\Services\ImportProjects\FreelancehuntImport;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 *
 */
class ProjectService extends AbstractService
{
    /**
     * @param AuthorService $authorService
     * @param SkillService $skillService
     * @throws BindingResolutionException
     */
    public function __construct(protected AuthorService $authorService, protected SkillService $skillService)
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getForResponse()
    {
        $result = $this->model->where([['is_active', '=', true]])->with(['skills', 'author'])->paginate(10)->map(function($model){
            $model->author = $this->authorService->getForResponse($model->author);
            $model->skills = $this->skillService->getCollectionForResponse($model->skills);

            return $model->setVisible([
                'title',
                'description',
                'amount',
                'currency',
                'author',
                'skills',
            ]);
        });

        return $result->toJson(1);
    }

    /**
     * @return true
     * @throws BindingResolutionException
     */
    public function import()
    {
        $import = new FreelancehuntImport();
        $import->importProjects();
//        $this->saveCollect($responseData);
    }

    /**
     * @param Collection $collect
     * @return true
     * @throws BindingResolutionException
     */
    public function saveCollect(Collection $collect){

        /** @var AuthorService $authorService */
        $authorService = Container::getInstance()->make(AuthorService::class);
        /** @var SkillService $skillService */
        $skillService = Container::getInstance()->make(SkillService::class);

        $collect->map(function($item) use($authorService, $skillService){

            //AUTHOR
            $author = $authorService->createOrUpdate(Arr::get($item, 'author'), [['username', '=', Arr::get($item, 'author.username')]]);
            if(!$author) throw new \ErrorException('Finder doesnt work');


            // PROJECT
            $project = $this->model->where('api_id', Arr::get($item, 'api_id'))->first();
            if($project){
                $project->fill($item)->save();
            }

            $project = (new $this->model)->fill($item);
            $author->projects()->save($project);

            //SKILS
            $skillIds = [];
            foreach(Arr::get($item, 'skills', []) as $skill){
                $skillIds[]  = $skillService->createOrUpdate($skill, [['api_id', '=', $skill['api_id']]])->id;
            }
            $project->skills()->sync($skillIds);

        });

        return true;
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Project::class;
    }
}