<?php

namespace EpicLog;

use Psr\Log\LoggerInterface;
use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

/**
 * EpicLog - Advanced Monolog configuration for Laravel/Lumen
 *
 * @author  John Fischelli <john.fischelli@gmail.com>
 */
class EpicLog
{
    /**
     * Holds the Monolog instance booted by Laravel/Lumen Frameworks
     * @var \Monolog\Logger
     */
    private $monolog;

    /**
     * An Array containing the log levels
     * @var array
     */
    private $levels;

    /**
     * Constructor
     * The LoggerInterface is injected by the container.
     * It is the Monolog instance boostratpped by the Laravel/Lumen framework.
     *
     * @param Psr\Log\LoggerInterface $log
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

    /**
     * Loops through each Log level defined in $this->levels and creates a StreamHandler class for it
     *
     * @return array of \Monolog\StreamHandler instances corresponding to each log level
     */
    private function setupStreamHandlersByLevel()
    {
        $handlers = [];
        foreach ($this->levels as $level => $monologStatic) {
            if (config('epiclog.rotate_log_by_level')) {
                $handlers[$level] = $this->setupRotatingLog($level, $monologStatic);
            } else {
                $handlers[$level] = $this->setupNormalLog($level, $monologStatic);
            }
        }
        return $handlers;
    }

    /**
     * Setups a rotating StreamHandler for a log.
     *
     * @param  string $level         key of the array $this->levels - will be the log filename.
     * @param  const $monologStatic  log level (RFC 5424)
     * @return \Monolog\StreamHandler
     */
    private function setupRotatingLog($level, $monologStatic)
    {
        return new RotatingFileHandler(
            storage_path("/logs/{$level}.log"),
            config('epiclog.rotate_log_by_level_num_days'),
            $monologStatic,
            false
        );
    }

    /**
     * Setups a normal StreamHandler for a log.
     *
     * @param  string $level         key of the array $this->levels - will be the log filename.
     * @param  const $monologStatic  log level (RFC 5424)
     * @return \Monolog\StreamHandler
     */
    private function setupNormalLog($level, $monologStatic)
    {
        return new StreamHandler(
            storage_path("/logs/{$level}.log"),
            $monologStatic,
            false
        );
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
}
