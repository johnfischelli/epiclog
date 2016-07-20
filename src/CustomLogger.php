<?php

namespace EpicLog;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class CustomLogger
{
    private $channels;

    public function __construct()
    {
        $this->channels = [];
    }

    /**
     * [setupChannels description]
     * @param  array  $logs [description]
     * @return [type]       [description]
     */
    public function setupChannels(array $logs)
    {
        foreach ($logs as $log) {
            
        }
    }

    /**
     * [__call description]
     * @param  [type] $name      [description]
     * @param  [type] $arguments [description]
     * @return [type]            [description]
     */
    public function __call($name, $arguments)
    {
        $logger = $this->channels[$name];

        if ($logger) {
            $logger->addInfo($arguments[0], $arguments[1]);
        }
    }
}
