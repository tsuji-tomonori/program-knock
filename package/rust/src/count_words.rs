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

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_count_words_basic() {
        let text = "apple banana apple cherry banana apple";
        let result = count_words(text);
        let expected = vec![
            ("apple".to_string(), 3),
            ("banana".to_string(), 2),
            ("cherry".to_string(), 1),
        ];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_count_words_single_word() {
        let text = "hello";
        let result = count_words(text);
        let expected = vec![("hello".to_string(), 1)];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_count_words_same_frequency() {
        let text = "a b c";
        let result = count_words(text);
        let expected = vec![
            ("a".to_string(), 1),
            ("b".to_string(), 1),
            ("c".to_string(), 1),
        ];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_count_words_empty_string() {
        let text = "";
        let result = count_words(text);
        assert_eq!(result, vec![]);
    }

    #[test]
    fn test_count_words_single_character() {
        let text = "a a b";
        let result = count_words(text);
        let expected = vec![
            ("a".to_string(), 2),
            ("b".to_string(), 1),
        ];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_count_words_long_words() {
        let text = "supercalifragilisticexpialidocious word supercalifragilisticexpialidocious";
        let result = count_words(text);
        let expected = vec![
            ("supercalifragilisticexpialidocious".to_string(), 2),
            ("word".to_string(), 1),
        ];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_count_words_alphabetical_sorting() {
        let text = "zebra apple zebra banana apple cherry";
        let result = count_words(text);
        let expected = vec![
            ("apple".to_string(), 2),
            ("zebra".to_string(), 2),
            ("banana".to_string(), 1),
            ("cherry".to_string(), 1),
        ];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_count_words_large_dataset() {
        let words: Vec<String> = (0..1000).map(|i| format!("word{}", i % 100)).collect();
        let text = words.join(" ");
        let result = count_words(&text);
        assert_eq!(result.len(), 100);
        assert!(result.iter().all(|(_, count)| *count == 10));
    }

    #[test]
    fn test_count_words_mixed_frequency() {
        let text = "the quick brown fox jumps over the lazy dog the";
        let result = count_words(text);
        assert_eq!(result[0], ("the".to_string(), 3));
        assert_eq!(result.len(), 8);
    }

    #[test]
    fn test_count_words_whitespace_handling() {
        let text = "  word1   word2  word1  ";
        let result = count_words(text);
        let expected = vec![
            ("word1".to_string(), 2),
            ("word2".to_string(), 1),
        ];
        assert_eq!(result, expected);
    }
}
