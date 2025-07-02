package src

// NextGeneration calculates the next generation of Conway's Game of Life.
//
// Parameters:
//   - board: current board state (m x n grid of 0s and 1s)
//
// Returns:
//   - next generation board state
func NextGeneration(board [][]int) [][]int {
	if len(board) == 0 || len(board[0]) == 0 {
		return [][]int{}
	}

	m, n := len(board), len(board[0])
	nextBoard := make([][]int, m)
	for i := range nextBoard {
		nextBoard[i] = make([]int, n)
	}

	directions := [][]int{
		{-1, -1}, {-1, 0}, {-1, 1},
		{0, -1}, {0, 1},
		{1, -1}, {1, 0}, {1, 1},
	}

	for i := 0; i < m; i++ {
		for j := 0; j < n; j++ {
			liveNeighbors := 0

			for _, dir := range directions {
				ni, nj := i+dir[0], j+dir[1]
				if ni >= 0 && ni < m && nj >= 0 && nj < n && board[ni][nj] == 1 {
					liveNeighbors++
				}
			}

			if board[i][j] == 1 {
				// Cell is currently alive
				if liveNeighbors == 2 || liveNeighbors == 3 {
					nextBoard[i][j] = 1
				} else {
					nextBoard[i][j] = 0
				}
			} else {
				// Cell is currently dead
				if liveNeighbors == 3 {
					nextBoard[i][j] = 1
				} else {
					nextBoard[i][j] = 0
				}
			}
		}
	}

	return nextBoard
}
