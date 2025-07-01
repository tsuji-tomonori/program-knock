from src.flood_fill import flood_fill


class TestFloodFill:
    def test_sample_1(self):
        image = [[1, 1, 0], [1, 0, 1], [0, 1, 1]]
        sr, sc, new_color = 1, 2, 0
        result = flood_fill(image, sr, sc, new_color)
        expected = [[1, 1, 0], [1, 0, 0], [0, 0, 0]]
        assert result == expected

    def test_sample_2(self):
        image = [[1, 1, 1], [1, 1, 0], [1, 0, 1]]
        sr, sc, new_color = 1, 1, 2
        result = flood_fill(image, sr, sc, new_color)
        expected = [[2, 2, 2], [2, 2, 0], [2, 0, 1]]
        assert result == expected

    def test_sample_3(self):
        image = [[0, 0, 0], [0, 1, 1]]
        sr, sc, new_color = 1, 1, 1
        result = flood_fill(image, sr, sc, new_color)
        expected = [[0, 0, 0], [0, 1, 1]]  # 変更なし
        assert result == expected

    def test_single_cell(self):
        image = [[1]]
        result = flood_fill(image, 0, 0, 0)
        expected = [[0]]
        assert result == expected

    def test_single_cell_no_change(self):
        image = [[1]]
        result = flood_fill(image, 0, 0, 1)
        expected = [[1]]
        assert result == expected

    def test_fill_entire_grid(self):
        image = [[1, 1, 1], [1, 1, 1], [1, 1, 1]]
        result = flood_fill(image, 1, 1, 0)
        expected = [[0, 0, 0], [0, 0, 0], [0, 0, 0]]
        assert result == expected

    def test_no_connected_region(self):
        image = [[0, 1, 0], [1, 0, 1], [0, 1, 0]]
        result = flood_fill(image, 1, 1, 1)
        expected = [[0, 1, 0], [1, 1, 1], [0, 1, 0]]
        assert result == expected

    def test_corner_cell(self):
        image = [[1, 0, 0], [0, 0, 0], [0, 0, 1]]
        result = flood_fill(image, 0, 0, 2)
        expected = [[2, 0, 0], [0, 0, 0], [0, 0, 1]]
        assert result == expected

    def test_l_shaped_region(self):
        image = [[1, 1, 0], [1, 0, 0], [1, 0, 1]]
        result = flood_fill(image, 0, 0, 2)
        expected = [[2, 2, 0], [2, 0, 0], [2, 0, 1]]
        assert result == expected

    def test_complex_pattern(self):
        image = [[1, 0, 1, 1], [0, 1, 1, 0], [1, 1, 0, 1], [1, 0, 1, 1]]
        result = flood_fill(image, 1, 1, 2)
        expected = [[1, 0, 2, 2], [0, 2, 2, 0], [2, 2, 0, 1], [2, 0, 1, 1]]
        assert result == expected

    def test_rectangular_grid(self):
        image = [[1, 1, 1, 1, 1], [0, 0, 0, 0, 0]]
        result = flood_fill(image, 0, 2, 0)
        expected = [[0, 0, 0, 0, 0], [0, 0, 0, 0, 0]]
        assert result == expected

    def test_original_image_not_modified(self):
        original = [[1, 1, 0], [1, 0, 1]]
        image_copy = [row[:] for row in original]
        flood_fill(image_copy, 0, 0, 2)
        # Original should remain unchanged
        assert original == [[1, 1, 0], [1, 0, 1]]
