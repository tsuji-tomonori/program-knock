use program_knock::langtons_ant::*;
use std::collections::HashSet;

#[test]
fn test_langtons_ant_0_steps() {
    let result = langtons_ant(0);
    assert_eq!(result, vec![]);
}

#[test]
fn test_langtons_ant_1_step() {
    let result = langtons_ant(1);
    assert_eq!(result, vec![(0, 0)]);
}

#[test]
fn test_langtons_ant_5_steps() {
    let result = langtons_ant(5);
    let mut expected = vec![(-1, -1), (-1, 0), (0, -1)];
    expected.sort();
    assert_eq!(result, expected);
}

#[test]
fn test_langtons_ant_long_simulation() {
    let result = langtons_ant(1000);
    assert!(result.len() <= 1000);
}

#[test]
fn test_langtons_ant_direction_changes() {
    let result = langtons_ant(8);
    assert!(result.len() >= 2);
}

#[test]
fn test_langtons_ant_negative_coordinates() {
    let result = langtons_ant(100);
    let has_negative = result.iter().any(|(x, y)| *x < 0 || *y < 0);
    assert!(has_negative || result.is_empty());
}

#[test]
fn test_langtons_ant_sort_order() {
    let result = langtons_ant(50);
    let mut sorted_result = result.clone();
    sorted_result.sort();
    assert_eq!(result, sorted_result);
}

#[test]
fn test_langtons_ant_large_coordinates() {
    let result = langtons_ant(10000);
    assert!(result.iter().any(|(x, y)| x.abs() > 10 || y.abs() > 10));
}

#[test]
fn test_langtons_ant_deduplication() {
    let result = langtons_ant(20);
    let mut unique_coords = HashSet::new();
    for coord in &result {
        assert!(unique_coords.insert(*coord), "Duplicate coordinate found: {:?}", coord);
    }
}

#[test]
fn test_langtons_ant_medium_simulation() {
    let result = langtons_ant(500);
    assert!(result.len() > 10);
    assert!(result.len() <= 500);
}
