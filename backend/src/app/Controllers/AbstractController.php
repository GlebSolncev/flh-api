<?php

namespace App\Controllers;

use App\Services\AbstractService;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;

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
     * AbstractController construct
     *
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        if($this->getServiceClassName())
            $this->service = Container::getInstance()->make($this->getServiceClassName());
    }


    /**
     * @return string
     */
    abstract protected function getServiceClassName(): string;

}