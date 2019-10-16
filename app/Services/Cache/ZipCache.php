<?php
namespace App\Services\Cache;

/**
 * Class ZipCache
 * @package App\Services\Cache
 */
class ZipCache extends CacheAbstract
{
    /**
     * @param $value
     * @return false|string
     */
    public function zip($value)
    {
        $compressed = gzdeflate($value,  9);
        return gzdeflate($compressed, 9);
    }

    /**
     * @param $value
     * @return false|string
     */
    public function unzip($value)
    {
        if (! empty($value)) {
            return gzinflate(gzinflate($value));
        }
        return false;
    }
}