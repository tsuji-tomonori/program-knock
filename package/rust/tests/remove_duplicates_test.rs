use program_knock::remove_duplicates::*;
use std::collections::HashSet;

#[test]
fn test_remove_duplicates_basic() {
    let customer_ids = vec![1, 2, 3, 2, 4, 1, 5];
    let result = remove_duplicates(&customer_ids);
    assert_eq!(result, vec![1, 2, 3, 4, 5]);
}

#[test]
fn test_remove_duplicates_no_duplicates() {
    let customer_ids = vec![1, 2, 3, 4, 5];
    let result = remove_duplicates(&customer_ids);
    assert_eq!(result, vec![1, 2, 3, 4, 5]);
}

#[test]
fn test_remove_duplicates_all_same() {
    let customer_ids = vec![5, 5, 5, 5, 5];
    let result = remove_duplicates(&customer_ids);
    assert_eq!(result, vec![5]);
}

#[test]
fn test_remove_duplicates_empty_list() {
    let customer_ids = vec![];
    let result = remove_duplicates(&customer_ids);
    assert_eq!(result, vec![]);
}

#[test]
fn test_remove_duplicates_negative_values() {
    let customer_ids = vec![-1, -2, -1, -3, -2];
    let result = remove_duplicates(&customer_ids);
    assert_eq!(result, vec![-1, -2, -3]);
}

#[test]
fn test_remove_duplicates_single_element() {
    let customer_ids = vec![42];
    let result = remove_duplicates(&customer_ids);
    assert_eq!(result, vec![42]);
}

#[test]
fn test_remove_duplicates_minimal_duplicates() {
    let customer_ids = vec![1, 2, 1];
    let result = remove_duplicates(&customer_ids);
    assert_eq!(result, vec![1, 2]);
}

#[test]
fn test_remove_duplicates_maximum_values() {
    let customer_ids = vec![i32::MAX, i32::MIN, i32::MAX, 0, i32::MIN];
    let result = remove_duplicates(&customer_ids);
    assert_eq!(result, vec![i32::MAX, i32::MIN, 0]);
}

#[test]
fn test_remove_duplicates_large_data() {
    let mut customer_ids = Vec::new();
    for i in 0..1000000 {
        customer_ids.push(i % 500000);
    }
    let result = remove_duplicates(&customer_ids);
    assert_eq!(result.len(), 500000);
    // 結果をHashSetに変換してO(1)で存在確認
    let result_set: HashSet<i32> = result.iter().cloned().collect();
    for i in 0..500000 {
        assert!(result_set.contains(&i));
    }
}

#[test]
fn test_remove_duplicates_reverse_pattern() {
    let customer_ids = vec![5, 4, 3, 2, 1, 5, 4, 3, 2, 1];
    let result = remove_duplicates(&customer_ids);
    assert_eq!(result, vec![5, 4, 3, 2, 1]);
}

#[test]
fn test_remove_duplicates_zero_values() {
    let customer_ids = vec![0, 1, 0, 2, 0, 3];
    let result = remove_duplicates(&customer_ids);
    assert_eq!(result, vec![0, 1, 2, 3]);
}

#[test]
fn test_remove_duplicates_preserves_order() {
    let customer_ids = vec![3, 1, 4, 1, 5, 9, 2, 6, 5, 3];
    let result = remove_duplicates(&customer_ids);
    assert_eq!(result, vec![3, 1, 4, 5, 9, 2, 6]);
}
