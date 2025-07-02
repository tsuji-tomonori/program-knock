package com.programknock;

import java.util.*;

public class FloodFill {
    
    public static int[][] floodFill(int[][] image, int sr, int sc, int newColor) {
        if (image == null || image.length == 0 || image[0].length == 0) {
            return image;
        }
        
        int rows = image.length;
        int cols = image[0].length;
        int originalColor = image[sr][sc];
        
        // If the starting color is already the new color, no change needed
        if (originalColor == newColor) {
            return image;
        }
        
        // Create a deep copy of the image to avoid modifying the original
        int[][] result = new int[rows][cols];
        for (int i = 0; i < rows; i++) {
            result[i] = image[i].clone();
        }
        
        // Start DFS from the given position
        dfs(result, sr, sc, originalColor, newColor, rows, cols);
        
        return result;
    }
    
    private static void dfs(int[][] result, int r, int c, int originalColor, int newColor, int rows, int cols) {
        // Check bounds and if current cell has the original color
        if (r < 0 || r >= rows || c < 0 || c >= cols || result[r][c] != originalColor) {
            return;
        }
        
        // Change the color
        result[r][c] = newColor;
        
        // Recursively fill adjacent cells (up, down, left, right)
        dfs(result, r - 1, c, originalColor, newColor, rows, cols); // up
        dfs(result, r + 1, c, originalColor, newColor, rows, cols); // down
        dfs(result, r, c - 1, originalColor, newColor, rows, cols); // left
        dfs(result, r, c + 1, originalColor, newColor, rows, cols); // right
    }
}