<?php

namespace App\Controllers;

use App\Services\ProjectService;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request)
    {
        return $this->service->getForResponse((int)$request->get('page', 1), $request->toArray());
    }

    public function budgets(Request $request){
        return $this->service->getBudgets($request->toArray());
    }
}