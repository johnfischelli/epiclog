<?php

namespace EpicLog;

use Monolog\Logger;
use Psr\Log\LoggerInterface;

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
     * An Array holding Handlers to be setup
     * @var [type]
     */
    private $handlers;

    /**
     * Contains helper methods for interacting with Monolog
     *
     * @var Helper
     */
    private $helper;

    /**
     * Constructor
     * The LoggerInterface is injected by the container.
     * It is the Monolog instance boostratpped by the Laravel/Lumen framework.
     *
     * @param \Psr\Log\LoggerInterface  $log
     * @param \EpicLog\helper           $helper
     */
    public function __construct(LoggerInterface $log, Helper $helper)
    {
        // Log will either be Logger or Laravel Writer
        if (get_class($log) != Logger::class) {
            // Get the Monolog Instance that Laravel is using
            $log = $log->getMonolog();
        }
        $this->monolog = $log;

        // injected Helper instance
        $this->helper = $helper;

        // define an empty array to hold handlers
        $this->handlers = [];
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
            // setup handlers by log level
            $this->setupStreamHandlersByLevel();
        }

        if (config('epiclog.push_errors_to_stderr')) {
            // push error logs and above to stderr
            $this->setupStdErrHandler();
        }

        if (config('epiclog.push_logs_to_stdout')) {
            // push all logs to stdout
            $this->setupStdOutHandler();
        }

        // actually alters the Log facade
        // depending on configuration settings, logs would now be configured by level
        // and could also potentially push log messages to stderr or stdout
        $this->setupLogs();

        // if custom logs are in the configuration, let's go ahead and create them.
        $logs = config('epiclog.logs');
        if (is_array($logs) && count($logs) >= 1) {
            $this->setupCustomLogs($logs);
        }
    }

    /**
     * Loops through each Log level defined in $this->levels and creates a StreamHandler class for it
     *
     * @return null
     */
    private function setupStreamHandlersByLevel()
    {
        foreach ($this->helper->levels as $name => $monologStatic) {
            if (config('epiclog.rotate_log_by_level')) {
                $this->handlers[] = $this->helper->setupRotatingLog($name, $monologStatic);
            } else {
                $this->handlers[] = $this->helper->setupNormalLog($name, $monologStatic);
            }
        }
    }

    /**
     * Proxy function that will use the Helper class to setup a StreamHandler to
     * write logs to stdout. Sets this StreamHandler to this class' $handlers array
     *
     * @return null
     */
    public function setupStdOutHandler()
    {
        $this->handlers[] = $this->helper->setupStdOutHandler();
    }

    /**
     * Proxy function that will use the Helper class to setup a StreamHandler to
     * write logs to stderr. Sets this StreamHandler to this class' $handlers array
     *
     * @return null
     */
    public function setupStdErrHandler()
    {
        $this->handlers[] = $this->helper->setupStdErrHandler();
    }

    /**
     * This function gets all Handlers and the Formatter and assigns them onto
     * the underlying Monolog instance that Laravel uses.
     *
     * @return null
     */
    public function setupLogs()
    {
        // setup Formatter
        $formatter = $this->helper->setupFormatter();

        foreach ($this->handlers as $handler) {
            // apply formatter to Stream
            $handler->setFormatter($formatter);
            // push Stream into Laravel Log Instance
            $this->monolog->pushHandler($handler);
        }
    }

    /**
     * Helper function that will take the epiclog configuration for custom logs
     * and pass it down to the CustomLogs class - This class will setup a new monolog
     * instance with new handlers, etc. This monolog instance can be accessed from the
     * Epiclog facade.
     *
     * @param  array  $logs array representing data of custom logs to create
     */
    private function setupCustomLogs(array $logs)
    {
        $instance = app()['epiclog'];
        $instance->setupChannels($logs);
    }
}
