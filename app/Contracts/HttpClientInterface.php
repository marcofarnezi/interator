<?php
namespace App\Contracts;

interface HttpClientInterface
{
    public function add($url);

    public function loadClient($baseUrl);

    public function exec();

    public function getResult();
}