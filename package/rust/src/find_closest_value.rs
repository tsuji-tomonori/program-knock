pub fn find_closest_value(arr: &[i32], target: i32) -> i32 {
    if arr.is_empty() {
        panic!("Array cannot be empty");
    }

    let mut left = 0;
    let mut right = arr.len() - 1;

    while left < right {
        let mid = left + (right - left) / 2;

        if arr[mid] < target {
            left = mid + 1;
        } else {
            right = mid;
        }
    }

    let closest_index = if left == 0 {
        0
    } else if left == arr.len() {
        arr.len() - 1
    } else {
        let diff_left = (target - arr[left - 1]).abs();
        let diff_right = (arr[left] - target).abs();

        if diff_left <= diff_right {
            left - 1
        } else {
            left
        }
    };

    arr[closest_index]
}
