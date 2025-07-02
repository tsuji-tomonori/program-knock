use program_knock::count_in_range::*;

#[test]
fn test_count_in_range_basic() {
    let arr = vec![1, 2, 3, 4, 5];
    assert_eq!(count_in_range(&arr, 2, 4), 3);
    assert_eq!(count_in_range(&arr, 1, 5), 5);
    assert_eq!(count_in_range(&arr, 3, 3), 1);
}

#[test]
fn test_count_in_range_negative_values() {
    let arr = vec![-5, -3, -1, 0, 1, 3, 5];
    assert_eq!(count_in_range(&arr, -3, 1), 4);
    assert_eq!(count_in_range(&arr, -10, 10), 7);
    assert_eq!(count_in_range(&arr, -2, 2), 3);
}

#[test]
fn test_count_in_range_no_matches() {
    let arr = vec![1, 3, 5, 7, 9];
    assert_eq!(count_in_range(&arr, 10, 15), 0);
    assert_eq!(count_in_range(&arr, -5, 0), 0);
    assert_eq!(count_in_range(&arr, 2, 2), 0);
}

#[test]
fn test_count_in_range_exact_boundaries() {
    let arr = vec![1, 2, 3, 4, 5];
    assert_eq!(count_in_range(&arr, 1, 1), 1);
    assert_eq!(count_in_range(&arr, 5, 5), 1);
    assert_eq!(count_in_range(&arr, 2, 4), 3);
}

#[test]
fn test_count_in_range_single_element() {
    let arr = vec![42];
    assert_eq!(count_in_range(&arr, 42, 42), 1);
    assert_eq!(count_in_range(&arr, 40, 44), 1);
    assert_eq!(count_in_range(&arr, 50, 60), 0);
}

#[test]
fn test_count_in_range_full_range() {
    let arr = vec![1, 2, 3, 4, 5];
    assert_eq!(count_in_range(&arr, 0, 10), 5);
    assert_eq!(count_in_range(&arr, i32::MIN, i32::MAX), 5);
}

#[test]
fn test_count_in_range_maximum_values() {
    let arr = vec![i32::MAX - 2, i32::MAX - 1, i32::MAX];
    assert_eq!(count_in_range(&arr, i32::MAX - 1, i32::MAX), 2);
    assert_eq!(count_in_range(&arr, i32::MAX, i32::MAX), 1);
}

#[test]
fn test_count_in_range_large_array() {
    let arr: Vec<i32> = (0..100000).collect();
    assert_eq!(count_in_range(&arr, 1000, 2000), 1001);
    assert_eq!(count_in_range(&arr, 50000, 60000), 10001);
}

#[test]
fn test_count_in_range_zero_crossing() {
    let arr = vec![-3, -1, 0, 1, 3];
    assert_eq!(count_in_range(&arr, -2, 2), 3);
    assert_eq!(count_in_range(&arr, -1, 1), 3);
    assert_eq!(count_in_range(&arr, 0, 0), 1);
}

#[test]
fn test_count_in_range_boundary_inclusion() {
    let arr = vec![1, 2, 3, 4, 5];
    assert_eq!(count_in_range(&arr, 2, 4), 3);
    assert_eq!(count_in_range(&arr, 1, 3), 3);
    assert_eq!(count_in_range(&arr, 3, 5), 3);
}

#[test]
fn test_count_in_range_duplicate_values() {
    let arr = vec![1, 2, 2, 2, 3, 3, 4];
    assert_eq!(count_in_range(&arr, 2, 3), 5);
    assert_eq!(count_in_range(&arr, 2, 2), 3);
    assert_eq!(count_in_range(&arr, 3, 3), 2);
}

#[test]
fn test_count_in_range_performance() {
    let arr: Vec<i32> = (0..100000).map(|i| i * 2).collect();
    for i in 0..1000 {
        let l = i * 100;
        let r = l + 100;
        let count = count_in_range(&arr, l, r);
        assert!(count >= 0);
    }
}
