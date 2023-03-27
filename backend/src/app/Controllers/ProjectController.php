<?php

namespace App\Controllers;

use App\Services\ProjectService;
use Symfony\Component\HttpFoundation\Response;

/**
 * @var ProjectService $service
 */
class ProjectController extends AbstractController
{
    /**
     * @return string
     */
    protected function getServiceClassName(): string
    {
        return ProjectService::class;
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