<?php

namespace Test\PhpDevCommunity\UniTester;

use PhpDevCommunity\UniTester\TestCase;

class AssertionTest extends TestCase
{
    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
    }

    protected function execute(): void
    {
        $this->testAssertTrue();
        $this->testAssertEquals();
    }

    public function testAssertTrue()
    {
        $this->assertTrue(true);
    }

    public function testAssertEquals()
    {
        $this->assertEquals(10, 5 + 5);
    }
}
