<?php
namespace App\Services\Cache;

use App\Contracts\CacheInterface;

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
        $this->dropRegister($key);
        unset($this->registers[$key]);
        return $this->registers;
    }

    public function save($key, $value)
    {
        $valueCompacted = $this->compact($value);
        return $this->saveRegister($key, $valueCompacted);
    }

    public function get($key) : string
    {
        if (! isset($this->registers[$key])) {
            $returnValue = $this->getRegister($key);
            $this->registers[$key] = $this->unpack($returnValue);
        }

        return $this->registers[$key];
    }

    public abstract function saveRegister($key, $value);
    public abstract function dropRegister($key);
    public abstract function zip($value);
    public abstract function unzip($value);
    public abstract function getRegister($key);
}