package src

import "sort"

// AntPoint represents a coordinate point for Langton's ant
type AntPoint struct {
	X, Y int
}

// SimulateLangtonsAnt simulates Langton's ant for the specified number of steps
// and returns the coordinates of black squares.
//
// Parameters:
//   - steps: number of steps the ant moves (0 <= steps <= 10,000)
//
// Returns:
//   - slice of coordinates of black squares. Each coordinate is returned as a Point struct.
func SimulateLangtonsAnt(steps int) []AntPoint {
	// Set to hold coordinates of black squares (initially all white, so empty)
	blackCells := make(map[AntPoint]bool)

	// Ant's initial position and direction
	x, y := 0, 0
	// Direction vectors (up, right, down, left)
	directions := []AntPoint{{0, 1}, {1, 0}, {0, -1}, {-1, 0}}
	// Initially facing up (index 0 of directions is up)
	dirIndex := 0

	for i := 0; i < steps; i++ {
		currentPos := AntPoint{x, y}

		if blackCells[currentPos] {
			// Black square → change to white → turn left 90°
			delete(blackCells, currentPos)
			dirIndex = (dirIndex - 1 + 4) % 4
		} else {
			// White square → change to black → turn right 90°
			blackCells[currentPos] = true
			dirIndex = (dirIndex + 1) % 4
		}

		// Move one square in the selected direction
		dx, dy := directions[dirIndex].X, directions[dirIndex].Y
		x += dx
		y += dy
	}

	// Convert black squares to slice and sort by (x, y) in ascending order
	result := make([]AntPoint, 0)
	for point := range blackCells {
		result = append(result, point)
	}

	// Sort by x first, then by y
	sort.Slice(result, func(i, j int) bool {
		if result[i].X != result[j].X {
			return result[i].X < result[j].X
		}
		return result[i].Y < result[j].Y
	})

	return result
}
