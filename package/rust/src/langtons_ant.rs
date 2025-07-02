use std::collections::HashSet;

#[derive(Clone, Copy, PartialEq)]
enum Direction {
    North = 0,
    East = 1,
    South = 2,
    West = 3,
}

impl Direction {
    fn turn_right(self) -> Direction {
        match self {
            Direction::North => Direction::East,
            Direction::East => Direction::South,
            Direction::South => Direction::West,
            Direction::West => Direction::North,
        }
    }
    
    fn turn_left(self) -> Direction {
        match self {
            Direction::North => Direction::West,
            Direction::East => Direction::North,
            Direction::South => Direction::East,
            Direction::West => Direction::South,
        }
    }
    
    fn get_delta(self) -> (i32, i32) {
        match self {
            Direction::North => (0, 1),
            Direction::East => (1, 0),
            Direction::South => (0, -1),
            Direction::West => (-1, 0),
        }
    }
}

pub fn langtons_ant(steps: i32) -> Vec<(i32, i32)> {
    let mut black_cells = HashSet::new();
    let mut ant_x = 0;
    let mut ant_y = 0;
    let mut direction = Direction::North;
    
    for _ in 0..steps {
        if black_cells.contains(&(ant_x, ant_y)) {
            black_cells.remove(&(ant_x, ant_y));
            direction = direction.turn_right();
        } else {
            black_cells.insert((ant_x, ant_y));
            direction = direction.turn_left();
        }
        
        let (dx, dy) = direction.get_delta();
        ant_x += dx;
        ant_y += dy;
    }
    
    let mut result: Vec<(i32, i32)> = black_cells.into_iter().collect();
    result.sort();
    result
}

#[cfg(test)]
mod tests {
    use super::*;

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
}