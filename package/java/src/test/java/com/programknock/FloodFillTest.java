package com.programknock;

import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class FloodFillTest {

    @Test
    void testSample1() {
        int[][] image = {{1, 1, 0}, {1, 0, 1}, {0, 1, 1}};
        int sr = 1, sc = 2, newColor = 0;
        int[][] result = FloodFill.floodFill(image, sr, sc, newColor);
        int[][] expected = {{1, 1, 0}, {1, 0, 0}, {0, 0, 0}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testSample2() {
        int[][] image = {{1, 1, 1}, {1, 1, 0}, {1, 0, 1}};
        int sr = 1, sc = 1, newColor = 2;
        int[][] result = FloodFill.floodFill(image, sr, sc, newColor);
        int[][] expected = {{2, 2, 2}, {2, 2, 0}, {2, 0, 1}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testSample3() {
        int[][] image = {{0, 0, 0}, {0, 1, 1}};
        int sr = 1, sc = 1, newColor = 1;
        int[][] result = FloodFill.floodFill(image, sr, sc, newColor);
        int[][] expected = {{0, 0, 0}, {0, 1, 1}}; // No change
        assertArrayEquals(expected, result);
    }

    @Test
    void testSingleCell() {
        int[][] image = {{1}};
        int[][] result = FloodFill.floodFill(image, 0, 0, 0);
        int[][] expected = {{0}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testSingleCellNoChange() {
        int[][] image = {{1}};
        int[][] result = FloodFill.floodFill(image, 0, 0, 1);
        int[][] expected = {{1}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testFillEntireGrid() {
        int[][] image = {{1, 1, 1}, {1, 1, 1}, {1, 1, 1}};
        int[][] result = FloodFill.floodFill(image, 1, 1, 0);
        int[][] expected = {{0, 0, 0}, {0, 0, 0}, {0, 0, 0}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testNoConnectedRegion() {
        int[][] image = {{0, 1, 0}, {1, 0, 1}, {0, 1, 0}};
        int[][] result = FloodFill.floodFill(image, 1, 1, 1);
        int[][] expected = {{0, 1, 0}, {1, 1, 1}, {0, 1, 0}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testCornerCell() {
        int[][] image = {{1, 0, 0}, {0, 0, 0}, {0, 0, 1}};
        int[][] result = FloodFill.floodFill(image, 0, 0, 2);
        int[][] expected = {{2, 0, 0}, {0, 0, 0}, {0, 0, 1}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testLShapedRegion() {
        int[][] image = {{1, 1, 0}, {1, 0, 0}, {1, 0, 1}};
        int[][] result = FloodFill.floodFill(image, 0, 0, 2);
        int[][] expected = {{2, 2, 0}, {2, 0, 0}, {2, 0, 1}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testComplexPattern() {
        int[][] image = {{1, 0, 1, 1}, {0, 1, 1, 0}, {1, 1, 0, 1}, {1, 0, 1, 1}};
        int[][] result = FloodFill.floodFill(image, 1, 1, 2);
        int[][] expected = {{1, 0, 2, 2}, {0, 2, 2, 0}, {2, 2, 0, 1}, {2, 0, 1, 1}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testRectangularGrid() {
        int[][] image = {{1, 1, 1, 1, 1}, {0, 0, 0, 0, 0}};
        int[][] result = FloodFill.floodFill(image, 0, 2, 0);
        int[][] expected = {{0, 0, 0, 0, 0}, {0, 0, 0, 0, 0}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testOriginalImageNotModified() {
        int[][] original = {{1, 1, 0}, {1, 0, 1}};
        int[][] imageCopy = new int[original.length][];
        for (int i = 0; i < original.length; i++) {
            imageCopy[i] = original[i].clone();
        }
        FloodFill.floodFill(imageCopy, 0, 0, 2);
        // Original should remain unchanged
        int[][] expectedOriginal = {{1, 1, 0}, {1, 0, 1}};
        assertArrayEquals(expectedOriginal, original);
    }

    @Test
    void testEmptyImage() {
        int[][] image = {};
        int[][] result = FloodFill.floodFill(image, 0, 0, 1);
        assertArrayEquals(image, result);
    }

    @Test
    void testNullImage() {
        int[][] result = FloodFill.floodFill(null, 0, 0, 1);
        assertNull(result);
    }

    @Test
    void testLargeGrid() {
        // Create a 5x5 grid with a cross pattern of 1s
        int[][] image = {
            {0, 0, 1, 0, 0},
            {0, 0, 1, 0, 0},
            {1, 1, 1, 1, 1},
            {0, 0, 1, 0, 0},
            {0, 0, 1, 0, 0}
        };
        int[][] result = FloodFill.floodFill(image, 2, 2, 9);
        int[][] expected = {
            {0, 0, 9, 0, 0},
            {0, 0, 9, 0, 0},
            {9, 9, 9, 9, 9},
            {0, 0, 9, 0, 0},
            {0, 0, 9, 0, 0}
        };
        assertArrayEquals(expected, result);
    }

    @Test
    void testDisconnectedRegions() {
        int[][] image = {{1, 0, 1}, {0, 0, 0}, {1, 0, 1}};
        int[][] result = FloodFill.floodFill(image, 0, 0, 2);
        int[][] expected = {{2, 0, 1}, {0, 0, 0}, {1, 0, 1}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testBoundaryFill() {
        int[][] image = {{1, 1, 1}, {1, 0, 1}, {1, 1, 1}};
        int[][] result = FloodFill.floodFill(image, 0, 0, 5);
        int[][] expected = {{5, 5, 5}, {5, 0, 5}, {5, 5, 5}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testCheckerboardPattern() {
        int[][] image = {{0, 1, 0}, {1, 0, 1}, {0, 1, 0}};
        int[][] result = FloodFill.floodFill(image, 0, 0, 2);
        int[][] expected = {{2, 1, 0}, {1, 0, 1}, {0, 1, 0}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testSameColorBoundary() {
        int[][] image = {{1, 2, 1}, {2, 1, 2}, {1, 2, 1}};
        int[][] result = FloodFill.floodFill(image, 1, 1, 3);
        int[][] expected = {{1, 2, 1}, {2, 3, 2}, {1, 2, 1}};
        assertArrayEquals(expected, result);
    }

    @Test
    void testLargeUniformRegion() {
        // Create a 4x4 grid all with same color
        int[][] image = new int[4][4];
        for (int i = 0; i < 4; i++) {
            for (int j = 0; j < 4; j++) {
                image[i][j] = 7;
            }
        }
        int[][] result = FloodFill.floodFill(image, 2, 2, 8);
        int[][] expected = new int[4][4];
        for (int i = 0; i < 4; i++) {
            for (int j = 0; j < 4; j++) {
                expected[i][j] = 8;
            }
        }
        assertArrayEquals(expected, result);
    }

    @Test
    void testEdgeCellFill() {
        int[][] image = {{3, 3, 3}, {3, 1, 3}, {3, 3, 3}};
        int[][] result = FloodFill.floodFill(image, 0, 1, 9);
        int[][] expected = {{9, 9, 9}, {9, 1, 9}, {9, 9, 9}};
        assertArrayEquals(expected, result);
    }
}
