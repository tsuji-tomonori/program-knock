pub fn next_generation(board: &[Vec<i32>]) -> Vec<Vec<i32>> {
    if board.is_empty() || board[0].is_empty() {
        return board.to_vec();
    }

    let rows = board.len();
    let cols = board[0].len();
    let mut next_board = vec![vec![0; cols]; rows];

    for i in 0..rows {
        for j in 0..cols {
            let live_neighbors = count_live_neighbors(board, i, j);

            match board[i][j] {
                1 => {
                    if live_neighbors == 2 || live_neighbors == 3 {
                        next_board[i][j] = 1;
                    }
                }
                0 => {
                    if live_neighbors == 3 {
                        next_board[i][j] = 1;
                    }
                }
                _ => {}
            }
        }
    }

    next_board
}

fn count_live_neighbors(board: &[Vec<i32>], row: usize, col: usize) -> i32 {
    let rows = board.len() as i32;
    let cols = board[0].len() as i32;
    let mut count = 0;

    for di in -1..=1 {
        for dj in -1..=1 {
            if di == 0 && dj == 0 {
                continue;
            }

            let ni = row as i32 + di;
            let nj = col as i32 + dj;

            if ni >= 0 && ni < rows && nj >= 0 && nj < cols {
                count += board[ni as usize][nj as usize];
            }
        }
    }

    count
}
