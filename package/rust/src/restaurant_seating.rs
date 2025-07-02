use std::collections::VecDeque;

pub fn restaurant_seating(commands: &[String]) -> Vec<String> {
    let mut queue: VecDeque<String> = VecDeque::new();
    let mut seated = Vec::new();

    for command in commands {
        if command.starts_with("arrive ") {
            let name = command.strip_prefix("arrive ").unwrap().to_string();
            queue.push_back(name);
        } else if command.starts_with("seat ") {
            let count_str = command.strip_prefix("seat ").unwrap();
            if let Ok(count) = count_str.parse::<usize>() {
                for _ in 0..count {
                    if let Some(name) = queue.pop_front() {
                        seated.push(name);
                    }
                }
            }
        }
    }

    seated
}
