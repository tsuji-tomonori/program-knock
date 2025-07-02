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
