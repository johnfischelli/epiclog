<?php
return [
    /**
     * If this flag is true, we alter the Laravel default Monolog config
     * so that each log level is written to its own file. If it is false,
     * the default Laravel Monolog config is untouched.
     */
    'separate_logs_by_level' => true,

    /**
     * Allows creation of custom logs
     */
    'logs' => []
];
