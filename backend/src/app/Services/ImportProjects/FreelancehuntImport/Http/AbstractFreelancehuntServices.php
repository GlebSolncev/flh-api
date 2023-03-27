<?php

namespace App\Services\ImportProjects\FreelancehuntImport\Http;

use ErrorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 *
 */
abstract class AbstractFreelancehuntServices
{

    /**
     * @var FreelancehuntRequest
     */
    protected $client;

    /**
     *
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param ResponseInterface $response
     * @return string
     * @throws ErrorException
     */
    protected function getContent(ResponseInterface $response): string
    {
        try {
            $data = $response->getBody()->getContents();
        } catch (GuzzleException $exception) {
            throw new ErrorException($exception);
        }

        return $data;
    }
}