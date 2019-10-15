<?php
namespace App\Services\HtmlClient;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\ClientException;

/**
 * Class GuzzleAsyncHttpClient
 * @package App\Services\HtmlClient
 */
class GuzzleAsyncHttpClient extends HttpClientAbstract
{
    private $promises = [];
    private $success = [];
    private $errors = [];

    /**
     * @param $string
     * @return string
     */
    public static function generatePromiseId($string) : string
    {
        $string = str_replace(' ', '-', $string);
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        return preg_replace('/-+/', '-', $string);
    }

    /**
     * @param $url
     * @return array
     */
    public function loadInitializeParameter($url) : array
    {
        return ['base_uri' => $url];
    }

    /**
     * @param $url
     * @param $client
     */
    public function addUrl($url, $client)
    {
        $promiseKey = self::generatePromiseId($url);
        $this->promises[$promiseKey] = $client->getAsync($url)->then(
            function(Response $response) {
                return $response;
            },
            function ($error) {
                return $error;
            }
        );
    }

    /**
     * @param $client
     */
    public function execAll($client)
    {
        $result = Promise\settle($this->promises)->wait();
        foreach ($this->promises as $key => $obj) {

            if ($result[$key]['value'] instanceof Response) {
                $this->success[$key]['status_code'] = $result[$key]['value']->getStatusCode();
                $this->success[$key]['headers'] = $result[$key]['value']->getHeaders();
                $this->success[$key]['body'] = $result[$key]['value']->getBody()->getContents();
            }

            if ($result[$key]['value'] instanceof ClientException) {
                $this->errors[$key]['status_code'] = $result[$key]['value']->getCode();
                $this->errors[$key]['headers'] = $result[$key]['value']->getResponse()->getHeaders();
                $this->errors[$key]['body'] = $result[$key]['value']->getMessage();
            }
        }
    }

    /**
     * @return string
     */
    public function client() : string
    {
        return Client::class;
    }

    /**
     * @return array
     */
    public function getSuceess() : array
    {
        return $this->success;
    }

    /**
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
}