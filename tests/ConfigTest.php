<?php

namespace pxgamer\LogBrowserChecker;

use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 */
class ConfigTest extends TestCase
{
    public function testCanGetConfigValue()
    {
        $config = new Config([
            'test' => 'value',
        ]);

        $this->assertEquals('value', $config->getValue('test'));
    }
}
