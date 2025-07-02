pub fn run_length_encoding(s: &str) -> Vec<(char, i32)> {
    if s.is_empty() {
        return vec![];
    }

    let mut result = Vec::new();
    let mut chars = s.chars();
    let mut current_char = chars.next().unwrap();
    let mut count = 1;

    for ch in chars {
        if ch == current_char {
            count += 1;
        } else {
            result.push((current_char, count));
            current_char = ch;
            count = 1;
        }
    }

    result.push((current_char, count));
    result
}
