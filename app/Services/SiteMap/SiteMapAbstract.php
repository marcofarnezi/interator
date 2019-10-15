<?php
namespace App\Services\SiteMap;

use App\Contracts\SiteMapInterface;
use phpDocumentor\Reflection\Types\Void_;

/**
 * Class SiteMapAbstract
 * @package App\Services\SiteMap
 */
abstract class SiteMapAbstract implements SiteMapInterface
{
    private static $url;
    private $httpClient;

    public function __construct($httpClient)
    {
        self::getUrl();
        $this->httpClient = $httpClient;
    }

    /**
     * @return string
     */
    public static function getUrl() : string
    {
        if (empty(self::$url)) {
            self::$url = static::url();
        }

        return self::$url;
    }

    /**
     * @return string
     */
    public static function getBaseUrl() : string
    {
        $resultParseUrl = parse_url(self::getUrl());
        return $resultParseUrl['scheme'] . '://' . $resultParseUrl['host'];
    }

    /**
     * @return array
     */
    public function load() : array
    {
        $urlsReturned = $this->exec(self::$url);
        return $this->formatReturn($urlsReturned);
    }

    /**
     * @param array $urls
     * @return array
     */
    public function extract(array $urls) : array
    {
        return $this->extractInfoByAll($urls, $this->httpClient);
    }

    public static abstract function url();
    public abstract function exec($url);
    public abstract function formatReturn($urlsReturned);
    public abstract function extractInfoByAll($urls, $httpClient);
}