<?php

namespace EpicLog;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class Helper
{
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
            storage_path("/logs/{$filename}.log"),
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
            storage_path("/logs/{$filename}.log"),
            $level,
            false
        );
    }

    /**
     * Returns a StreamHandler that pushes messages to stderr
     *
     * @return \Monolog\StreamHandler
     */
    public function setupStdErrHandler()
    {
        return new StreamHandler("php://stderr", Logger::ERROR, true);
    }

    /**
     * Returns a StreamHandler that pushes messages to stdout
     *
     * @return \Monolog\StreamHandler
     */
    public function setupStdOutHandler($level)
    {
        return new StreamHandler("php://stdout", Logger::DEBUG, true);
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
}
