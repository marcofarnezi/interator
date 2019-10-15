<?php
namespace App\Contracts;

interface HttpClientInterface
{
    public function __construct($baseUrl);
    public function add($url);
    public function loadClient();
    public function exec();
    public function getResult();

}