# PHP UniTester

**UniTester** is a unit testing library for PHP that provides a straightforward interface for writing and executing tests. It focuses on a minimalist approach, allowing developers to create assertions and organize tests at the lowest level of PHP, without reliance on complex external libraries.

## Installation

You can install this library via [Composer](https://getcomposer.org/). Ensure your project meets the minimum PHP version requirement of 7.4.

```bash
composer require phpdevcommunity/unitester
```
## Requirements

- PHP version 7.4 or higher
- Psr\Container 2.0 or higher

## Project Structure

Before getting started, make sure you have a `tests/` directory at the root of your project. This directory will contain all your test classes.

```
your-project/
│
├── src/          # Directory containing your source code
├── tests/        # Directory containing your test classes
│   ├── AssertionTest.php
│   └── AnotherTest.php
└── ...
```

## Basic Structure of a Test

To create tests with UniTester, you need to extend the `TestCase` class and implement the `setUp()`, `tearDown()`, and `execute()` methods.

### Example Usage:

```php
class AssertionTest extends TestCase
{
    protected function setUp(): void
    {
        // Initialize any necessary resources before each test
    }

    protected function tearDown(): void
    {
        // Release resources after each test
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
```

### Method Explanation:

- **setUp()**: This method is called before each test is executed. You can initialize any necessary resources for your tests here.

- **tearDown()**: This method is called after each test is executed. It allows you to release any resources used.

- **execute()**: This method should contain all the tests in the class. You can call as many test methods as needed.

### Creating Multiple Test Classes

You can create as many test classes as needed in the `tests/` directory, each following the same structure. Just make sure to extend the `TestCase` class and implement the required methods.

## Executing Tests

To run your tests using UniTester, navigate to the root of your project in the terminal and use the following command:

```bash
php vendor/bin/unitester tests/
```

### Command Explanation:

- **php**: This is the PHP command-line interface, which allows you to run PHP scripts from the terminal.


- **vendor/bin/unitester**: This path points to the UniTester executable. Ensure that UniTester is installed and available in your `vendor` directory.


- **tests/**: This specifies the directory where your test classes are located. UniTester will automatically find and execute all test classes in this directory.

### Output

After executing the command, you will see the results of your tests in the terminal, indicating which tests passed and which, if any, failed.
