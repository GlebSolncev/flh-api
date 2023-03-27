<?php

namespace App\Services\ImportProjects\FreelancehuntImport;

use App\Services\ImportProjects\FreelancehuntImport\Http\FreelancehuntRequest;
use App\Services\ImportProjects\FreelancehuntImport\Http\FreelancehuntProjectsService;
use App\Services\ImportProjects\Interfaces\ImportFactoryInterface;
use App\Services\ImportProjects\Interfaces\ImportProjectsInterface;
use Illuminate\Support\Arr;

class FreelancehuntFactory implements ImportFactoryInterface
{
    public function getProjects(): ImportProjectsInterface
    {
        return  new FreelancehuntProjectsService(new FreelancehuntRequest());
    }
}