<?php

namespace EpicLog;

use Monolog\Logger;

class CustomLogs
{
    private $channels;

    public function __construct()
    {
        $this->channels = [];
    }

    /**
     * [setupLoggers description]
     * @param  array  $logs [description]
     * @return [type]       [description]
     */
    public function setupLoggers(array $logs)
    {

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
