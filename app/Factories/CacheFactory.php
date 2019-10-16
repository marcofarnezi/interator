<?php
namespace App\Factories;

use App\Services\Cache\CacheAbstract;
use App\Services\Cache\ZipCache;

/**
 * Class CacheFactory
 * @package App\Factories
 */
class CacheFactory
{
    const ZIP = 'zip';

    /**
     * @return CacheAbstract
     * @throws \Exception
     */
    public function createCache() : CacheAbstract
    {
        $type = config('cache.compressor');

        switch ($type) {
            case self::ZIP:
                return new ZipCache();
            default:
                throw new \Exception('undefined type');
        }
    }
}