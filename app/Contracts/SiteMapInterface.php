<?php
namespace App\Contracts;

use App\Services\Cache\CacheAbstract;
use App\Services\HtmlClient\HttpClientAbstract;

/**
 * Interface SiteMapInterface
 * @package App\Contracts
 */
interface SiteMapInterface
{
    public function __construct(HttpClientAbstract $httpClient,CacheAbstract $cache);

    public static function getUrl();

    public static function getBaseUrl();

    public function load();

    public function extract(array $urls);

    public function clearResults();
}