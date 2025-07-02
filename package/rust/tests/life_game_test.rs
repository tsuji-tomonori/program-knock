use program_knock::life_game::*;

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
