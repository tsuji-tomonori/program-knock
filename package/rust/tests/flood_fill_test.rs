use program_knock::flood_fill::*;

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
