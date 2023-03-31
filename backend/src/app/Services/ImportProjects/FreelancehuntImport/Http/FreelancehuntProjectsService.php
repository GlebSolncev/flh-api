<?php

namespace App\Services\ImportProjects\FreelancehuntImport\Http;


use App\Services\ImportProjects\Interfaces\ImportProjectsInterface;
use App\Services\ImportProjects\Interfaces\ImportResponseInterface;
use App\Services\ProjectService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 *
 */
class FreelancehuntProjectsService extends AbstractFreelancehuntServices implements ImportProjectsInterface
{

    /**
     * @var string
     */
    protected string $strLink = 'v2/projects?%s';

    /**
     * @return Collection
     * @throws \ErrorException
     * @throws GuzzleException
     */
    public function getResponse(): Collection
    {
        /** @var ProjectService $projectService */
        $projectService = Container::getInstance()->make(ProjectService::class);
        $query = '';
        $linkNext = '';
        $linkLast = null;

        $collect = Collection::make();
        while ($linkNext !== $linkLast) {
            $data = $this->getPage($query)->toArray();

            $linkNext = Arr::get($data, 'links.next');
            $linkLast = Arr::get($data, 'links.last');
            $query = Arr::get(parse_url($linkNext), 'query');

            $projectService->saveCollect($this->formattingData($data));
            dump($linkNext, $linkLast, $linkNext !== $linkLast);
            sleep(1);
        }

        return $collect;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function formattingData(array $data): Collection
    {
        $result = Collection::make();
        foreach (Arr::get($data, 'data') as $inx => $item) {
            $result->add($this->getProjectData($item));
        }

        return $result;
    }

    /**
     * @param array $item
     * @return array
     */
    protected function getSkillsData(array $item): array
    {
        $skills = [];
        foreach (Arr::get($item, 'attributes.skills', []) as $skill) {
            $skills[] = [
                'api_id' => Arr::get($skill, 'id'),
                'title' => Arr::get($skill, 'name'),
            ];
        }

        return $skills;
    }

    /**
     * @param array $item
     * @return array
     */
    protected function getAuthorData(array $item): array
    {
        return [
            'username' => Arr::get($item, 'attributes.employer.login'),
            'first_name' => Arr::get($item, 'attributes.employer.first_name'),
            'last_name' => Arr::get($item, 'attributes.employer.last_name'),
            //'is_active' => Arr::get($item, 'attributes.status.id') === 11, @todo get Status ids
            'author_link' => Arr::get($item, 'attributes.employer.self'),
        ];
    }

    /**
     * @param array $item
     * @return array
     */
    protected function getProjectData(array $item): array
    {
        return [
            'amount' => Arr::get($item, 'attributes.budget.amount'),
            'api_id' => Arr::get($item, 'id'),
            'author' => $this->getAuthorData($item),
            'title' => Arr::get($item, 'attributes.name'),
            'link' => Arr::get($item, 'links.self.web'),
            'published_at' => Arr::get($item, 'attributes.published_at'),
            'description' => Arr::get($item, 'attributes.description'),
            'currency' => Arr::get($item, 'attributes.budget.currency'),
            'skills' => $this->getSkillsData($item)
        ];
    }

    /**
     * @param string $pageQuery
     * @return ImportResponseInterface
     * @throws \ErrorException
     * @throws GuzzleException
     */
    protected function getPage(string $pageQuery = ''): ImportResponseInterface
    {
        $request = $this->client->request('GET', sprintf($this->strLink, $pageQuery));

        return new FreelancehuntResponse($this->getContent($request));
    }
}