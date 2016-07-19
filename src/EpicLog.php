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
            $this->setupLogsByLevel();
        }

        if (config('epiclog.push_errors_to_stderr')) {
            // push error logs and above to stderr
            $this->setupStdErrHandler();
        }

        if (config('epiclog.push_logs_to_stdout')) {
            // push all logs to stdout
            $this->setupStdOutHandler();
        }

        $logs = config('epiclog.logs');
        if (is_array($logs) && count($logs) > 1) {
            $this->setupCustomLogs($logs);
        }
    }

    /**
     * This function gets all Handlers and the Formatter and assigns them onto
     * the underlying Monolog instance that Laravel uses.
     *
     * @return null
     */
    public function setupLogsByLevel()
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

    /**
     * Alters the Log facade to push error and above log levels to stderr
     *
     * @return null
     */
    private function setupStdErrHandler()
    {
        $handler = new StreamHandler("php://stderr", $this->levels['error'], true);
        $handler->setFormatter($this->setupFormatter());
        $this->monolog->pushHandler($handler);
    }

    /**
     * Alters the Log facade to push debug and above log levels to stdout
     *
     * @return null
     */
    private function setupStdOutHandler()
    {
        $handler = new StreamHandler("php://stdout", $this->levels['debug'], true);
        $handler->setFormatter($this->setupFormatter());
        $this->monolog->pushHandler($handler);
    }

    /**
     * Helper function that will take the epiclog configuration for custom logs
     * and pass it down to the CustomLogs class - This class will setup a new monolog
     * instance with new handlers, etc. This monolog instance can be accessed from the
     * EpicLog facade.
     *
     * @param  array  $logs array representing data of custom logs to create
     */
    private function setupCustomLogs(array $logs)
    {
        app()['epiclog']->setupCustomLogs($logs);
    }
}
