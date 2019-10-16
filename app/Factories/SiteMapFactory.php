<?php
namespace App\Factories;

use App\Services\Cache\CacheAbstract;
use App\Services\HtmlClient\HttpClientAbstract;
use App\Services\SiteMap\SiteMapAbstract;

/**
 * Class SiteMapFactory
 * @package App\Factories
 */
class SiteMapFactory
{
    private $type;

    /**
     * @var HttpClientAbstract
     */
    private $httpClient;

    /**
     * @var CacheAbstract
     */
    private $cache;

    /**
     * SiteMapFactory constructor.
     * @param $type
     * @param HttpClientAbstract $httpClient
     * @param CacheAbstract $cache
     */
    public function __construct(
        $type,
        HttpClientAbstract $httpClient,
        CacheAbstract $cache
    )
    {
        $this->type = $type;
        $this->httpClient = $httpClient;
        $this->cache = $cache;
    }

    /**
     * @return SiteMapAbstract
     * @throws \Exception
     */
    public function createSiteMap() : SiteMapAbstract
    {
        try {
            return $this->getClass();
        } catch (\ReflectionException $exception) {
            throw $exception;
        }
    }

    /**
     * @return SiteMapAbstract
     * @throws \ReflectionException
     */
    private function getClass() : SiteMapAbstract
    {
        $className = "\App\Services\SiteMap\\" . $this->type;
        $classReflection = new \ReflectionClass($className);
        return $classReflection->newInstanceArgs([$this->httpClient, $this->cache]);
    }
}