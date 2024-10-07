<?php

namespace PhpDevCommunity\UniTester;

use Exception;
use LogicException;
use PhpDevCommunity\UniTester\Exception\AssertionFailureException;
use Psr\Container\ContainerInterface;
use Throwable;

abstract class TestCase
{
    private int $assertions = 0;
    private ?ContainerInterface $container;

    final public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    abstract protected function setUp(): void;

    abstract protected function tearDown(): void;

    abstract protected function execute(): void;

    final public function run(): void
    {
        $this->setUp();

        try {
            $this->execute();
        } finally {
            $this->tearDown();
        }
    }

    final public function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            throw new LogicException('Container not set in PhpDevCommunity\UniTester\TestExecutor.');
        }

        return $this->container;
    }

    final protected function assertStrictEquals($expected, $actual): void
    {
        assert_strict_equals($expected, $actual);
        $this->assertions++;
    }

    final protected function assertEquals($expected, $actual): void
    {
        assert_equals($expected, $actual);
        $this->assertions++;
    }

    final protected function assertNotStrictEquals($expected, $actual): void
    {
        assert_not_strict_equals($expected, $actual);
        $this->assertions++;
    }

    final protected function assertNotEquals($expected, $actual): void
    {
        assert_not_equals($expected, $actual);
        $this->assertions++;
    }

    final protected function assertSimilar($expected, $actual): void
    {
        assert_similar($expected, $actual);
        $this->assertions++;
    }

    final protected function assertTrue($condition): void
    {
        assert_true($condition);
        $this->assertions++;
    }

    final protected function assertFalse($condition): void
    {
        assert_false($condition);
        $this->assertions++;
    }

    final protected function assertNull($value): void
    {
        assert_null($value);
        $this->assertions++;
    }

    final protected function assertNotNull($value): void
    {
        assert_not_null($value);
        $this->assertions++;
    }

    final protected function assertEmpty($value): void
    {
        assert_empty($value);
        $this->assertions++;
    }

    final protected function assertNotEmpty($value): void
    {
        assert_not_empty($value);
        $this->assertions++;
    }

    final protected function assertInstanceOf(string $expected, $actual): void
    {
        assert_instanceof($expected, $actual);
        $this->assertions++;
    }


    final protected function assertStringLength($string, int $length): void
    {
        assert_string_length($string, $length);
        $this->assertions++;
    }

    final protected function assertStringContains(string $haystack, $needle): void
    {
        assert_string_contains($haystack, $needle);
        $this->assertions++;
    }

    final protected function assertStringStartsWith(string $haystack, $needle): void
    {
        assert_string_starts_with($haystack, $needle);
        $this->assertions++;
    }

    final protected function assertStringEndsWith(string $haystack, $needle): void
    {
        assert_string_ends_with($haystack, $needle);
        $this->assertions++;
    }

    final protected function assertPositiveInt($value): void
    {
        assert_positive_int($value);
        $this->assertions++;
    }

    final protected function assertNegativeInt($value): void
    {
        assert_negative_int($value);
        $this->assertions++;
    }

    final protected function expectException(string $exception, callable $callable, string $message = null): void
    {
        try {
            $callable();
            throw new AssertionFailureException('Expected exception not thrown. : ' . $exception);
        } catch (Throwable $e) {
            if ($e instanceof AssertionFailureException) {
                throw $e;
            }
            assert_equals($exception, get_class($e));
            if ($message !== null) {
                assert_equals($message, $e->getMessage());
            }
            $this->assertions++;
        }
    }


    final public function getAssertions(): int
    {
        return $this->assertions;
    }
}
