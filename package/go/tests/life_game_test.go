package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestLifeGameSample1(t *testing.T) {
	board := [][]int{{0, 1, 0}, {0, 1, 1}, {1, 0, 0}}
	expected := [][]int{{0, 1, 1}, {1, 1, 1}, {0, 1, 0}}
	assert.Equal(t, expected, src.NextGeneration(board))
}

func TestLifeGameSample2(t *testing.T) {
	board := [][]int{{1, 1, 1}, {1, 1, 1}, {1, 1, 1}}
	expected := [][]int{{1, 0, 1}, {0, 0, 0}, {1, 0, 1}}
	assert.Equal(t, expected, src.NextGeneration(board))
}

func TestLifeGameEmptyBoard(t *testing.T) {
	board := [][]int{{0, 0, 0}, {0, 0, 0}, {0, 0, 0}}
	expected := [][]int{{0, 0, 0}, {0, 0, 0}, {0, 0, 0}}
	assert.Equal(t, expected, src.NextGeneration(board))
}

func TestLifeGameSingleCellDies(t *testing.T) {
	board := [][]int{{0, 0, 0}, {0, 1, 0}, {0, 0, 0}}
	expected := [][]int{{0, 0, 0}, {0, 0, 0}, {0, 0, 0}}
	assert.Equal(t, expected, src.NextGeneration(board))
}

func TestLifeGameTwoCellsDie(t *testing.T) {
	board := [][]int{{0, 0, 0}, {1, 1, 0}, {0, 0, 0}}
	expected := [][]int{{0, 0, 0}, {0, 0, 0}, {0, 0, 0}}
	assert.Equal(t, expected, src.NextGeneration(board))
}

func TestLifeGameThreeCellsInLineStayAlive(t *testing.T) {
	board := [][]int{{0, 0, 0}, {1, 1, 1}, {0, 0, 0}}
	expected := [][]int{{0, 1, 0}, {0, 1, 0}, {0, 1, 0}}
	assert.Equal(t, expected, src.NextGeneration(board))
}

func TestLifeGameBlockPatternStable(t *testing.T) {
	board := [][]int{{0, 0, 0, 0}, {0, 1, 1, 0}, {0, 1, 1, 0}, {0, 0, 0, 0}}
	expected := [][]int{{0, 0, 0, 0}, {0, 1, 1, 0}, {0, 1, 1, 0}, {0, 0, 0, 0}}
	assert.Equal(t, expected, src.NextGeneration(board))
}

func TestLifeGameBlinkerPattern(t *testing.T) {
	board := [][]int{
		{0, 0, 0, 0, 0},
		{0, 0, 1, 0, 0},
		{0, 0, 1, 0, 0},
		{0, 0, 1, 0, 0},
		{0, 0, 0, 0, 0},
	}
	expected := [][]int{
		{0, 0, 0, 0, 0},
		{0, 0, 0, 0, 0},
		{0, 1, 1, 1, 0},
		{0, 0, 0, 0, 0},
		{0, 0, 0, 0, 0},
	}
	assert.Equal(t, expected, src.NextGeneration(board))
}

func TestLifeGameCornerCell(t *testing.T) {
	board := [][]int{{1, 1, 0}, {1, 0, 0}, {0, 0, 0}}
	expected := [][]int{{1, 1, 0}, {1, 1, 0}, {0, 0, 0}}
	assert.Equal(t, expected, src.NextGeneration(board))
}

func TestLifeGameLargeGrid(t *testing.T) {
	board := [][]int{
		{0, 0, 0, 0, 0, 0},
		{0, 0, 1, 1, 0, 0},
		{0, 0, 1, 1, 0, 0},
		{0, 0, 0, 0, 0, 0},
		{0, 1, 1, 1, 0, 0},
		{0, 0, 0, 0, 0, 0},
	}
	expected := [][]int{
		{0, 0, 0, 0, 0, 0},
		{0, 0, 1, 1, 0, 0},
		{0, 0, 1, 1, 0, 0},
		{0, 1, 0, 0, 0, 0},
		{0, 0, 1, 0, 0, 0},
		{0, 0, 1, 0, 0, 0},
	}
	assert.Equal(t, expected, src.NextGeneration(board))
}

func TestLifeGameSingleRow(t *testing.T) {
	board := [][]int{{1, 0, 1, 0, 1}}
	expected := [][]int{{0, 0, 0, 0, 0}}
	assert.Equal(t, expected, src.NextGeneration(board))
}

func TestLifeGameMaximumSizeGrid(t *testing.T) {
	// Create a 50x50 grid with a blinker pattern in the center
	board := make([][]int, 50)
	for i := range board {
		board[i] = make([]int, 50)
	}

	// Place blinker pattern in the center
	center := 25
	board[center-1][center] = 1
	board[center][center] = 1
	board[center+1][center] = 1

	result := src.NextGeneration(board)

	// Check that the result grid size is correct
	assert.Equal(t, 50, len(result))
	assert.Equal(t, 50, len(result[0]))

	// Check that the blinker has rotated
	assert.Equal(t, 1, result[center][center-1])
	assert.Equal(t, 1, result[center][center])
	assert.Equal(t, 1, result[center][center+1])
}

func TestLifeGameAllAliveGrid(t *testing.T) {
	board := [][]int{{1, 1, 1}, {1, 1, 1}, {1, 1, 1}}
	expected := [][]int{{1, 0, 1}, {0, 0, 0}, {1, 0, 1}}
	assert.Equal(t, expected, src.NextGeneration(board))
}

func TestLifeGameCheckerboardPattern(t *testing.T) {
	board := [][]int{{1, 0, 1, 0}, {0, 1, 0, 1}, {1, 0, 1, 0}, {0, 1, 0, 1}}
	expected := [][]int{{0, 1, 1, 0}, {1, 0, 0, 1}, {1, 0, 0, 1}, {0, 1, 1, 0}}
	assert.Equal(t, expected, src.NextGeneration(board))
}

func TestLifeGameRectangularGrid(t *testing.T) {
	board := [][]int{{0, 1, 0, 1, 0, 1}, {1, 0, 1, 0, 1, 0}, {0, 1, 0, 1, 0, 1}}
	expected := [][]int{{0, 1, 1, 1, 1, 0}, {1, 0, 0, 0, 0, 1}, {0, 1, 1, 1, 1, 0}}
	assert.Equal(t, expected, src.NextGeneration(board))
}
