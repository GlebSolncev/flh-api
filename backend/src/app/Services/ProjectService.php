<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Project;
use App\Services\ImportProjects\FreelancehuntImport;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
     * @param int $page
     * @return void
     */
    protected function setPageForPaginate(int $page): void
    {
        Paginator::currentPageResolver(function() use($page) {
            return $page;
        });
    }

    /**
     * @param int   $page
     * @param array $data
     * @return Model
     */
    public function getForResponse(int $page, array $data): LengthAwarePaginator
    {
        $this->setPageForPaginate($page);

        $skills = Arr::get($data, 'skill_ids', []);
        $result = $this->model->whereHas('skills', function($query) use($skills){
            if($skills)
                $query->whereIn('id', $skills);
        })->orderBy('published_at', 'desc')->paginate(25);

        $data = $result->getCollection();
        $data->map(function($model){
            $model->author = $this->authorService->getForResponse($model->author);
            $model->skills = $this->skillService->getCollectionForResponse($model->skills);

            return $model->setVisible([
                'title',
                'description',
                'amount',
                'published_at',
                'link',
                'currency',
                'author',
                'skills',
            ]);
        });
        $result->setCollection($data);

        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    public function getBudgets(array $data): array
    {
        $skills = Arr::get($data, 'skill_ids');

        $result = $this->model->whereHas('skills', function($query) use($skills){
            if($skills)
                $query->whereIn('id', $skills);
        })->selectRaw('amount, currency, count(*) as count')->groupBy(['amount', 'currency'])->get();

        return $this->getReformatBudgets($result);
    }

    /**
     * @param Collection $result
     * @return array
     */
    protected function getReformatBudgets(Collection $result): array
    {
        return [
            $result->filter(function($model){
                return $model->amount <= 500 and $model->amount != null;
            })->sum('count'),
            $result->filter(function($model){
                return $model->amount > 500 and $model->amount <= 1000;
            })->sum('count'),
            $result->filter(function($model){
                return $model->amount > 1000 and $model->amount <= 5000;
            })->sum('count'),
            $result->filter(function($model){
                return $model->amount > 5000;
            })->sum('count')
        ];
    }

    /**
     * @return true
     * @throws BindingResolutionException
     */
    public function import()
    {
        $import = new FreelancehuntImport();
        $import->importProjects();
    }

    /**
     * @param Collection $collect
     * @return void
     */
    public function saveCollect(Collection $collect): void
    {
        $collect->map(function($item){
            //AUTHOR
            $author = $this->syncAuthor($item);

            // PROJECT
            $project = $this->model->where('api_id', Arr::get($item, 'api_id'))->first();
            if($project){
                $project->fill($item)->save();
            }else{
                $project = (new $this->model)->fill($item);
                $author->projects()->save($project);
            }

            //SKILLS
            if($project)
                $this->syncSkills($project, $item);
        });
    }

    /**
     * @param array $item
     * @return Model
     * @throws \ErrorException
     */
    protected function syncAuthor(array $item): Model
    {
        $author = $this->authorService->createOrUpdate(
            Arr::get($item, 'author'), [
                ['username', '=', Arr::get($item, 'author.username')]
            ]
        );
        if(!$author) throw new \ErrorException('Finder doesnt work');

        return $author;
    }

    /**
     * @param Model $project
     * @param array $item
     * @return void
     */
    protected function syncSkills(Model $project, array $item): void
    {
        $skillIds = [];
        foreach(Arr::get($item, 'skills', []) as $skill){
            $skillIds[]  = $this->skillService->createOrUpdate($skill, [['api_id', '=', $skill['api_id']]])->id;
        }

        $project->skills()->sync($skillIds);
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Project::class;
    }
}