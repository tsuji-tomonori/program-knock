<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Simple test runner
$passed = 0;
$failed = 0;

// Find all test files
$testFiles = glob(__DIR__ . '/*Test.php');

foreach ($testFiles as $testFile) {
    echo "\nRunning tests in " . basename($testFile) . ":\n";
    require_once $testFile;

    $className = 'ProgramKnock\\Tests\\' . str_replace('.php', '', basename($testFile));

    if (!class_exists($className)) {
        echo "  ❌ Class $className not found\n";
        $failed++;
        continue;
    }

    $reflection = new ReflectionClass($className);
    $instance = $reflection->newInstance();

    foreach ($reflection->getMethods() as $method) {
        if (strpos($method->getName(), 'test') === 0) {
            try {
                $method->invoke($instance);
                echo "  ✅ " . $method->getName() . "\n";
                $passed++;
            } catch (Exception $e) {
                echo "  ❌ " . $method->getName() . ": " . $e->getMessage() . "\n";
                $failed++;
            } catch (AssertionError $e) {
                echo "  ❌ " . $method->getName() . ": " . $e->getMessage() . "\n";
                $failed++;
            }
        }
    }
}

echo "\n========================================\n";
echo "Total: " . ($passed + $failed) . " tests\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";

exit($failed > 0 ? 1 : 0);
