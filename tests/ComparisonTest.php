<?php

namespace Test\PhpDevCommunity\UniTester;

use PhpDevCommunity\UniTester\TestCase;

class ComparisonTest extends TestCase
{
    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
    }

    protected function execute(): void
    {
        $this->testAssertNotEquals();
        $this->testAssertNull();
    }

    public function testAssertNotEquals()
    {
        $this->assertNotEquals(10, 20);
    }

    public function testAssertNull()
    {
        $this->assertNull(null); // Test que la valeur est null
    }

}
