<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\FloodFill;

class FloodFillTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected " . json_encode($expected) . ", but got " . json_encode($actual));
        }
    }

    private function assertTrue($condition, $message = ''): void
    {
        if (!$condition) {
            throw new \AssertionError($message ?: "Expected true, but got false");
        }
    }

    private function assertFalse($condition, $message = ''): void
    {
        if ($condition) {
            throw new \AssertionError($message ?: "Expected false, but got true");
        }
    }

    public function testSampleCase1(): void
    {
        $image = [
            [1, 1, 0],
            [1, 0, 1],
            [0, 1, 1]
        ];
        $result = FloodFill::floodFill($image, 1, 2, 0);
        $expected = [
            [1, 1, 0],
            [1, 0, 0],
            [0, 0, 0]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase2(): void
    {
        $image = [
            [1, 1, 1],
            [1, 1, 0],
            [1, 0, 1]
        ];
        $result = FloodFill::floodFill($image, 1, 1, 2);
        $expected = [
            [2, 2, 2],
            [2, 2, 0],
            [2, 0, 1]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase3(): void
    {
        $image = [
            [0, 0, 0],
            [0, 1, 1]
        ];
        $result = FloodFill::floodFill($image, 1, 1, 1);
        $expected = [
            [0, 0, 0],
            [0, 1, 1]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testSinglePixel(): void
    {
        $image = [[1]];
        $result = FloodFill::floodFill($image, 0, 0, 0);
        $expected = [[0]];
        $this->assertEquals($expected, $result);
    }

    public function testSinglePixelNoChange(): void
    {
        $image = [[1]];
        $result = FloodFill::floodFill($image, 0, 0, 1);
        $expected = [[1]];
        $this->assertEquals($expected, $result);
    }

    public function testTwoByTwo(): void
    {
        $image = [
            [1, 0],
            [0, 1]
        ];
        $result = FloodFill::floodFill($image, 0, 0, 2);
        $expected = [
            [2, 0],
            [0, 1]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testEntireGridSameColor(): void
    {
        $image = [
            [1, 1, 1],
            [1, 1, 1],
            [1, 1, 1]
        ];
        $result = FloodFill::floodFill($image, 1, 1, 0);
        $expected = [
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testCheckboardPattern(): void
    {
        $image = [
            [1, 0, 1],
            [0, 1, 0],
            [1, 0, 1]
        ];
        $result = FloodFill::floodFill($image, 0, 0, 2);
        $expected = [
            [2, 0, 1],
            [0, 1, 0],
            [1, 0, 1]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testLShapePattern(): void
    {
        $image = [
            [1, 1, 0],
            [1, 0, 0],
            [1, 0, 0]
        ];
        $result = FloodFill::floodFill($image, 0, 0, 2);
        $expected = [
            [2, 2, 0],
            [2, 0, 0],
            [2, 0, 0]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testOutOfBounds(): void
    {
        $image = [
            [1, 1, 0],
            [1, 0, 1]
        ];
        // Test negative coordinates
        $result = FloodFill::floodFill($image, -1, 0, 2);
        $this->assertEquals($image, $result);

        // Test coordinates too large
        $result = FloodFill::floodFill($image, 5, 0, 2);
        $this->assertEquals($image, $result);

        $result = FloodFill::floodFill($image, 0, 5, 2);
        $this->assertEquals($image, $result);
    }

    public function testEmptyImage(): void
    {
        $image = [];
        $result = FloodFill::floodFill($image, 0, 0, 1);
        $this->assertEquals($image, $result);
    }

    public function testEmptyRow(): void
    {
        $image = [[]];
        $result = FloodFill::floodFill($image, 0, 0, 1);
        $this->assertEquals($image, $result);
    }

    public function testIterativeVsRecursive(): void
    {
        $testCases = [
            [
                [
                    [1, 1, 0],
                    [1, 0, 1],
                    [0, 1, 1]
                ],
                1, 2, 0
            ],
            [
                [
                    [1, 1, 1],
                    [1, 1, 0],
                    [1, 0, 1]
                ],
                1, 1, 2
            ],
            [
                [
                    [0, 0, 0],
                    [0, 1, 1]
                ],
                1, 1, 1
            ]
        ];

        foreach ($testCases as [$image, $sr, $sc, $newColor]) {
            $recursiveResult = FloodFill::floodFill($image, $sr, $sc, $newColor);
            $iterativeResult = FloodFill::floodFillIterative($image, $sr, $sc, $newColor);

            $this->assertEquals($recursiveResult, $iterativeResult, "Recursive and iterative results differ");
        }
    }

    public function testImageToString(): void
    {
        $image = [
            [1, 0, 1],
            [0, 1, 0]
        ];
        $result = FloodFill::imageToString($image);
        $expected = "1 0 1\n0 1 0";
        $this->assertEquals($expected, $result);
    }

    public function testGetImageSize(): void
    {
        $image = [
            [1, 0, 1],
            [0, 1, 0]
        ];
        $size = FloodFill::getImageSize($image);
        $expected = ['rows' => 2, 'cols' => 3];
        $this->assertEquals($expected, $size);

        $emptyImage = [];
        $size = FloodFill::getImageSize($emptyImage);
        $expected = ['rows' => 0, 'cols' => 0];
        $this->assertEquals($expected, $size);
    }

    public function testValidateImage(): void
    {
        // Valid images
        $this->assertTrue(FloodFill::validateImage([[1, 0], [0, 1]]));
        $this->assertTrue(FloodFill::validateImage([[1]]));
        $this->assertTrue(FloodFill::validateImage([[0, 0, 0], [1, 1, 1]]));

        // Invalid images
        $this->assertFalse(FloodFill::validateImage([])); // Empty
        $this->assertFalse(FloodFill::validateImage([[1, 0], [0]])); // Inconsistent row lengths
        $this->assertFalse(FloodFill::validateImage([[1, 2], [0, 1]])); // Invalid value (2)
        $this->assertFalse(FloodFill::validateImage([["1", "0"], ["0", "1"]])); // String values
    }

    public function testValidateCoordinates(): void
    {
        $image = [
            [1, 0, 1],
            [0, 1, 0]
        ];

        // Valid coordinates
        $this->assertTrue(FloodFill::validateCoordinates($image, 0, 0));
        $this->assertTrue(FloodFill::validateCoordinates($image, 1, 2));
        $this->assertTrue(FloodFill::validateCoordinates($image, 0, 2));

        // Invalid coordinates
        $this->assertFalse(FloodFill::validateCoordinates($image, -1, 0));
        $this->assertFalse(FloodFill::validateCoordinates($image, 0, -1));
        $this->assertFalse(FloodFill::validateCoordinates($image, 2, 0)); // Row out of bounds
        $this->assertFalse(FloodFill::validateCoordinates($image, 0, 3)); // Col out of bounds

        // Empty image
        $this->assertFalse(FloodFill::validateCoordinates([], 0, 0));
        $this->assertFalse(FloodFill::validateCoordinates([[]], 0, 0));
    }

    public function testCalculateFillableArea(): void
    {
        $image = [
            [1, 1, 0],
            [1, 0, 1],
            [0, 1, 1]
        ];

        $this->assertEquals(3, FloodFill::calculateFillableArea($image, 0, 0)); // Top-left group of 1s: (0,0), (0,1), (1,0)
        $this->assertEquals(3, FloodFill::calculateFillableArea($image, 1, 2)); // Right group of 1s: (1,2), (2,2), (2,1)
        $this->assertEquals(1, FloodFill::calculateFillableArea($image, 0, 2)); // Single 0 at (0,2)

        // Out of bounds
        $this->assertEquals(0, FloodFill::calculateFillableArea($image, -1, 0));
        $this->assertEquals(0, FloodFill::calculateFillableArea($image, 5, 0));
    }

    public function testFloodFillMultiple(): void
    {
        $image = [
            [1, 1, 0],
            [1, 0, 1],
            [0, 1, 1]
        ];

        $operations = [
            [0, 0, 2], // Fill top-left 1s with 2
            [1, 2, 0], // Fill bottom-right 1s with 0
        ];

        $result = FloodFill::floodFillMultiple($image, $operations);
        $expected = [
            [2, 2, 0],
            [2, 0, 0],
            [0, 0, 0]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testLargeImage(): void
    {
        // Create a 10x10 image with all 1s
        $image = [];
        for ($i = 0; $i < 10; $i++) {
            $image[$i] = array_fill(0, 10, 1);
        }

        $result = FloodFill::floodFill($image, 5, 5, 0);

        // Should fill entire image with 0s
        $expected = [];
        for ($i = 0; $i < 10; $i++) {
            $expected[$i] = array_fill(0, 10, 0);
        }

        $this->assertEquals($expected, $result);
        $this->assertEquals(100, FloodFill::calculateFillableArea($image, 5, 5));
    }

    public function testComplexPattern(): void
    {
        $image = [
            [1, 1, 0, 0, 1],
            [1, 0, 0, 1, 1],
            [0, 0, 1, 1, 0],
            [1, 1, 1, 0, 0],
            [1, 0, 0, 0, 1]
        ];

        // Fill the group of 0s starting from (1,2)
        $result = FloodFill::floodFill($image, 1, 2, 2);
        $expected = [
            [1, 1, 2, 2, 1],
            [1, 2, 2, 1, 1],
            [2, 2, 1, 1, 0],
            [1, 1, 1, 0, 0],
            [1, 0, 0, 0, 1]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testBorderCells(): void
    {
        $image = [
            [1, 0, 1],
            [0, 1, 0],
            [1, 0, 1]
        ];

        // Test all border cells
        $borderTests = [
            [0, 0, 2], // Top-left
            [0, 2, 2], // Top-right
            [2, 0, 2], // Bottom-left
            [2, 2, 2], // Bottom-right
            [0, 1, 2], // Top-center
            [1, 0, 2], // Left-center
            [1, 2, 2], // Right-center
            [2, 1, 2], // Bottom-center
        ];

        foreach ($borderTests as [$sr, $sc, $newColor]) {
            $result = FloodFill::floodFill($image, $sr, $sc, $newColor);

            // Should only affect the single cell (since it's a checkerboard)
            $expected = $image;
            $expected[$sr][$sc] = $newColor;

            $this->assertEquals($expected, $result, "Failed for border cell ($sr, $sc)");
        }
    }

    public function testPerformanceTest(): void
    {
        // Create a large connected region
        $size = 50;
        $image = [];
        for ($i = 0; $i < $size; $i++) {
            $image[$i] = array_fill(0, $size, 1);
        }

        $startTime = microtime(true);
        $result = FloodFill::floodFillIterative($image, 25, 25, 0);
        $endTime = microtime(true);

        // Performance check
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        // Verify result
        $expected = [];
        for ($i = 0; $i < $size; $i++) {
            $expected[$i] = array_fill(0, $size, 0);
        }

        $this->assertEquals($expected, $result);
    }

    public function testRandomPatterns(): void
    {
        for ($i = 0; $i < 10; $i++) {
            // Generate random 5x5 image
            $image = [];
            for ($row = 0; $row < 5; $row++) {
                $image[$row] = [];
                for ($col = 0; $col < 5; $col++) {
                    $image[$row][$col] = rand(0, 1);
                }
            }

            $sr = rand(0, 4);
            $sc = rand(0, 4);
            $newColor = rand(0, 1);

            $recursiveResult = FloodFill::floodFill($image, $sr, $sc, $newColor);
            $iterativeResult = FloodFill::floodFillIterative($image, $sr, $sc, $newColor);

            $this->assertEquals($recursiveResult, $iterativeResult, "Random test $i failed");

            // Basic sanity checks
            $this->assertTrue(FloodFill::validateImage($recursiveResult));
            $this->assertEquals(FloodFill::getImageSize($image), FloodFill::getImageSize($recursiveResult));
        }
    }

    public function testSpecialColors(): void
    {
        $image = [
            [0, 0, 1],
            [0, 1, 1],
            [1, 1, 0]
        ];

        // Test filling with same color as original
        $result = FloodFill::floodFill($image, 0, 0, 0);
        $this->assertEquals($image, $result); // No change expected

        // Test different new colors (only connected 0s will be filled)
        $result1 = FloodFill::floodFill($image, 0, 0, 1);
        $expected1 = [
            [1, 1, 1],
            [1, 1, 1],
            [1, 1, 0]  // This 0 is not connected to the starting group
        ];
        $this->assertEquals($expected1, $result1);
    }

    public function testConsistencyCheck(): void
    {
        $image = [
            [1, 1, 0],
            [1, 0, 1],
            [0, 1, 1]
        ];

        // Multiple calls should return same result
        for ($i = 0; $i < 5; $i++) {
            $result = FloodFill::floodFill($image, 1, 2, 0);
            $expected = [
                [1, 1, 0],
                [1, 0, 0],
                [0, 0, 0]
            ];
            $this->assertEquals($expected, $result);
        }
    }

    public function testMaxSizeImage(): void
    {
        // Test constraint limits (50x50)
        $image = [];
        for ($i = 0; $i < 50; $i++) {
            $image[$i] = array_fill(0, 50, ($i + rand(0, 1)) % 2);
        }

        // Should handle without issues
        $result = FloodFill::floodFill($image, 25, 25, ($image[25][25] + 1) % 2);

        $this->assertTrue(FloodFill::validateImage($result));
        $this->assertEquals(['rows' => 50, 'cols' => 50], FloodFill::getImageSize($result));
    }
}
