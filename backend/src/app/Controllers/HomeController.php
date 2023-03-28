<?php

namespace App\Controllers;

use App\Services\ProjectService;
use App\Services\SkillService;
use Symfony\Component\HttpFoundation\Response;

/**
 * @var ProjectService $service
 */
class HomeController extends AbstractController
{
    /**
     * @return string
     */
    protected function getServiceClassName(): string
    {
        return '';
    }

    /**
     * @return Response
     */
    public function index()
    {
        return "Welcome";
    }
}