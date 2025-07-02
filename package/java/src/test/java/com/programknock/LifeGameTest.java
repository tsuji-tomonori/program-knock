package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.*;

import static org.junit.jupiter.api.Assertions.*;

class LifeGameTest {

    @Test
    void testSample1() {
        List<List<Integer>> board = Arrays.asList(
            Arrays.asList(0, 1, 0),
            Arrays.asList(0, 0, 1),
            Arrays.asList(1, 1, 1),
            Arrays.asList(0, 0, 0)
        );
        List<List<Integer>> expected = Arrays.asList(
            Arrays.asList(0, 0, 0),
            Arrays.asList(1, 0, 1),
            Arrays.asList(0, 1, 1),
            Arrays.asList(0, 1, 0)
        );
        assertEquals(expected, LifeGame.nextGeneration(board));
    }

    @Test
    void testSample2() {
        List<List<Integer>> board = Arrays.asList(
            Arrays.asList(1, 1),
            Arrays.asList(1, 0)
        );
        List<List<Integer>> expected = Arrays.asList(
            Arrays.asList(1, 1),
            Arrays.asList(1, 1)
        );
        assertEquals(expected, LifeGame.nextGeneration(board));
    }

    @Test
    void testEmptyBoard() {
        List<List<Integer>> board = new ArrayList<>();
        List<List<Integer>> expected = new ArrayList<>();
        assertEquals(expected, LifeGame.nextGeneration(board));
    }

    @Test
    void testSingleCell() {
        List<List<Integer>> board = Arrays.asList(
            Arrays.asList(1)
        );
        List<List<Integer>> expected = Arrays.asList(
            Arrays.asList(0)
        );
        assertEquals(expected, LifeGame.nextGeneration(board));
    }

    @Test
    void testAllDead() {
        List<List<Integer>> board = Arrays.asList(
            Arrays.asList(0, 0, 0),
            Arrays.asList(0, 0, 0),
            Arrays.asList(0, 0, 0)
        );
        List<List<Integer>> expected = Arrays.asList(
            Arrays.asList(0, 0, 0),
            Arrays.asList(0, 0, 0),
            Arrays.asList(0, 0, 0)
        );
        assertEquals(expected, LifeGame.nextGeneration(board));
    }

    @Test
    void testBlinker() {
        List<List<Integer>> board = Arrays.asList(
            Arrays.asList(0, 0, 0, 0, 0),
            Arrays.asList(0, 0, 1, 0, 0),
            Arrays.asList(0, 0, 1, 0, 0),
            Arrays.asList(0, 0, 1, 0, 0),
            Arrays.asList(0, 0, 0, 0, 0)
        );
        List<List<Integer>> expected = Arrays.asList(
            Arrays.asList(0, 0, 0, 0, 0),
            Arrays.asList(0, 0, 0, 0, 0),
            Arrays.asList(0, 1, 1, 1, 0),
            Arrays.asList(0, 0, 0, 0, 0),
            Arrays.asList(0, 0, 0, 0, 0)
        );
        assertEquals(expected, LifeGame.nextGeneration(board));
    }

    @Test
    void testBlock() {
        List<List<Integer>> board = Arrays.asList(
            Arrays.asList(0, 0, 0, 0),
            Arrays.asList(0, 1, 1, 0),
            Arrays.asList(0, 1, 1, 0),
            Arrays.asList(0, 0, 0, 0)
        );
        List<List<Integer>> expected = Arrays.asList(
            Arrays.asList(0, 0, 0, 0),
            Arrays.asList(0, 1, 1, 0),
            Arrays.asList(0, 1, 1, 0),
            Arrays.asList(0, 0, 0, 0)
        );
        assertEquals(expected, LifeGame.nextGeneration(board));
    }

