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

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_flood_fill_basic() {
        let image = vec![
            vec![1, 1, 1],
            vec![1, 1, 0],
            vec![1, 0, 1],
        ];
        let result = flood_fill(&image, 1, 1, 2);
        let expected = vec![
            vec![2, 2, 2],
            vec![2, 2, 0],
            vec![2, 0, 1],
        ];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_flood_fill_large_connected_area() {
        let image = vec![
            vec![1, 1, 1, 1],
            vec![1, 1, 1, 1],
            vec![1, 1, 1, 1],
            vec![1, 1, 1, 1],
        ];
        let result = flood_fill(&image, 0, 0, 2);
        let expected = vec![
            vec![2, 2, 2, 2],
            vec![2, 2, 2, 2],
            vec![2, 2, 2, 2],
            vec![2, 2, 2, 2],
        ];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_flood_fill_no_change() {
        let image = vec![
            vec![1, 1, 1],
            vec![1, 2, 1],
            vec![1, 1, 1],
        ];
        let result = flood_fill(&image, 1, 1, 2);
        assert_eq!(result, image);
    }

    #[test]
    fn test_flood_fill_single_pixel() {
        let image = vec![vec![1]];
        let result = flood_fill(&image, 0, 0, 2);
        let expected = vec![vec![2]];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_flood_fill_all_same_color() {
        let image = vec![
            vec![1, 1, 1],
            vec![1, 1, 1],
            vec![1, 1, 1],
        ];
        let result = flood_fill(&image, 1, 1, 2);
        let expected = vec![
            vec![2, 2, 2],
            vec![2, 2, 2],
            vec![2, 2, 2],
        ];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_flood_fill_isolated_pixels() {
        let image = vec![
            vec![0, 1, 0],
            vec![1, 0, 1],
            vec![0, 1, 0],
        ];
        let result = flood_fill(&image, 1, 1, 2);
        let expected = vec![
            vec![0, 1, 0],
            vec![1, 2, 1],
            vec![0, 1, 0],
        ];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_flood_fill_corner_fill() {
        let image = vec![
            vec![1, 0, 0],
            vec![0, 0, 0],
            vec![0, 0, 0],
        ];
        let result = flood_fill(&image, 0, 0, 2);
        let expected = vec![
            vec![2, 0, 0],
            vec![0, 0, 0],
            vec![0, 0, 0],
        ];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_flood_fill_maximum_size() {
        let mut image = vec![vec![1; 50]; 50];
        image[25][25] = 0;
        let result = flood_fill(&image, 0, 0, 2);
        assert_eq!(result[0][0], 2);
        assert_eq!(result[25][25], 0);
        assert_eq!(result[49][49], 2);
    }

    #[test]
    fn test_flood_fill_rectangular_image() {
        let image = vec![
            vec![1, 1, 1, 1, 1],
            vec![1, 0, 0, 0, 1],
            vec![1, 1, 1, 1, 1],
        ];
        let result = flood_fill(&image, 0, 0, 2);
        let expected = vec![
            vec![2, 2, 2, 2, 2],
            vec![2, 0, 0, 0, 2],
            vec![2, 2, 2, 2, 2],
        ];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_flood_fill_complex_boundaries() {
        let image = vec![
            vec![1, 1, 2, 2],
            vec![1, 2, 2, 1],
            vec![2, 2, 1, 1],
            vec![2, 1, 1, 1],
        ];
        let result = flood_fill(&image, 0, 0, 3);
        let expected = vec![
            vec![3, 3, 2, 2],
            vec![3, 2, 2, 1],
            vec![2, 2, 1, 1],
            vec![2, 1, 1, 1],
        ];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_flood_fill_4_directional_connectivity() {
        let image = vec![
            vec![1, 0, 1],
            vec![0, 1, 0],
            vec![1, 0, 1],
        ];
        let result = flood_fill(&image, 1, 1, 2);
        let expected = vec![
            vec![1, 0, 1],
            vec![0, 2, 0],
            vec![1, 0, 1],
        ];
        assert_eq!(result, expected);
    }
}