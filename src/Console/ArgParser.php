<?php

namespace PhpDevCommunity\UniTester\Console;

use InvalidArgumentException;
use function strlen;
use function strncmp;

final class ArgParser
{
    private array $options = [];
    private array $arguments = [];

    public function __construct(array $argv)
    {
        array_shift($argv);
        $ignoreKeys = [];
        foreach ($argv as $key => $value) {
            if (in_array($key, $ignoreKeys, true)) {
                continue;
            }

            if (self::startsWith($value, '--')) {
                $it = explode("=", ltrim($value, '-'), 2);
                $optionName = $it[0];
                $optionValue = $it[1] ?? true;
                $this->options[$optionName] = $optionValue;
            } elseif (self::startsWith($value, '-')) {
                $optionName = ltrim($value, '-');
                if (strlen($optionName) > 1) {
                    $options = str_split($optionName);
                    foreach ($options as $option) {
                        $this->options[$option] = true;
                    }
                } else {
                    $this->options[$optionName] = true;
                    if (isset($argv[$key + 1]) && !self::startsWith($argv[$key + 1], '-')) {
                        $ignoreKeys[] = $key + 1;
                        $this->options[$optionName] = $argv[$key + 1];
                    }
                }
            } else {
                $this->arguments[] = $value;
            }
        }
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function hasOption(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }

    public function getOptionValue(string $name)
    {
        if (!$this->hasOption($name)) {
            throw new InvalidArgumentException(sprintf('Option "%s" is not defined.', $name));
        }
        return $this->options[$name];
    }

    public function getArgumentValue(int $key)
    {
        if (!$this->hasArgument($key)) {
            throw new InvalidArgumentException(sprintf('Argument "%s" is not defined.', $key));
        }
        return $this->arguments[$key];
    }

    public function hasArgument(int $key): bool
    {
        return array_key_exists($key, $this->arguments);
    }

    private static function startsWith(string $haystack, string $needle): bool
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
