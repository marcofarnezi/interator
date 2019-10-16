<?php
namespace App\Services\HtmlClient;

use App\Contracts\HttpClientInterface;

/**
 * Class HttpClientAbstract
 * @package App\Services\HtmlClient
 */
abstract class HttpClientAbstract implements HttpClientInterface
{
    /**
     * @var HttpClientAbstract
     */
    private $client;

    /**
     * @param $url
     */
    public function add($url)
    {
        $this->addUrl($url, $this->client);
    }

    /**
     * Exec all urls
     */
    public function exec()
    {
        $this->execAll($this->client);
    }

    /**
     * Load a HttpClient
     */
    public function loadClient($baseUrl)
    {
        $clientInstanceName = $this->client();
        $initializeParameter = $this->loadInitializeParameter($baseUrl);
        $this->client = new $clientInstanceName($initializeParameter);
    }

    /**
     * @return array
     */
    public function getResult() : array
    {
        $results = [];
        $results['success'] = $this->getSuceess();
        $results['errors'] = $this->getErrors();

        return $results;
    }

    public abstract function addUrl($url, $client);

    public abstract function execAll($client);

    public abstract function client();

    public abstract function loadInitializeParameter($url);

    public abstract function getSuceess();

    public abstract function getErrors();

}