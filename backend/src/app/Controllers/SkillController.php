<?php

namespace App\Controllers;

use App\Services\ProjectService;
use App\Services\SkillService;
use Symfony\Component\HttpFoundation\Response;

/**
 * @var ProjectService $service
 */
class SkillController extends AbstractController
{
    /**
     * @return string
     */
    protected function getServiceClassName(): string
    {
        return SkillService::class;
    }

    /**
     * @return Response
     */
    public function index()
    {
        $this->response->setContent($this->service->getForResponse());
        return $this->response;
    }
}