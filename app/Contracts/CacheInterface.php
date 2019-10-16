<?php
namespace App\Contracts;

/**
 * Interface CacheInterface
 * @package App\Contracts
 */
interface CacheInterface
{
    public function save($key, $value, $time);

    public function compact($value);

    public function unpack($value);

    public function remove($key);

    public function get($key);

    public function hasKeyInCache($key);
}