<?php

namespace App\Services\ImportProjects;

use App\Services\ImportProjects\Interfaces\ImportFactoryInterface;

abstract class AbstractImports
{
    public function importProjects(){
        $importer = $this->getImporter();

        return  $importer->getProjects()->getResponse();
    }


    abstract protected function getImporter(): ImportFactoryInterface;
}