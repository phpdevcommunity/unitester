<?php

namespace PhpDevCommunity\UniTester\Console;

use const PHP_EOL;

final class Output
{
    /**
     * @var callable
     */
    private $output;

    const FOREGROUND_COLORS = [
        'black' => '0;30',
        'dark_gray' => '1;30',
        'green' => '0;32',
        'light_green' => '1;32',
        'red' => '0;31',
        'light_red' => '1;31',
        'yellow' => '0;33',
        'light_yellow' => '1;33',
        'blue' => '0;34',
        'dark_blue' => '0;34',
        'light_blue' => '1;34',
        'purple' => '0;35',
        'light_purple' => '1;35',
        'cyan' => '0;36',
        'light_cyan' => '1;36',
        'light_gray' => '0;37',
        'white' => '1;37',
    ];

    const BG_COLORS = [
        'black' => '40',
        'red' => '41',
        'green' => '42',
        'yellow' => '43',
        'blue' => '44',
        'magenta' => '45',
        'cyan' => '46',
        'light_gray' => '47',
    ];

    public function __construct(callable $output = null)
    {
        if ($output === null) {
            $output = function ($message) {
                fwrite(STDOUT, $message);
            };
        }
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $message, ?string $color = null, ?string $background = null): void
    {
        $formattedMessage = '';

        if ($color) {
            $formattedMessage .= "\033[" . self::FOREGROUND_COLORS[$color] . 'm';
        }
        if ($background) {
            $formattedMessage .= "\033[" . self::BG_COLORS[$background] . 'm';
        }

        if (!empty($formattedMessage)) {
            $formattedMessage .= $message . "\033[0m";
        } else {
            $formattedMessage = $message;
        }

        $output = $this->output;
        $output($formattedMessage);
    }

    public function writeln(string $message): void
    {
        $this->write($message);
        $this->write(PHP_EOL);
    }

    public function list(array $items): void
    {
        foreach ($items as $item) {
            $this->write('- ' . $item);
            $this->write(PHP_EOL);
        }
        $this->write(PHP_EOL);
    }

    public function listKeyValues(array $items, bool $inlined = false): void
    {
        $maxKeyLength = 0;
        if ($inlined) {
            foreach ($items as $key => $value) {
                $keyLength = mb_strlen($key);
                if ($keyLength > $maxKeyLength) {
                    $maxKeyLength = $keyLength;
                }
            }
        }

        foreach ($items as $key => $value) {
            $key = str_pad($key, $maxKeyLength, ' ', STR_PAD_RIGHT);
            $this->write($key, 'green');
            $this->write(' : ');
            $this->write($value, 'white');
            $this->write(PHP_EOL);
        }
        $this->write(PHP_EOL);
    }

    public function success(string $message): void
    {
        [$formattedMessage, $lineLength, $color] = $this->formatMessage('OK', $message, 'green');
        $this->outputMessage($formattedMessage, $lineLength, $color);
    }

    public function error(string $message): void
    {
        [$formattedMessage, $lineLength, $color] = $this->formatMessage('ERROR', $message, 'red');
        $this->outputMessage($formattedMessage, $lineLength, $color);
    }

    public function warning(string $message): void
    {
        [$formattedMessage, $lineLength, $color] = $this->formatMessage('WARNING', $message, 'yellow');
        $this->outputMessage($formattedMessage, $lineLength, $color);
    }

    public function info(string $message): void
    {
        [$formattedMessage, $lineLength, $color] = $this->formatMessage('INFO', $message, 'blue');
        $this->outputMessage($formattedMessage, $lineLength, $color);
    }

    private function outputMessage($formattedMessage, int $lineLength, string $color): void
    {
        $this->write(PHP_EOL);
        $this->write(str_repeat(' ', $lineLength), 'white', $color);
        $this->write(PHP_EOL);

        if (is_string($formattedMessage)) {
            $formattedMessage = [$formattedMessage];
        }

        foreach ($formattedMessage as $line) {
            $this->write($line, 'white', $color);
        }

        $this->write(PHP_EOL);
        $this->write(str_repeat(' ', $lineLength), 'white', $color);
        $this->write(PHP_EOL);
        $this->write(PHP_EOL);
    }

    private function formatMessage(string $prefix, string $message, string $color): array
    {
        $formattedMessage = sprintf('[%s] %s', $prefix, trim($message));
        $lineLength = mb_strlen($formattedMessage);
        $consoleWidth = $this->geTerminalWidth();

        if ($lineLength > $consoleWidth) {
            $lineLength = $consoleWidth;
            $lines = explode('|', wordwrap($formattedMessage, $lineLength, '|', true));
            $formattedMessage = array_map(function ($line) use ($lineLength) {
                return str_pad($line, $lineLength);
            }, $lines);
        }
        return [$formattedMessage, $lineLength, $color];
    }

    private function geTerminalWidth(): int
    {
        return ((int)exec('tput cols') ?? 85 - 5);
    }
}
