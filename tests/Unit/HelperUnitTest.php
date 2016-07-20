<?php

namespace EpicLog\Tests\Unit;

use EpicLog\Tests\TestingBase;

class HelperUnitTest extends TestingBase
{
    /** @test */
    public function setupRotatingLogReturnsRotatingFileHandlerClass()
    {
        $helper = new \EpicLog\Helper();
        
        $result = $helper->setupRotatingLog('myTestLog', \Monolog\Logger::DEBUG);
        $this->assertInstanceOf('\Monolog\Handler\RotatingFileHandler', $result);
    }
}
