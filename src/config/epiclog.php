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
     */
    'rotate_log_by_level' => true,

    /**
     * Specify the number of days of logs to keep for the log level files.
     */
    'rotate_log_by_level_num_days' => 5,

    /**
     * Allows creation of custom logs
     */
    'logs' => []
];
