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

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_next_generation_basic_evolution() {
        let board = vec![
            vec![0, 1, 0],
            vec![0, 1, 0],
            vec![0, 1, 0],
        ];
        let expected = vec![
            vec![0, 0, 0],
            vec![1, 1, 1],
            vec![0, 0, 0],
        ];
        assert_eq!(next_generation(&board), expected);
    }

    #[test]
    fn test_next_generation_still_pattern() {
        let board = vec![
            vec![0, 0, 0, 0],
            vec![0, 1, 1, 0],
            vec![0, 1, 1, 0],
            vec![0, 0, 0, 0],
        ];
        let expected = board.clone();
        assert_eq!(next_generation(&board), expected);
    }

    #[test]
    fn test_next_generation_oscillator() {
        let board = vec![
            vec![0, 0, 0, 0, 0],
            vec![0, 0, 1, 0, 0],
            vec![0, 0, 1, 0, 0],
            vec![0, 0, 1, 0, 0],
            vec![0, 0, 0, 0, 0],
        ];
        let expected = vec![
            vec![0, 0, 0, 0, 0],
            vec![0, 0, 0, 0, 0],
            vec![0, 1, 1, 1, 0],
            vec![0, 0, 0, 0, 0],
            vec![0, 0, 0, 0, 0],
        ];
        assert_eq!(next_generation(&board), expected);
    }

    #[test]
    fn test_next_generation_all_dead() {
        let board = vec![
            vec![0, 0, 0],
            vec![0, 0, 0],
            vec![0, 0, 0],
        ];
        let expected = board.clone();
        assert_eq!(next_generation(&board), expected);
    }

    #[test]
    fn test_next_generation_all_alive() {
        let board = vec![
            vec![1, 1, 1],
            vec![1, 1, 1],
            vec![1, 1, 1],
        ];
        let expected = vec![
            vec![1, 0, 1],
            vec![0, 0, 0],
            vec![1, 0, 1],
        ];
        assert_eq!(next_generation(&board), expected);
    }

    #[test]
    fn test_next_generation_single_cell() {
        let board = vec![vec![1]];
        let expected = vec![vec![0]];
        assert_eq!(next_generation(&board), expected);
    }

    #[test]
    fn test_next_generation_boundary_processing() {
        let board = vec![
            vec![1, 1],
            vec![1, 0],
        ];
        let expected = vec![
            vec![1, 1],
            vec![1, 1],
        ];
        assert_eq!(next_generation(&board), expected);
    }

    #[test]
    fn test_next_generation_glider_pattern() {
        let board = vec![
            vec![0, 0, 1, 0, 0],
            vec![1, 0, 1, 0, 0],
            vec![0, 1, 1, 0, 0],
            vec![0, 0, 0, 0, 0],
            vec![0, 0, 0, 0, 0],
        ];
        let result = next_generation(&board);
        assert_ne!(result, board);
        assert!(result.iter().flatten().sum::<i32>() > 0);
    }

    #[test]
    fn test_next_generation_large_grid() {
        let board = vec![vec![0; 50]; 50];
        let result = next_generation(&board);
        assert_eq!(result.len(), 50);
        assert_eq!(result[0].len(), 50);
    }

    #[test]
    fn test_next_generation_birth_death_rules() {
        let board = vec![
            vec![0, 1, 0],
            vec![1, 0, 1],
            vec![0, 1, 0],
        ];
        let result = next_generation(&board);
        assert_eq!(result[1][1], 0);
    }
}