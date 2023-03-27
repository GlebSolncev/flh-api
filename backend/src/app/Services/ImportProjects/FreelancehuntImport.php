<?php

namespace App\Services\ImportProjects;

use App\Services\ImportProjects\FreelancehuntImport\FreelancehuntFactory;
use App\Services\ImportProjects\Interfaces\ImportFactoryInterface;

/**
 *
 */
class FreelancehuntImport extends AbstractImports
{

    /**
     * @return ImportFactoryInterface
     */
    protected function getImporter(): ImportFactoryInterface
    {
        return new FreelancehuntFactory();
    }
}