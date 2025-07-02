use program_knock::find_closest_value::*;

#[test]
fn test_find_closest_value_basic() {
    let arr = vec![1, 3, 5, 7, 9];
    assert_eq!(find_closest_value(&arr, 4), 3);
    assert_eq!(find_closest_value(&arr, 6), 5);
    assert_eq!(find_closest_value(&arr, 8), 7);
}

#[test]
fn test_find_closest_value_equal_distance() {
    let arr = vec![1, 3, 5, 7, 9];
    assert_eq!(find_closest_value(&arr, 4), 3);
    assert_eq!(find_closest_value(&arr, 6), 5);
}

#[test]
fn test_find_closest_value_out_of_range() {
    let arr = vec![1, 3, 5, 7, 9];
    assert_eq!(find_closest_value(&arr, 0), 1);
    assert_eq!(find_closest_value(&arr, 10), 9);
}

#[test]
fn test_find_closest_value_exact_match() {
    let arr = vec![1, 3, 5, 7, 9];
    assert_eq!(find_closest_value(&arr, 5), 5);
    assert_eq!(find_closest_value(&arr, 1), 1);
    assert_eq!(find_closest_value(&arr, 9), 9);
}

#[test]
fn test_find_closest_value_single_element() {
    let arr = vec![42];
    assert_eq!(find_closest_value(&arr, 10), 42);
    assert_eq!(find_closest_value(&arr, 42), 42);
    assert_eq!(find_closest_value(&arr, 100), 42);
}

#[test]
fn test_find_closest_value_boundary_search() {
    let arr = vec![1, 2, 3, 4, 5];
    assert_eq!(find_closest_value(&arr, 0), 1);
    assert_eq!(find_closest_value(&arr, 6), 5);
}

#[test]
fn test_find_closest_value_large_values() {
    let arr = vec![1000000, 2000000, 3000000];
    assert_eq!(find_closest_value(&arr, 1500000), 1000000);
    assert_eq!(find_closest_value(&arr, 2500000), 2000000);
}

#[test]
fn test_find_closest_value_negative_values() {
    let arr = vec![-10, -5, 0, 5, 10];
    assert_eq!(find_closest_value(&arr, -3), -5);
    assert_eq!(find_closest_value(&arr, 3), 5);
    assert_eq!(find_closest_value(&arr, -7), -5);
}

#[test]
fn test_find_closest_value_large_array() {
    let arr: Vec<i32> = (0..100000).map(|i| i * 2).collect();
    assert_eq!(find_closest_value(&arr, 1001), 1000);
    assert_eq!(find_closest_value(&arr, 1000), 1000);
    assert_eq!(find_closest_value(&arr, 999), 998);
}

#[test]
fn test_find_closest_value_distance_calculation() {
    let arr = vec![10, 20, 30];
    assert_eq!(find_closest_value(&arr, 15), 10);
    assert_eq!(find_closest_value(&arr, 25), 20);
}

#[test]
fn test_find_closest_value_prefer_smaller() {
    let arr = vec![1, 3, 5];
    assert_eq!(find_closest_value(&arr, 2), 1);
    assert_eq!(find_closest_value(&arr, 4), 3);
}

#[test]
fn test_find_closest_value_mixed_distances() {
    let arr = vec![1, 10, 100, 1000];
    assert_eq!(find_closest_value(&arr, 50), 10);
    assert_eq!(find_closest_value(&arr, 500), 100);
}
