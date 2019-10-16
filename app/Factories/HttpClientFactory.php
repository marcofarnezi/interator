<?php
namespace App\Factories;

use App\Services\HtmlClient\GuzzleAsyncHttpClient;
use App\Services\HtmlClient\HttpClientAbstract;

/**
 * Class HttpClientFactory
 * @package App\Factories
 */
class HttpClientFactory
{
    const GUZZLE = 'guzzle';

    /**
     * @return HttpClientAbstract
     * @throws \Exception
     */
    public function createHttpClient() : HttpClientAbstract
    {
        $type = config('services.http_client.type');
        switch ($type) {
            case self::GUZZLE:
                return new GuzzleAsyncHttpClient();
            default:
                throw new \Exception('undefined type');
        }
    }
}