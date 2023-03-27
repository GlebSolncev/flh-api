<?php

namespace App\Services\ImportProjects\FreelancehuntImport\Http;

use GuzzleHttp\Client;

/**
 *
 */
class FreelancehuntRequest extends Client
{
    /**
     * @var string
     */
    protected string $baseUri = 'https://api.freelancehunt.com/';

    /**
     * @var int
     */
    protected int $timeout = 5;

    /**
     * @var array|string[]
     */
    protected array $defaultHeaders = [
        'Authorization'     => 'Bearer 2c17b3c0c5bb0a854dcb00212ef67f89032d33fd'
    ];

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct(array_merge($config, ['base_uri' => $this->baseUri, 'timeout' => $this->timeout, 'headers' => $this->defaultHeaders]));
    }
}