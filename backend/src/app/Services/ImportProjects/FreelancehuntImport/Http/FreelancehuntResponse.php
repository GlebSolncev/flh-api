<?php

namespace App\Services\ImportProjects\FreelancehuntImport\Http;

use App\Services\ImportProjects\Interfaces\ImportResponseInterface;

class FreelancehuntResponse implements ImportResponseInterface

{
    public function __construct(protected string $content)
    {
    }

    public function toArray(): array
    {
        return json_decode($this->content, true);
    }
}