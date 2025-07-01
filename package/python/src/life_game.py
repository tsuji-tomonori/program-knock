from typing import List


def next_generation(board: List[List[int]]) -> List[List[int]]:
    """
    Calculate the next generation of Conway's Game of Life.

    Args:
        board: Current board state (m x n grid of 0s and 1s)

    Returns:
        Next generation board state
    """
    if not board or not board[0]:
        return []

    m, n = len(board), len(board[0])
    next_board = [[0] * n for _ in range(m)]

    directions = [(-1, -1), (-1, 0), (-1, 1), (0, -1), (0, 1), (1, -1), (1, 0), (1, 1)]

    for i in range(m):
        for j in range(n):
            live_neighbors = 0

            for di, dj in directions:
                ni, nj = i + di, j + dj
                if 0 <= ni < m and 0 <= nj < n and board[ni][nj] == 1:
                    live_neighbors += 1

            if board[i][j] == 1:
                if live_neighbors == 2 or live_neighbors == 3:
                    next_board[i][j] = 1
                else:
                    next_board[i][j] = 0
            else:
                if live_neighbors == 3:
                    next_board[i][j] = 1
                else:
                    next_board[i][j] = 0

    return next_board
