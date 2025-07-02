package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestFloodFill(t *testing.T) {
	t.Run("Sample1", func(t *testing.T) {
		image := [][]int{{1, 1, 0}, {1, 0, 1}, {0, 1, 1}}
		sr, sc, newColor := 1, 2, 0
		result := src.FloodFill(image, sr, sc, newColor)
		expected := [][]int{{1, 1, 0}, {1, 0, 0}, {0, 0, 0}}
		assert.Equal(t, expected, result)
	})

	t.Run("Sample2", func(t *testing.T) {
		image := [][]int{{1, 1, 1}, {1, 1, 0}, {1, 0, 1}}
		sr, sc, newColor := 1, 1, 2
		result := src.FloodFill(image, sr, sc, newColor)
		expected := [][]int{{2, 2, 2}, {2, 2, 0}, {2, 0, 1}}
		assert.Equal(t, expected, result)
	})

	t.Run("Sample3", func(t *testing.T) {
		image := [][]int{{0, 0, 0}, {0, 1, 1}}
		sr, sc, newColor := 1, 1, 1
		result := src.FloodFill(image, sr, sc, newColor)
		expected := [][]int{{0, 0, 0}, {0, 1, 1}} // No change
		assert.Equal(t, expected, result)
	})

	t.Run("SingleCell", func(t *testing.T) {
		image := [][]int{{1}}
		result := src.FloodFill(image, 0, 0, 0)
		expected := [][]int{{0}}
		assert.Equal(t, expected, result)
	})

	t.Run("SingleCellNoChange", func(t *testing.T) {
		image := [][]int{{1}}
		result := src.FloodFill(image, 0, 0, 1)
		expected := [][]int{{1}}
		assert.Equal(t, expected, result)
	})

	t.Run("FillEntireGrid", func(t *testing.T) {
		image := [][]int{{1, 1, 1}, {1, 1, 1}, {1, 1, 1}}
		result := src.FloodFill(image, 1, 1, 0)
		expected := [][]int{{0, 0, 0}, {0, 0, 0}, {0, 0, 0}}
		assert.Equal(t, expected, result)
	})

	t.Run("NoConnectedRegion", func(t *testing.T) {
		image := [][]int{{0, 1, 0}, {1, 0, 1}, {0, 1, 0}}
		result := src.FloodFill(image, 1, 1, 1)
		expected := [][]int{{0, 1, 0}, {1, 1, 1}, {0, 1, 0}}
		assert.Equal(t, expected, result)
	})

	t.Run("CornerCell", func(t *testing.T) {
		image := [][]int{{1, 0, 0}, {0, 0, 0}, {0, 0, 1}}
		result := src.FloodFill(image, 0, 0, 2)
		expected := [][]int{{2, 0, 0}, {0, 0, 0}, {0, 0, 1}}
		assert.Equal(t, expected, result)
	})

	t.Run("LShapedRegion", func(t *testing.T) {
		image := [][]int{{1, 1, 0}, {1, 0, 0}, {1, 0, 1}}
		result := src.FloodFill(image, 0, 0, 2)
		expected := [][]int{{2, 2, 0}, {2, 0, 0}, {2, 0, 1}}
		assert.Equal(t, expected, result)
	})

	t.Run("ComplexPattern", func(t *testing.T) {
		image := [][]int{{1, 0, 1, 1}, {0, 1, 1, 0}, {1, 1, 0, 1}, {1, 0, 1, 1}}
		result := src.FloodFill(image, 1, 1, 2)
		expected := [][]int{{1, 0, 2, 2}, {0, 2, 2, 0}, {2, 2, 0, 1}, {2, 0, 1, 1}}
		assert.Equal(t, expected, result)
	})

	t.Run("RectangularGrid", func(t *testing.T) {
		image := [][]int{{1, 1, 1, 1, 1}, {0, 0, 0, 0, 0}}
		result := src.FloodFill(image, 0, 2, 0)
		expected := [][]int{{0, 0, 0, 0, 0}, {0, 0, 0, 0, 0}}
		assert.Equal(t, expected, result)
	})

	t.Run("OriginalImageNotModified", func(t *testing.T) {
		original := [][]int{{1, 1, 0}, {1, 0, 1}}
		// Create a copy for the test
		imageCopy := make([][]int, len(original))
		for i := range original {
			imageCopy[i] = make([]int, len(original[i]))
			copy(imageCopy[i], original[i])
		}
		src.FloodFill(imageCopy, 0, 0, 2)
		// Original should remain unchanged
		assert.Equal(t, [][]int{{1, 1, 0}, {1, 0, 1}}, original)
	})

	t.Run("EmptyImage", func(t *testing.T) {
		image := [][]int{}
		result := src.FloodFill(image, 0, 0, 1)
		expected := [][]int{}
		assert.Equal(t, expected, result)
	})

	t.Run("EmptyRows", func(t *testing.T) {
		image := [][]int{{}}
		result := src.FloodFill(image, 0, 0, 1)
		expected := [][]int{{}}
		assert.Equal(t, expected, result)
	})

	t.Run("LargeGrid", func(t *testing.T) {
		// Create a 10x10 grid filled with 1s
		image := make([][]int, 10)
		for i := range image {
			image[i] = make([]int, 10)
			for j := range image[i] {
				image[i][j] = 1
			}
		}
		result := src.FloodFill(image, 5, 5, 0)

		// All cells should be filled with 0
		expected := make([][]int, 10)
		for i := range expected {
			expected[i] = make([]int, 10)
			for j := range expected[i] {
				expected[i][j] = 0
			}
		}
		assert.Equal(t, expected, result)
	})

	t.Run("DiagonalPattern", func(t *testing.T) {
		// Test that diagonal cells are not connected
		image := [][]int{
			{1, 0, 0},
			{0, 1, 0},
			{0, 0, 1},
		}
		result := src.FloodFill(image, 0, 0, 2)
		expected := [][]int{
			{2, 0, 0},
			{0, 1, 0},
			{0, 0, 1},
		}
		assert.Equal(t, expected, result)
	})
}
