<?php

namespace EpicLog\Tests\Unit;

use EpicLog\Tests\TestingBase;

class HelperUnitTest extends TestingBase
{
    /** @test */
    public function setupRotatingLogReturnsRotatingFileHandlerClass()
    {
        $helper = new \EpicLog\Helper();
        
        $result = $helper->setupRotatingLog('myTestLog', $helper->levels['debug']);
        $this->assertInstanceOf('\Monolog\Handler\RotatingFileHandler', $result);
    }

    /** @test */
    public function setupNormalLogReturnsStreamHandlerClass()
    {
        $helper = new \EpicLog\Helper();
        
        $result = $helper->setupRotatingLog('myTestLog', $helper->levels['debug']);
        $this->assertInstanceOf('\Monolog\Handler\StreamHandler', $result);
    }

    /** @test */
    public function setupLogByConfigReturnsStreamHandlerClass()
    {
        $helper = new \EpicLog\Helper();

        $config = [
            'name' => 'myTestLog'
        ];

        $config = array_merge($helper->defaultConfig, $config);
        
        $result = $helper->setupLogByConfig($config);
        $this->assertInstanceOf('\Monolog\Handler\StreamHandler', $result);
    }
}
