<?php
namespace App\Services\SiteMap;

use App\Contracts\SiteMapInterface;
use App\Services\Cache\CacheAbstract;
use App\Services\HtmlClient\HttpClientAbstract;

/**
 * Class SiteMapAbstract
 * @package App\Services\SiteMapLoad
 */
abstract class SiteMapAbstract implements SiteMapInterface
{
    private static $url;

    /**
     * @var HttpClientAbstract
     */
    private $httpClient;

    /**
     * @var CacheAbstract
     */
    private $cache;

    /**
     * SiteMapAbstract constructor.
     * @param HttpClientAbstract $httpClient
     * @param CacheAbstract $cache
     */
    public function __construct(HttpClientAbstract $httpClient,CacheAbstract $cache)
    {
        self::getUrl();
        $this->httpClient = $httpClient;
        $this->cache = $cache;
        $httpClient->loadClient(self::getBaseUrl());
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
     * Clear cache
     */
    public function clearResults()
    {
        $this->cache->remove(self::getUrl());
        $this->cache->remove(self::getBaseUrl());
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
        if ($this->cache->hasKeyInCache(self::getUrl())) {
            return $this->loadFromCache(self::getUrl());
        }

        $urlsReturned = $this->exec(self::$url);
        $result = $this->formatReturn($urlsReturned);
        $this->saveInCache(self::getUrl(), $result);
        return $result;
    }

    /**
     * @param array $urls
     * @return array
     */
    public function extract(array $urls) : array
    {
        if ($this->cache->hasKeyInCache(self::getBaseUrl())) {
            return $this->loadFromCache(self::getBaseUrl());
        }
        $result = $this->extractInfoByAll($urls, $this->httpClient);
        $this->saveInCache(self::getBaseUrl(), $result);
        return $result;
    }

    /**
     * @param $key
     * @param array $value
     */
    private function saveInCache($key, array $value)
    {
        $this->cache->save(
            $key,
            json_encode($value),
            config('services.sitemap.cachetime')
        );
    }

    /**
     * @param $key
     * @return array
     */
    private function loadFromCache($key) : array
    {
        $result = $this->cache->get($key);
        return json_decode($result, true);
    }

    public static abstract function url();

    public abstract function exec($url);

    public abstract function formatReturn($urlsReturned);

    public abstract function extractInfoByAll($urls, $httpClient);
}