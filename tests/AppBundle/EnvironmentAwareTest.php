<?php

namespace AppBundle;

/**
 * @group unit
 */
class EnvironmentAwareTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        /** @var EnvironmentAware $trait */
        $trait = $this->getMockForTrait(EnvironmentAware::class);
        $trait->setEnvironment('test');

        $this->assertSame('test', $trait->getEnvironment());
        $this->assertTrue($trait->isTestEnvironment());
    }

}