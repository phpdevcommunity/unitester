<?php

namespace PhpDevCommunity\UniTester;

use PhpDevCommunity\Unitester\Console\Output;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use LogicException;
use Throwable;

final class TestExecutor
{
    const TEST_PASSED = 0;
    const TEST_FAILED = 1;
    private array $testClasses = [];
    private Output $output;
    private ?ContainerInterface $container;

    public function __construct(array $testClasses, Output $output, ?ContainerInterface $container = null)
    {
        $this->testClasses = $testClasses;
        $this->container = $container;
        $this->output = $output;
    }

    public function run(): int
    {
        $this->output->writeln('Running ' . count($this->testClasses) . ' tests...');
        $this->output->writeln('Press Ctrl+C to stop...');
        $this->output->writeln('');

        $passedTests = 0;
        $failedTests = 0;
        $assertions = 0;
        foreach ($this->testClasses as $class) {
            try {
                $testCase = $this->resolveClassName($class);
                $testCase->run();
                $passedTests++;
                $assertions += $testCase->getAssertions();
                $this->logSuccess($testCase);
            } catch (Throwable $e) {
                $failedTests++;
                $this->logFailure($class, $e->getMessage());
            }

        }

        $this->output->writeln('');
        $this->printSummary($passedTests, $failedTests, $assertions);
        if ($failedTests == 0) {
            $this->output->success('All tests passed.');
            return self::TEST_PASSED;
        }
        $this->output->error( $failedTests . ' tests failed.');
        return self::TEST_FAILED;
    }

    /**
     * @param string|object $className
     * @return TestCase
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function resolveClassName($className): TestCase
    {
        if (!is_subclass_of($className, TestCase::class)) {
            throw new LogicException("Test class $className is not a subclass of TestCase.");
        }

        if (is_object($className)) {
            return $className;
        }

        if (!$this->container instanceof ContainerInterface) {
            return new $className();
        }

        return $this->container->get($className);
    }

    private function logSuccess(object $testCase): void
    {
        $testName = get_class($testCase);
        $this->output->write("✔ $testName PASSED", 'green');
        $this->output->write(PHP_EOL);
    }

    private function logFailure($testCase, string $message): void
    {
        $testName = is_object($testCase) ? get_class($testCase) : $testCase;
        $this->output->write("✘ $testName FAILED : $message", 'red');
        $this->output->write(PHP_EOL);
    }

    private function printSummary(int $passedTests = 0, int $failedTests = 0, int $assertions = 0): void
    {
        $totalTests = $passedTests + $failedTests;
        $this->output->listKeyValues([
            'Total Tests' => $totalTests,
            'Passed' => $passedTests,
            'Failed' => $failedTests,
            'Assertions' => $assertions,
        ]);
    }

}
