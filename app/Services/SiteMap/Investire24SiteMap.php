<?php
namespace App\Services\SiteMap;

use App\Services\HtmlClient\HttpClientAbstract;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class Investire24SiteMap
 * @package App\Services\SiteMap
 */
class Investire24SiteMap extends SiteMapAbstract
{
    const URL_BASE = 'www.investire24.it';
    const URL_PATH = 'post-sitemap.xml';
    const URL_HTTP_TYPE = 'https';

    /**
     * @return string
     */
    public static function url() : string
    {
        return self::URL_HTTP_TYPE . '://' . self::URL_BASE . '/' . self::URL_PATH;
    }

    /**
     * @param $url
     * @return \SimpleXMLElement
     */
    public function exec($url) : \SimpleXMLElement
    {
        $xml = @simplexml_load_file($this->getUrl());
        if (! $xml) {
            throw new HttpException(404);
        }

        return $xml;
    }

    /**
     * @param $urlsReturned
     * @return array
     */
    public function formatReturn($urlsReturned) : array
    {
        $json = json_encode($urlsReturned);
        return json_decode($json, true);
    }

    /**
     * @param $urls
     * @param HttpClientAbstract $httpClient
     * @return array
     */
    public function extractInfoByAll($urls, $httpClient) : array
    {
        foreach ($urls['url'] as $data) {
            $httpClient->add(self::extractUrlToAddInHttpClient($data['loc']));
        }
        $httpClient->exec();
        return $httpClient->getResult();

    }

    /**
     * @param $url
     * @return string
     */
    private static function extractUrlToAddInHttpClient($url) : string
    {
        $urlParsed = parse_url($url);
        return array_key_exists('path', $urlParsed) ? ltrim($urlParsed['path']) : '/';
    }
}