pub fn flood_fill(image: &[Vec<i32>], sr: usize, sc: usize, new_color: i32) -> Vec<Vec<i32>> {
    if image.is_empty() || image[0].is_empty() {
        return image.to_vec();
    }

    let mut result = image.to_vec();
    let original_color = image[sr][sc];

    if original_color == new_color {
        return result;
    }

    flood_fill_recursive(&mut result, sr, sc, original_color, new_color);
    result
}

fn flood_fill_recursive(image: &mut [Vec<i32>], row: usize, col: usize, original_color: i32, new_color: i32) {
    if row >= image.len() || col >= image[0].len() || image[row][col] != original_color {
        return;
    }

    image[row][col] = new_color;

    if row > 0 {
        flood_fill_recursive(image, row - 1, col, original_color, new_color);
    }
    if row + 1 < image.len() {
        flood_fill_recursive(image, row + 1, col, original_color, new_color);
    }
    if col > 0 {
        flood_fill_recursive(image, row, col - 1, original_color, new_color);
    }
    if col + 1 < image[0].len() {
        flood_fill_recursive(image, row, col + 1, original_color, new_color);
    }
}
