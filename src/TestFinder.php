<?php

namespace PhpDevCommunity\UniTester;

final class TestFinder
{
    private string $directory;
    public function __construct(string $directory)
    {
        if (!is_dir($directory)) {
            throw new \InvalidArgumentException("Directory '{$directory}' does not exist.");
        }

        $this->directory = $directory;
    }

    public function find(): array
    {
        return $this->findTestClasses();
    }

    private function findTestClasses(): array
    {
        $testClasses = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->directory));
        foreach ($iterator as $file) {
            if ($file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }

            $class = self::extractNamespaceAndClass($file->getPathname());
            if (class_exists($class) && is_subclass_of($class, TestCase::class)) {
                $testClasses[] = $class;
            }
        }
        return $testClasses;
    }

    private static function extractNamespaceAndClass(string $filePath): string
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException('File not found: ' . $filePath);
        }

        $contents = file_get_contents($filePath);
        $namespace = '';
        $class = '';
        $isExtractingNamespace = false;
        $isExtractingClass = false;

        foreach (token_get_all($contents) as $token) {
            if (is_array($token) && $token[0] == T_NAMESPACE) {
                $isExtractingNamespace = true;
            }

            if (is_array($token) && $token[0] == T_CLASS) {
                $isExtractingClass = true;
            }

            if ($isExtractingNamespace) {
                if (is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {
                    $namespace .= $token[1];
                } else if ($token === ';') {
                    $isExtractingNamespace = false;
                }
            }

            if ($isExtractingClass) {
                if (is_array($token) && $token[0] == T_STRING) {
                    $class = $token[1];
                    break;
                }
            }
        }
        return $namespace ? $namespace . '\\' . $class : $class;
    }

}