    @Test
    void testGlider() {
        List<List<Integer>> board = Arrays.asList(
            Arrays.asList(0, 0, 0, 0, 0, 0),
            Arrays.asList(0, 0, 1, 0, 0, 0),
            Arrays.asList(0, 0, 0, 1, 0, 0),
            Arrays.asList(0, 1, 1, 1, 0, 0),
            Arrays.asList(0, 0, 0, 0, 0, 0),
            Arrays.asList(0, 0, 0, 0, 0, 0)
        );
        List<List<Integer>> expected = Arrays.asList(
            Arrays.asList(0, 0, 0, 0, 0, 0),
            Arrays.asList(0, 0, 0, 0, 0, 0),
            Arrays.asList(0, 1, 0, 1, 0, 0),
            Arrays.asList(0, 0, 1, 1, 0, 0),
            Arrays.asList(0, 0, 1, 0, 0, 0),
            Arrays.asList(0, 0, 0, 0, 0, 0)
        );
        assertEquals(expected, LifeGame.nextGeneration(board));
    }

    @Test
    void testSingleRow() {
        List<List<Integer>> board = Arrays.asList(
            Arrays.asList(1, 0, 1, 0, 1)
        );
        List<List<Integer>> expected = Arrays.asList(
            Arrays.asList(0, 0, 0, 0, 0)
        );
        assertEquals(expected, LifeGame.nextGeneration(board));
    }

    @Test
    void testMaximumSizeGrid() {
        List<List<Integer>> board = new ArrayList<>();
        for (int i = 0; i < 50; i++) {
            List<Integer> row = new ArrayList<>();
            for (int j = 0; j < 50; j++) {
                row.add(0);
            }
            board.add(row);
        }

        int center = 25;
        board.get(center - 1).set(center, 1);
        board.get(center).set(center, 1);
        board.get(center + 1).set(center, 1);

        List<List<Integer>> result = LifeGame.nextGeneration(board);

        assertEquals(50, result.size());
        assertEquals(50, result.get(0).size());

        assertEquals(1, result.get(center).get(center - 1));
        assertEquals(1, result.get(center).get(center));
        assertEquals(1, result.get(center).get(center + 1));
    }

    @Test
    void testAllAliveGrid() {
        List<List<Integer>> board = Arrays.asList(
            Arrays.asList(1, 1, 1),
            Arrays.asList(1, 1, 1),
            Arrays.asList(1, 1, 1)
        );
        List<List<Integer>> expected = Arrays.asList(
            Arrays.asList(1, 0, 1),
            Arrays.asList(0, 0, 0),
            Arrays.asList(1, 0, 1)
        );
        assertEquals(expected, LifeGame.nextGeneration(board));
    }

    @Test
    void testCheckerboardPattern() {
        List<List<Integer>> board = Arrays.asList(
            Arrays.asList(1, 0, 1, 0),
            Arrays.asList(0, 1, 0, 1),
            Arrays.asList(1, 0, 1, 0),
            Arrays.asList(0, 1, 0, 1)
        );
        List<List<Integer>> expected = Arrays.asList(
            Arrays.asList(0, 1, 1, 0),
            Arrays.asList(1, 0, 0, 1),
            Arrays.asList(1, 0, 0, 1),
            Arrays.asList(0, 1, 1, 0)
        );
        assertEquals(expected, LifeGame.nextGeneration(board));
    }

    @Test
    void testRectangularGrid() {
        List<List<Integer>> board = Arrays.asList(
            Arrays.asList(0, 1, 0, 1, 0, 1),
            Arrays.asList(1, 0, 1, 0, 1, 0),
            Arrays.asList(0, 1, 0, 1, 0, 1)
        );
        List<List<Integer>> expected = Arrays.asList(
            Arrays.asList(0, 1, 1, 1, 1, 0),
            Arrays.asList(1, 0, 0, 0, 0, 1),
            Arrays.asList(0, 1, 1, 1, 1, 0)
        );
        assertEquals(expected, LifeGame.nextGeneration(board));
    }
}
