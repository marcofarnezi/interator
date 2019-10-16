<?php
namespace App\Loaders;

use App\Factories\CacheFactory;
use App\Factories\HttpClientFactory;
use App\Factories\SiteMapFactory;
use App\Services\SiteMap\SiteMapAbstract;

/**
 * Class SiteMapLoader
 * @package App\Loaders
 */
class SiteMapLoader
{
    /**
     * @var CacheFactory
     */
    private $cacheFactory;

    /**
     * @var HttpClientFactory
     */
    private $httpClientFactory;

    /**
     * @var SiteMapFactory
     */
    private $siteMapFactory;

    /**
     * @var SiteMapAbstract
     */
    private $siteMap;

    /**
     * SiteMapLoader constructor.
     * @param SiteMapFactory $siteMapFactory
     * @param HttpClientFactory $httpClientFactory
     * @param CacheFactory $cacheFactory
     * @throws \Exception
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

    /**
     * @return string
     */
    public function getSiteMapUrlOrigin() : string
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
    public function load() : array
    {
        $urls = $this->siteMap->load();
        return $this->siteMap->extract($urls);
    }
}