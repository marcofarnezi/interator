<?php
namespace App\Contracts;

/**
 * Interface SiteMapInterface
 * @package App\Contracts
 */
interface SiteMapInterface
{
    public function __construct($httpClient);
    public static function getUrl();
    public static function getBaseUrl();
    public function load();
    public function extract(array $urls);

}