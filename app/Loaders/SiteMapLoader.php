<?php
namespace App\Loaders;

use App\Factories\CacheFactory;
use App\Factories\HttpClientFactory;
use App\Factories\SiteMapFactory;

/**
 * Class SiteMapLoader
 * @package App\Loaders
 */
class SiteMapLoader
{
    private $cacheFactory;
    private $httpClientFactory;
    private $siteMapFactory;
    private $siteMap;

    /**
     * SiteMapLoader constructor.
     * @param SiteMapFactory $siteMapFactory
     * @param HttpClientFactory $httpClientFactory
     * @param CacheFactory $cacheFactory
     */
    public function __construct(
        SiteMapFactory $siteMapFactory,
        HttpClientFactory $httpClientFactory,
        CacheFactory $cacheFactory
    )
    {
        $this->cacheFactory = $cacheFactory;
        $this->httpClientFactory = $httpClientFactory;
        $this->siteMapFactory = $siteMapFactory;
        $this->createObjs();
    }

    public function getSiteMapUrlOrigin()
    {
        $className = get_class($this->siteMap);
        return $className::getUrl();
    }

    /**
     * @throws \Exception
     */
    private function createObjs()
    {
        $cache =  $this->cacheFactory->createCache();
        $httpClient =  $this->httpClientFactory->createHttpClient();
        $siteMap = $this->siteMapFactory->createSiteMap($httpClient, $cache);
        $this->siteMap = $siteMap;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function load()
    {
        $urls = $this->siteMap->load();
        return $this->siteMap->extract($urls);
    }
}