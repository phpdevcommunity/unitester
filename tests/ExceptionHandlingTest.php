<?php

namespace Test\PhpDevCommunity\UniTester;

use PhpDevCommunity\UniTester\TestCase;

class ExceptionHandlingTest extends TestCase
{
    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
    }

    protected function execute(): void
    {
        $this->testExceptionThrown();
    }

    public function testExceptionThrown()
    {
        $this->expectException(\LogicException::class, function() {
            throw new \LogicException("Test exception");
        });
    }
}
