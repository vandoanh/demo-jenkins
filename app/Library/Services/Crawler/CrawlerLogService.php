<?php

namespace App\Library\Services\Crawler;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Carbon\Carbon;

class CrawlerLogService
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger('Crawler');
        $this->logger->pushHandler(new StreamHandler(storage_path('logs/laravel-' . Carbon::now()->format('Y-m-d') . '.log')));
    }

    public function writeLog($logData)
    {
        $this->logger->info('Log', [$logData]);

        return true;
    }
}
