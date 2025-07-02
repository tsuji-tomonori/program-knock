/**
 * Calculate the next generation of Conway's Game of Life.
 *
 * @param board - Current board state (m x n grid of 0s and 1s)
 * @returns Next generation board state
 */
export function nextGeneration(board: number[][]): number[][] {
  if (!board || !board[0]) {
    return [];
  }

  const m = board.length;
  const n = board[0].length;
  const nextBoard = Array.from({ length: m }, () => Array(n).fill(0));

  const directions = [[-1, -1], [-1, 0], [-1, 1], [0, -1], [0, 1], [1, -1], [1, 0], [1, 1]];

  for (let i = 0; i < m; i++) {
    for (let j = 0; j < n; j++) {
      let liveNeighbors = 0;

      for (const [di, dj] of directions) {
        const ni = i + di;
        const nj = j + dj;
        if (ni >= 0 && ni < m && nj >= 0 && nj < n && board[ni][nj] === 1) {
          liveNeighbors++;
        }
      }

      if (board[i][j] === 1) {
        if (liveNeighbors === 2 || liveNeighbors === 3) {
          nextBoard[i][j] = 1;
        } else {
          nextBoard[i][j] = 0;
        }
      } else {
        if (liveNeighbors === 3) {
          nextBoard[i][j] = 1;
        } else {
          nextBoard[i][j] = 0;
        }
      }
    }
  }

  return nextBoard;
}
