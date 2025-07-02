package com.programknock;

import java.util.*;

public class LifeGame {

    public static List<List<Integer>> nextGeneration(List<List<Integer>> board) {
        if (board == null || board.isEmpty() || board.get(0).isEmpty()) {
            return new ArrayList<>();
        }

        int m = board.size();
        int n = board.get(0).size();
        List<List<Integer>> nextBoard = new ArrayList<>();

        for (int i = 0; i < m; i++) {
            List<Integer> row = new ArrayList<>();
            for (int j = 0; j < n; j++) {
                row.add(0);
            }
            nextBoard.add(row);
        }

        int[][] directions = {{-1, -1}, {-1, 0}, {-1, 1}, {0, -1}, {0, 1}, {1, -1}, {1, 0}, {1, 1}};

        for (int i = 0; i < m; i++) {
            for (int j = 0; j < n; j++) {
                int liveNeighbors = 0;

                for (int[] dir : directions) {
                    int ni = i + dir[0];
                    int nj = j + dir[1];
                    if (ni >= 0 && ni < m && nj >= 0 && nj < n && board.get(ni).get(nj) == 1) {
                        liveNeighbors++;
                    }
                }

                if (board.get(i).get(j) == 1) {
                    if (liveNeighbors == 2 || liveNeighbors == 3) {
                        nextBoard.get(i).set(j, 1);
                    }
                } else {
                    if (liveNeighbors == 3) {
                        nextBoard.get(i).set(j, 1);
                    }
                }
            }
        }

        return nextBoard;
    }
}
