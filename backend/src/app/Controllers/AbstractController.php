<?php

namespace App\Controllers;

use App\Services\AbstractService;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
abstract class AbstractController
{

    /**
     * @var AbstractService $service
     */
    protected AbstractService $service;

    /**
     * @param Response $response
     * @throws BindingResolutionException
     */
    public function __construct(protected Response $response)
    {
        $this->response->headers = ['Content-Type' => 'application/json'];

        if($this->getServiceClassName())
            $this->service = Container::getInstance()->make($this->getServiceClassName());
    }


    /**
     * @return string
     */
    abstract protected function getServiceClassName(): string;

}