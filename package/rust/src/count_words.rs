use std::collections::HashMap;

pub fn count_words(text: &str) -> Vec<(String, i32)> {
    let mut word_count = HashMap::new();

    for word in text.split_whitespace() {
        *word_count.entry(word.to_string()).or_insert(0) += 1;
    }

    let mut result: Vec<(String, i32)> = word_count.into_iter().collect();

    result.sort_by(|a, b| {
        b.1.cmp(&a.1).then_with(|| a.0.cmp(&b.0))
    });

    result
}
