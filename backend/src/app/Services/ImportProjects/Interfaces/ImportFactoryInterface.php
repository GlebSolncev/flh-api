<?php

namespace App\Services\ImportProjects\Interfaces;

interface ImportFactoryInterface
{
    public function getProjects(): ImportProjectsInterface;
}