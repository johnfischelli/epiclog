<?php

namespace EpicLog;

use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class Helper
{
    /**
     * An Array containing the log levels
     * @var array
     */
    public $levels;

    public function __construct()
    {
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
     * Setups a rotating StreamHandler for a log.
     *
     * @param  string $filename         the log filename.
     * @param  const $level             log level (RFC 5424)
     * @return \Monolog\StreamHandler
     */
    public function setupRotatingLog($filename, $level)
    {
        return new RotatingFileHandler(
            storage_path("logs/{$filename}.log"),
            config('epiclog.rotate_log_by_level_num_days'),
            $level,
            false
        );
    }

    /**
     * Setups a normal StreamHandler for a log.
     *
     * @param  string $filename         the log filename.
     * @param  const $level             log level (RFC 5424)
     * @return \Monolog\StreamHandler
     */
    public function setupNormalLog($filename, $level)
    {
        return new StreamHandler(
            storage_path("logs/{$filename}.log"),
            $level,
            false
        );
    }

    /**
     * Creates StreamHandlers based on configuration array
     *
     * @param  array  $config
     * @return mixed \Monolog\StreamHandler or \Monolog\RotatingFileHandler
     */
    public function setupLogByConfig(array $config)
    {
        $level = $this->getMonologLevel($config['level']);

        if ($config['rotate']) {
            return new RotatingFileHandler(
                $config['location'],
                $config['num_days'],
                $level,
                $config['bubbles']
            );
        } else {
            return new StreamHandler(
                $config['location'],
                $level,
                $config['bubbles']
            );
        }
    }

    /**
     * Returns a StreamHandler that pushes messages to stderr
     *
     * @return \Monolog\StreamHandler
     */
    public function setupStdErrHandler()
    {
        return new StreamHandler("php://stderr", Monolog::ERROR, true);
    }

    /**
     * Returns a StreamHandler that pushes messages to stdout
     *
     * @return \Monolog\StreamHandler
     */
    public function setupStdOutHandler()
    {
        return new StreamHandler("php://stdout", Monolog::DEBUG, true);
    }

    /**
     * Returns a Monolog\LineFormatter Instance configured like Laravel's default
     *
     * @return Monolog\LineFormatter
     */
    public function setupFormatter()
    {
        return new LineFormatter(null, null, true, true);
    }

    /**
     * Helper function to config the log level string
     * into the Monolog constant
     *
     * @param  string $level
     * @return const
     */
    public function getMonologLevel($level)
    {
        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }
        return $this->levels['debug'];
    }
}
