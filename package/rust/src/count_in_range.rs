pub fn count_in_range(arr: &[i32], l: i32, r: i32) -> i32 {
    if arr.is_empty() {
        return 0;
    }

    let left_bound = find_left_bound(arr, l);
    let right_bound = find_right_bound(arr, r);

    if left_bound >= arr.len() || right_bound >= arr.len() || left_bound > right_bound {
        0
    } else {
        (right_bound - left_bound + 1) as i32
    }
}

fn find_left_bound(arr: &[i32], target: i32) -> usize {
    let mut left = 0;
    let mut right = arr.len();

    while left < right {
        let mid = left + (right - left) / 2;
        if arr[mid] < target {
            left = mid + 1;
        } else {
            right = mid;
        }
    }

    left
}

fn find_right_bound(arr: &[i32], target: i32) -> usize {
    let mut left = 0;
    let mut right = arr.len();

    while left < right {
        let mid = left + (right - left) / 2;
        if arr[mid] <= target {
            left = mid + 1;
        } else {
            right = mid;
        }
    }

    if left > 0 && left <= arr.len() && (left == arr.len() || arr[left - 1] <= target) {
        left - 1
    } else {
        arr.len() // Return invalid index when no elements are <= target
    }
}
