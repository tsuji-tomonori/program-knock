from src.life_game import next_generation


class TestLifeGame:
    def test_sample_1(self):
        board = [[0, 1, 0], [0, 1, 1], [1, 0, 0]]
        expected = [[0, 1, 1], [1, 1, 1], [0, 1, 0]]
        assert next_generation(board) == expected

    def test_sample_2(self):
        board = [[1, 1, 1], [1, 1, 1], [1, 1, 1]]
        expected = [[1, 0, 1], [0, 0, 0], [1, 0, 1]]
        assert next_generation(board) == expected

    def test_empty_board(self):
        board = [[0, 0, 0], [0, 0, 0], [0, 0, 0]]
        expected = [[0, 0, 0], [0, 0, 0], [0, 0, 0]]
        assert next_generation(board) == expected

    def test_single_cell_dies(self):
        board = [[0, 0, 0], [0, 1, 0], [0, 0, 0]]
        expected = [[0, 0, 0], [0, 0, 0], [0, 0, 0]]
        assert next_generation(board) == expected

    def test_two_cells_die(self):
        board = [[0, 0, 0], [1, 1, 0], [0, 0, 0]]
        expected = [[0, 0, 0], [0, 0, 0], [0, 0, 0]]
        assert next_generation(board) == expected

    def test_three_cells_in_line_stay_alive(self):
        board = [[0, 0, 0], [1, 1, 1], [0, 0, 0]]
        expected = [[0, 1, 0], [0, 1, 0], [0, 1, 0]]
        assert next_generation(board) == expected

    def test_block_pattern_stable(self):
        board = [[0, 0, 0, 0], [0, 1, 1, 0], [0, 1, 1, 0], [0, 0, 0, 0]]
        expected = [[0, 0, 0, 0], [0, 1, 1, 0], [0, 1, 1, 0], [0, 0, 0, 0]]
        assert next_generation(board) == expected

    def test_blinker_pattern(self):
        board = [
            [0, 0, 0, 0, 0],
            [0, 0, 1, 0, 0],
            [0, 0, 1, 0, 0],
            [0, 0, 1, 0, 0],
            [0, 0, 0, 0, 0],
        ]
        expected = [
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 1, 1, 1, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
        ]
        assert next_generation(board) == expected

    def test_corner_cell(self):
        board = [[1, 1, 0], [1, 0, 0], [0, 0, 0]]
        expected = [[1, 1, 0], [1, 1, 0], [0, 0, 0]]
        assert next_generation(board) == expected

    def test_large_grid(self):
        board = [
            [0, 0, 0, 0, 0, 0],
            [0, 0, 1, 1, 0, 0],
            [0, 0, 1, 1, 0, 0],
            [0, 0, 0, 0, 0, 0],
            [0, 1, 1, 1, 0, 0],
            [0, 0, 0, 0, 0, 0],
        ]
        expected = [
            [0, 0, 0, 0, 0, 0],
            [0, 0, 1, 1, 0, 0],
            [0, 0, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 0],
            [0, 0, 1, 0, 0, 0],
            [0, 0, 1, 0, 0, 0],
        ]
        assert next_generation(board) == expected

    def test_single_row(self):
        board = [[1, 0, 1, 0, 1]]
        expected = [[0, 0, 0, 0, 0]]
        assert next_generation(board) == expected
