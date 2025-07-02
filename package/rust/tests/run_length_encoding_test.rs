use program_knock::run_length_encoding::*;

#[test]
fn test_run_length_encoding_basic() {
    let s = "aaabbc";
    let result = run_length_encoding(s);
    let expected = vec![('a', 3), ('b', 2), ('c', 1)];
    assert_eq!(result, expected);
}

#[test]
fn test_run_length_encoding_all_different() {
    let s = "abcdef";
    let result = run_length_encoding(s);
    let expected = vec![('a', 1), ('b', 1), ('c', 1), ('d', 1), ('e', 1), ('f', 1)];
    assert_eq!(result, expected);
}

#[test]
fn test_run_length_encoding_all_same() {
    let s = "aaaaaaa";
    let result = run_length_encoding(s);
    let expected = vec![('a', 7)];
    assert_eq!(result, expected);
}

#[test]
fn test_run_length_encoding_single_character() {
    let s = "a";
    let result = run_length_encoding(s);
    let expected = vec![('a', 1)];
    assert_eq!(result, expected);
}

#[test]
fn test_run_length_encoding_alternating_pattern() {
    let s = "abababab";
    let result = run_length_encoding(s);
    let expected = vec![('a', 1), ('b', 1), ('a', 1), ('b', 1), ('a', 1), ('b', 1), ('a', 1), ('b', 1)];
    assert_eq!(result, expected);
}

#[test]
fn test_run_length_encoding_mixed_lengths() {
    let s = "aabbbbcccccccddee";
    let result = run_length_encoding(s);
    let expected = vec![('a', 2), ('b', 4), ('c', 7), ('d', 2), ('e', 2)];
    assert_eq!(result, expected);
}

#[test]
fn test_run_length_encoding_empty_string() {
    let s = "";
    let result = run_length_encoding(s);
    assert_eq!(result, vec![]);
}

#[test]
fn test_run_length_encoding_maximum_length() {
    let s = "a".repeat(100000);
    let result = run_length_encoding(&s);
    assert_eq!(result, vec![('a', 100000)]);
}

#[test]
fn test_run_length_encoding_large_consecutive() {
    let s = "a".repeat(50000) + &"b".repeat(50000);
    let result = run_length_encoding(&s);
    assert_eq!(result, vec![('a', 50000), ('b', 50000)]);
}

#[test]
fn test_run_length_encoding_complex_pattern() {
    let s = "aaabbbaaacccaaa";
    let result = run_length_encoding(s);
    let expected = vec![('a', 3), ('b', 3), ('a', 3), ('c', 3), ('a', 3)];
    assert_eq!(result, expected);
}

#[test]
fn test_run_length_encoding_alphabetical_order() {
    let s = "aabbccddee";
    let result = run_length_encoding(s);
    let expected = vec![('a', 2), ('b', 2), ('c', 2), ('d', 2), ('e', 2)];
    assert_eq!(result, expected);
}

#[test]
fn test_run_length_encoding_reverse_order() {
    let s = "eeddccbbaa";
    let result = run_length_encoding(s);
    let expected = vec![('e', 2), ('d', 2), ('c', 2), ('b', 2), ('a', 2)];
    assert_eq!(result, expected);
}
