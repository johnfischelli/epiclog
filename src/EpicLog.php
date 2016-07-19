<?php

namespace EpicLog;

use Psr\Log\LoggerInterface;
use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class EpicLog
{
    /**
     * Holds the Monolog instance booted by Laravel/Lumen Frameworks
     * @var [type]
     */
    private $monolog;

    /**
     * An Array containing the log levels
     * @var array
     */
    private $levels;

    /**
     * The log output format -- see Monolog documentation
     * @var string
     */
    private $outputFormat;

    public function __construct(LoggerInterface $log)
    {
        // Assign the LoggerInterface
        $this->monolog = $log;

        // Setup Log levels according to RFC 5424
        $this->levels = [
            'debug',
            'info',
            'notice',
            'warning',
            'error',
            'critical',
            'alert',
            'emergency'
        ];
    }

    public function init()
    {
        if (config('epiclog.separate_logs_by_level')) {
            // remove default Laravel/Lumen monolog handlers
            $this->logger->popHandler();
            // setup Logs by log level
            $this->setupLogsByLevel();
        }
    }

    private function setupStreamHandlersByLevel()
    {
        $handlers = [];
        foreach ($this->levels as $level) {
            $handlers[$level] = new StreamHandler(
                storage_path("/logs/{$level}.log"),
                Monolog::strtoupper($level),
                false
            );
        }
        return $handlers;
    }

    private function setupFormatter()
    {
        return new LineFormatter($this->output);
    }

    public function setupLogs()
    {
        // Stream Handlers
        $handlers = $this->setupStreamHandlersByLevel();

        // setup Formatter
        $formatter = $this->setupFormatter();

        foreach ($handlers as $handler) {
            // apply formatter to Stream
            $handler->setFormatter($formatter);
            // push Stream into Laravel Log Instance
            $this->log->pushHandler($handler);
        }
    }
}
