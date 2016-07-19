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
     * Constructor
     * @param LoggerInterface $log This is the Laravel/Lumen Monolog Interface
     */
    public function __construct(LoggerInterface $log)
    {
        // Get the Monolog Instance that Laravel/Lumen is using
        $this->monolog = $log->getMonolog();

        // Setup Log levels according to RFC 5424
        $this->levels = [
            'debug' => Monolog::DEBUG,
            'info' => Monolog::INFO,
            'notice' => Monolog::NOTICE,
            'warning' => Monolog::WARNING,
            'error' => Monolog::ERROR,
            'critical' => Monolog::CRITICAL,
            'alert' => Monolog::ALERT,
            'emergency' => Monolog::EMERGENCY
        ];
    }

    /**
     * Initializes Epic Log
     *
     * @return null
     */
    public function init()
    {
        if (config('epiclog.separate_logs_by_level')) {
            // remove default Laravel/Lumen monolog handlers
            $this->monolog->popHandler();
            // setup Logs by log level
            $this->setupLogs();
        }
    }

    /**
     * Loops through each Log level defined in $this->levels and creates a StreamHandler class for it
     *
     * @return array of \Monolog\StreamHandler instances corresponding to each log level
     */
    private function setupStreamHandlersByLevel()
    {
        $handlers = [];
        foreach ($this->levels as $level => $monologStatic) {
            $handlers[$level] = new StreamHandler(
                storage_path("/logs/{$level}.log"),
                $monologStatic,
                false
            );
        }
        return $handlers;
    }

    /**
     * Returns a Monolog\LineFormatter Instance configured like Laravel's default
     *
     * @return Monolog\LineFormatter
     */
    private function setupFormatter()
    {
        return new LineFormatter(null, null, true, true);
    }

    /**
     * This function gets all Handlers and the Formatter and assigns them onto
     * the underlying Monolog instance that Laravel uses.
     *
     * @return null
     */
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
            $this->monolog->pushHandler($handler);
        }
    }
}
