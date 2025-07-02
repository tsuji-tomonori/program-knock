package src

// FloodFill fills a connected region in a 2D image with a new color
func FloodFill(image [][]int, sr, sc, newColor int) [][]int {
	if len(image) == 0 || len(image[0]) == 0 {
		return image
	}

	rows, cols := len(image), len(image[0])
	originalColor := image[sr][sc]

	// If the starting color is already the new color, no change needed
	if originalColor == newColor {
		return image
	}

	// Create a deep copy of the image to avoid modifying the original
	result := make([][]int, rows)
	for i := range image {
		result[i] = make([]int, cols)
		copy(result[i], image[i])
	}

	// DFS function to fill connected cells
	var dfs func(r, c int)
	dfs = func(r, c int) {
		// Check bounds and if current cell has the original color
		if r < 0 || r >= rows || c < 0 || c >= cols || result[r][c] != originalColor {
			return
		}

		// Change the color
		result[r][c] = newColor

		// Recursively fill adjacent cells (up, down, left, right)
		dfs(r-1, c) // up
		dfs(r+1, c) // down
		dfs(r, c-1) // left
		dfs(r, c+1) // right
	}

	dfs(sr, sc)
	return result
}
