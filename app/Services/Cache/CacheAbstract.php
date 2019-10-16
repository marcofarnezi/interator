<?php
namespace App\Services\Cache;

use App\Contracts\CacheInterface;
use Illuminate\Support\Facades\Cache;

/**
 * Class CacheAbstract
 * @package App\Services\Cache
 */
abstract class CacheAbstract implements CacheInterface
{
    private $registers = [];

    /**
     * @param $value
     * @return string
     */
    public function compact($value) : string
    {
        return $this->zip($value);
    }

    /**
     * @param $value
     * @return string
     */
    public function unpack($value) : string
    {
        return $this->unzip($value);
    }

    /**
     * @param $key
     * @return array
     */
    public function remove($key) : array
    {
        Cache::forget($key);
        Cache::flush();
        unset($this->registers[$key]);
        return $this->registers;
    }

    /**
     * @param $key
     * @param $value
     * @param $time
     * @return mixed
     */
    public function save($key, $value, $time)
    {
        $valueCompacted = $this->compact($value);
        return Cache::add($key, $valueCompacted, $time);
    }

    /**
     * @param $key
     * @return string
     */
    public function get($key) : string
    {
        if (! isset($this->registers[$key])) {
            $returnValue = Cache::get($key);
            $this->registers[$key] = $this->unpack($returnValue);
        }

        return $this->registers[$key];
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasKeyInCache($key) : bool
    {
        return Cache::has($key);
    }

    public abstract function zip($value);

    public abstract function unzip($value);
}