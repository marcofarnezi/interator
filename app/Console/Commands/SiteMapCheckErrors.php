<?php

namespace App\Console\Commands;

use App\Factories\CacheFactory;
use App\Factories\HttpClientFactory;
use App\Factories\SiteMapFactory;
use App\Loaders\SiteMapLoader;
use Illuminate\Console\Command;

class SiteMapCheckErrors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:check {name : CamelCase}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check sitemap routes - Parameter{name : CamelCase}';

    private $cacheFactory;
    private $httpClientFactory;
    private $siteMapFactory;
    private $type;
    /**
     * @var SiteMapLoader
     */
    private $loader;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->cacheFactory = new CacheFactory();
        $this->httpClientFactory = new HttpClientFactory();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->type = $this->argument('name');
        $this->line('');
        $this->info('Starting error detection for ' . $this->type);

        $result = $this->load();
        $this->table(['Route', 'Status Code'], $this->formatReturn($result));

        $this->line('');
        $this->info('Summary');
        $this->table(['Success', 'Errors'], [[count($result['success']), count($result['errors'])]]);

        $this->line('');
        $this->info('SiteMap Origin : ' . $this->loader->getSiteMapUrlOrigin());
        $this->line('');
    }

    private function load()
    {
        $cache = $this->cacheFactory->createCache();
        $httpClient = $this->httpClientFactory->createHttpClient();

        $this->siteMapFactory = new SiteMapFactory($this->type, $httpClient, $cache);
        $this->loader = new SiteMapLoader(
            $this->siteMapFactory,
            $this->httpClientFactory,
            $this->cacheFactory
        );

        return $this->loader->load();
    }

    private function formatReturn($result)
    {
        $tbody = [];
        if (! empty($result['success'])) {
            foreach ($result['success'] as $http => $result) {
                $tbody[] = [
                    '\\' . $http, $result['status_code']
                ];
            }
        }
        if (! empty($result['errors'])) {
            foreach ($result['errors'] as $http => $result) {
                $tbody[] = [
                    $http, $result['status_code']
                ];
            }
        }

        return $tbody;
    }
}
