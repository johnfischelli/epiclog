<?php
return [
    /**
     * If this flag is true, we alter the Laravel default Monolog config
     * so that each log level is written to its own file. If it is false,
     * the default Laravel Monolog config is untouched.
     */
    'separate_logs_by_level' => true,

    /**
     * If this flag is true, we'll setup a RotatingFileHandler instead of a
     * default StreamHandler for each of our logs.
     * NOTE: This only applies if the separate_logs_by_level parameter is
     * also true. This also, only applies to the logs separated by level.
     */
    'rotate_log_by_level' => true,

    /**
     * Specify the number of days of logs to keep for the log level files.
     * NOTE: If rotate_log_by_level is false, it doesn't matter what you
     * put here - all logs will be kept indefinitely.
     */
    'rotate_log_by_level_num_days' => 5,

    /**
     * Set this flag to true, if you'd like to push error level and above logs
     * to stderr. This is especially useful if you run a docker environment.
     */
    'push_errors_to_stderr' => false,

    /**
     * Set this flag to true, if you'd like to push all logs
     * to stdout. This is especially useful if you run a docker environment.
     */
    'push_logs_to_stdout' => false,

    /**
     * Allows creation of custom logs.
     *
     * All settings except 'name' are optional. Here are the default settings
     * for each log you specify.
     *
     * [
     *     'location' => storage_path("logs/epiclog.log"), // location of actual log file
     *     'level' => 'debug', // log level this log will be written as
     *     'rotate' => false, // whether or not to rotate these log files daily
     *     'num_days' => 5, // number of days of logs to retain
     *     'bubbles' => false // whether or not records handled by this logger will propogate to other handlers
     * ]
     *
     * Here is a complete example of a valid log configuration:
     *
     * [
     *     'name' => 'myCustomLog',
     *     'location' => storage_path("logs/myCustomLog.log"),
     *     'level' => 'error',
     *     'rotate' => true,
     *     'num_days' => 3,
     *     'bubbles' => true
     * ]
     *
     * You can access any custom logs you create here through the Epiclog:: facade.
     * In the complete example above you would use Epiclog::myCustomLog('data to log');
     */
    'logs' => []
];
