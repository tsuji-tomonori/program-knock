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

    def test_maximum_size_grid(self):
        """
        最大サイズ（50x50）でのパフォーマンステスト。
        """
        # 50x50のグリッドを作成し、中央に小さなパターンを配置
        board = [[0 for _ in range(50)] for _ in range(50)]

        # 中央にblinkerパターンを配置
        center = 25
        board[center - 1][center] = 1
        board[center][center] = 1
        board[center + 1][center] = 1

        result = next_generation(board)

        # 結果のグリッドサイズが正しいことを確認
        assert len(result) == 50
        assert len(result[0]) == 50

        # blinkerが回転していることを確認
        assert result[center][center - 1] == 1
        assert result[center][center] == 1
        assert result[center][center + 1] == 1

    def test_all_alive_grid(self):
        """
        全てのセルが生きている場合のテスト。
        """
        board = [[1, 1, 1], [1, 1, 1], [1, 1, 1]]
        expected = [[1, 0, 1], [0, 0, 0], [1, 0, 1]]
        assert next_generation(board) == expected

    def test_checkerboard_pattern(self):
        """
        チェッカーボードパターンでのテスト。
        チェッカーボードパターンは特定の形に変化する。
        """
        board = [[1, 0, 1, 0], [0, 1, 0, 1], [1, 0, 1, 0], [0, 1, 0, 1]]
        expected = [[0, 1, 1, 0], [1, 0, 0, 1], [1, 0, 0, 1], [0, 1, 1, 0]]
        assert next_generation(board) == expected

    def test_rectangular_grid(self):
        """
        非正方形（長方形）のグリッドでのテスト。
        """
        board = [[0, 1, 0, 1, 0, 1], [1, 0, 1, 0, 1, 0], [0, 1, 0, 1, 0, 1]]
        expected = [[0, 1, 1, 1, 1, 0], [1, 0, 0, 0, 0, 1], [0, 1, 1, 1, 1, 0]]
        assert next_generation(board) == expected
