<?php

namespace App\Services\ImportProjects\Interfaces;

use Illuminate\Support\Collection;

interface ImportProjectsInterface
{
    public function getResponse(): Collection;
}