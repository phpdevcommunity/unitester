<?php

namespace PhpDevCommunity\UniTester;

use Exception;
use PhpDevCommunity\UniTester\Exception\AssertionFailureException;

function assert_equals($expected, $actual, string $message = ''): void
{
    if ($expected !== $actual) {
        throw new AssertionFailureException($message ?: "Expected '{$expected}', got '{$actual}'");
    }
}

function assert_not_equals($expected, $actual, string $message = ''): void
{
    if ($expected === $actual) {
        throw new AssertionFailureException($message ?: "Expected '{$expected}', got '{$actual}'");
    }
}

function assert_similar($expected, $actual, string $message = ''): void
{
    if ($expected != $actual) {
        throw new AssertionFailureException($message ?: "Expected similar '{$expected}', got '{$actual}'");
    }
}

function assert_true($condition, string $message = ''): void
{
    if (!$condition) {
        throw new AssertionFailureException($message ?: "Expected condition to be true, but it was false.");
    }
}
function assert_false($condition, string $message = ''): void
{
    if ($condition) {
        throw new AssertionFailureException($message ?: "Expected condition to be false, but it was true.");
    }
}

function assert_null($value, string $message = ''): void
{
    if (!is_null($value)) {
        throw new AssertionFailureException($message ?: "Expected value to be null, but it was not.");
    }
}

function assert_not_null($value, string $message = ''): void
{
    if (is_null($value)) {
        throw new AssertionFailureException($message ?: "Expected value to not be null, but it was.");
    }
}

function assert_empty($value, string $message = ''): void
{
    if (!empty($value)) {
        throw new AssertionFailureException($message ?: "Expected value to be an array, but it was not.");
    }
}
function assert_not_empty($value, string $message = ''): void
{
    if (empty($value)) {
        throw new AssertionFailureException($message ?: "Expected value to not be an array, but it was.");
    }
}

function assert_instanceof(string $expected, $actual, string $message = ''): void
{
    if (!is_object($actual)) {
        throw new AssertionFailureException($message ?: "Expected '{$expected}', got " . gettype($actual) . ".");
    }

    if (!is_a($actual, $expected)) {
        $type = get_class($actual);
        throw new AssertionFailureException($message ?: "Expected '{$expected}', got '{$type}'");
    }
}

function assert_string_length($string, int $length, string $message = ''): void
{
    if (!is_string($string)) {
        throw new AssertionFailureException($message ?: "Expected string, but got " . gettype($string) . ".");
    }

    if (strlen($string) !== $length) {
        throw new AssertionFailureException($message ?: "Expected string length of {$length}, but got " . strlen($string) . ".");
    }
}

function assert_string_contains($haystack, $needle, string $message = ''): void
{
    if (!is_string($haystack)) {
        throw new AssertionFailureException($message ?: "Expected string, but got " . gettype($haystack) . ".");
    }
    if (!is_string($needle)) {
        throw new AssertionFailureException($message ?: "Expected string, but got " . gettype($needle) . ".");
    }

    if (strpos($haystack, $needle) === false) {
        throw new AssertionFailureException($message ?: "Expected '{$haystack}' to contain '{$needle}'.");
    }
}

function assert_string_starts_with($haystack, $needle, string $message = ''): void
{
    if (!is_string($haystack)) {
        throw new AssertionFailureException($message ?: "Expected string, but got " . gettype($haystack) . ".");
    }
    if (!is_string($needle)) {
        throw new AssertionFailureException($message ?: "Expected string, but got " . gettype($needle) . ".");
    }

    if (strpos($haystack, $needle) !== 0) {
        throw new AssertionFailureException($message ?: "Expected '{$haystack}' to start with '{$needle}'.");
    }
}

function assert_string_ends_with($haystack, $needle, string $message = ''): void
{
    if (!is_string($haystack)) {
        throw new AssertionFailureException($message ?: "Expected string, but got " . gettype($haystack) . ".");
    }
    if (!is_string($needle)) {
        throw new AssertionFailureException($message ?: "Expected string, but got " . gettype($needle) . ".");
    }
    if (substr($haystack, -strlen($needle)) !== $needle) {
        throw new AssertionFailureException($message ?: "Expected '{$haystack}' to end with '{$needle}'.");
    }
}

function assert_positive_int($value, string $message = ''): void
{
    if (!is_int($value) || $value <= 0) {
        throw new AssertionFailureException($message ?: "Expected positive integer, but got " . (is_int($value) ? $value : gettype($value)) . ".");
    }
}

function assert_negative_int($value, string $message = ''): void
{
    if (!is_int($value) || $value >= 0) {
        throw new AssertionFailureException($message ?: "Expected negative integer, but got " . (is_int($value) ? $value : gettype($value)) . ".");
    }
}
