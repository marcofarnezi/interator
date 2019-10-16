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

    public function compact($value) : string
    {
        return $this->zip($value);
    }

    public function unpack($value) : string
    {
        return $this->unzip($value);
    }

    public function remove($key) : array
    {
        Cache::forget($key);
        Cache::flush();
        unset($this->registers[$key]);
        return $this->registers;
    }

    public function save($key, $value, $time)
    {
        $valueCompacted = $this->compact($value);
        return Cache::add($key, $valueCompacted, $time);
    }

    public function get($key) : string
    {
        if (! isset($this->registers[$key])) {
            $returnValue = Cache::get($key);
            $this->registers[$key] = $this->unpack($returnValue);
        }

        return $this->registers[$key];
    }

    public function hasKeyInCache($key) : bool
    {
        return Cache::has($key);
    }

    public abstract function zip($value);
    public abstract function unzip($value);
}