package com.programknock;

import java.util.*;
import java.util.stream.Collectors;

public class SimulateLangtonsAnt {

    public static class Coordinate {
        public final int x;
        public final int y;

        public Coordinate(int x, int y) {
            this.x = x;
            this.y = y;
        }

        @Override
        public boolean equals(Object obj) {
            if (this == obj) return true;
            if (obj == null || getClass() != obj.getClass()) return false;
            Coordinate that = (Coordinate) obj;
            return x == that.x && y == that.y;
        }

        @Override
        public int hashCode() {
            return Objects.hash(x, y);
        }

        @Override
        public String toString() {
            return "(" + x + ", " + y + ")";
        }
    }

    public static List<Coordinate> simulateLangtonsAnt(int steps) {
        Set<Coordinate> blackCells = new HashSet<>();

        int x = 0, y = 0;
        int[][] directions = {{0, 1}, {1, 0}, {0, -1}, {-1, 0}};
        int dirIndex = 0;

        for (int i = 0; i < steps; i++) {
            Coordinate currentPos = new Coordinate(x, y);

            if (blackCells.contains(currentPos)) {
                blackCells.remove(currentPos);
                dirIndex = (dirIndex - 1 + 4) % 4;
            } else {
                blackCells.add(currentPos);
                dirIndex = (dirIndex + 1) % 4;
            }

            x += directions[dirIndex][0];
            y += directions[dirIndex][1];
        }

        return blackCells.stream()
            .sorted((a, b) -> {
                if (a.x != b.x) return Integer.compare(a.x, b.x);
                return Integer.compare(a.y, b.y);
            })
            .collect(Collectors.toList());
    }
}
