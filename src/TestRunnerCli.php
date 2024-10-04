<?php

namespace PhpDevCommunity\UniTester;

use InvalidArgumentException;
use LogicException;
use PhpDevCommunity\UniTester\Console\ArgParser;
use PhpDevCommunity\UniTester\Console\Output;
use Throwable;

final class TestRunnerCli
{
    const VERSION = '0.1.0';

    const CLI_ERROR = 1;
    const CLI_SUCCESS = 0;

    public static function run(Output $output, array $args = null): int
    {
        if (php_sapi_name() !== 'cli') {
            throw new LogicException('This script can only be run from the command line.');
        }

        set_time_limit(0);

        try {
            $args = $args ?? $_SERVER['argv'] ?? [];
            $arg = new ArgParser($args);
            foreach ($arg->getOptions() as $name => $value) {
                if (!in_array($name, self::allowedOptions(), true)) {
                    throw new InvalidArgumentException('Invalid option: ' . $name);
                }
            }

            if ($arg->hasOption('help')) {
                self::printUsage($output);
                return self::CLI_SUCCESS;
            }

            if ($arg->hasOption('version')) {
                $output->writeln(sprintf('PHP UniTester version %s', self::VERSION));
                return self::CLI_SUCCESS;
            }

            if (count($arg->getArguments()) === 0) {
                self::printUsage($output);
                return self::CLI_SUCCESS;
            }

            if (count($arg->getArguments()) > 1) {
                throw new InvalidArgumentException('Too many arguments, only one allowed.');
            }

            $dir = $arg->getArgumentValue(0);
            if (!(strncmp($dir, DIRECTORY_SEPARATOR, strlen(DIRECTORY_SEPARATOR)) === 0)) {
                $dir = getcwd() . DIRECTORY_SEPARATOR . $dir;
            }

            $testFinder = new TestFinder($dir);
            $testExecutor = new TestExecutor($testFinder->find(), $output);
            return $testExecutor->run() === 0 ? self::CLI_SUCCESS : self::CLI_ERROR;

        } catch (Throwable $e) {
            $output->error($e->getMessage());
            return self::CLI_ERROR;
        }
    }


    private static function printUsage(Output $output): void
    {
        $output->writeln('Usage PHP UniTester : [options] [folder]');
        $output->writeln('Options:');
        $output->writeln('  --help       Show this help message');
        $output->writeln('  --version    Show the version number');

        $output->writeln('Examples:');
        $output->writeln('  Run all tests in tests folder : bin/unitester tests/');
        $output->writeln('  Display help                  : bin/unitester --help');
        $output->writeln('  Show version                  : bin/unitester --version');
    }

    private static function allowedOptions(): array
    {
        return ['help', 'version'];
    }
}
