<?php

namespace EpicLog\Tests\Unit;

use EpicLog\Tests\TestingBase;

class HelperUnitTest extends TestingBase
{
    public function setUp()
    {
        parent::setUp();
        $this->helper = new \EpicLog\Helper();
    }

    /** @test */
    public function canBootUpHelperClass()
    {
        $this->assertInstanceOf('\EpicLog\Helper', $this->helper);
    }

    /** @test */
    public function setupRotatingLogReturnsRotatingFileHandlerClass()
    {
        $result = $this->helper->setupRotatingLog('myTestLog', $this->helper->levels['debug']);
        $this->assertInstanceOf('\Monolog\Handler\RotatingFileHandler', $result);
    }

    /** @test */
    public function setupNormalLogReturnsStreamHandlerClass()
    {
        $result = $this->helper->setupRotatingLog('myTestLog', $this->helper->levels['debug']);
        $this->assertInstanceOf('\Monolog\Handler\StreamHandler', $result);
    }

    /** @test */
    public function setupLogByConfigReturnsStreamHandlerClass()
    {
        $config = [
            'name' => 'myTestLog'
        ];

        $config = array_merge($this->helper->defaultConfig, $config);
        
        $result = $this->helper->setupLogByConfig($config);
        $this->assertInstanceOf('\Monolog\Handler\StreamHandler', $result);
    }

    /** @test */
    public function setupStdErrHandlerReturnsStreamHandlerClass()
    {
        $result = $this->helper->setupStdErrHandler();
        $this->assertInstanceOf('\Monolog\Handler\StreamHandler', $result);
    }

    /** @test */
    public function setupStdOutHandlerReturnsStreamHandlerClass()
    {
        $result = $this->helper->setupStdOutHandler();
        $this->assertInstanceOf('\Monolog\Handler\StreamHandler', $result);
    }

    /** @test */
    public function setupFormatterReturnsLineFormatterClass()
    {
        $result = $this->helper->setupFormatter();
        $this->assertInstanceOf('\Monolog\Formatter\LineFormatter', $result);
    }

    /** @test */
    public function getMonologReturnsMonologConst()
    {
        $result = $this->helper->getMonologLevel('error');
        $this->assertEquals($result, 400);
    }

    /** @test */
    public function getMonologReturnsDebugMonologConstByDefault()
    {
        $result = $this->helper->getMonologLevel('not-a-log-level');
        $this->assertEquals($result, 100);
    }
}
