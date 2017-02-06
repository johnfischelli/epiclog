<?php

namespace EpicLog;

use Monolog\Logger;

class CustomLogger
{
    /**
     * Holds all the custom Channels configured in
     * config/epiclog.php
     *
     * @var array
     */
    private $channels;

    /**
     * Default Configuration for a custom log
     * Gets merged and overwritten with config defined
     * in config/epiclog.php
     *
     * @var array
     */
    private $defaultConfig;

    /**
     * Contains helper methods for interacting with Monolog
     *
     * @var Helper
     */
    private $helper;

    /**
     * Constructor
     *
     * @param \EpicLog\helper $helper
     */
    public function __construct(Helper $helper)
    {
        $this->channels = [];

        $this->defaultConfig = [
            'location' => storage_path("logs/epiclog.log"),
            'level' => 'debug',
            'rotate' => false,
            'num_days' => 5,
            'bubbles' => false
        ];

        $this->helper = $helper;
    }

    /**
     * [setupChannels description]
     * @param  array  $logs [description]
     * @return [type]       [description]
     */
    public function setupChannels(array $logs)
    {
        foreach ($logs as &$config) {
            if (!is_array($config) && !isset($config['name'])) {
                // throw exception
                continue;
            }

            // generate a handler class from the configuration array
            $handler = $this->helper->setupLogByConfig(
                array_merge(
                    $this->defaultConfig,
                    $config
                )
            );

            // set the formatter on the handler class
            $handler->setFormatter($this->helper->setupFormatter());

            // boot up a new monologger on the channel specified by the config name
            $logger = new Logger($config['name']);
            // push the new handler onto this new channel
            $logger->pushHandler($handler);

            // save the channel so that we can access it later
            $this->channels[$config['name']] = $logger;
        }
    }

    /**
     * [__call description]
     * @param  [type] $name      [description]
     * @param  [type] $arguments [description]
     * @return [type]            [description]
     */
    public function __call($name, $arguments)
    {
        if (isset($this->channels[$name])) {
            $logger = $this->channels[$name];
            $logger->addInfo($arguments[0], $arguments[1]);
        }

        // throw exception optionally
        return false;
    }
}
