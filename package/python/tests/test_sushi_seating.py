from src.sushi_seating import sushi_seating


class TestSushiSeating:
    def test_sample_1(self):
        commands = [
            "arrive Alice",
            "arrive Bob",
            "seat 1",
            "arrive Charlie",
            "seat 2",
            "arrive Dave",
            "arrive Eve",
            "seat 3",
        ]
        expected = ["Alice", "Bob", "Charlie", "Dave", "Eve"]
        assert sushi_seating(commands) == expected

    def test_sample_2(self):
        commands = [
            "arrive Tom",
            "arrive Jerry",
            "arrive Spike",
            "seat 2",
            "arrive Butch",
            "seat 2",
        ]
        expected = ["Tom", "Jerry", "Spike", "Butch"]
        assert sushi_seating(commands) == expected

    def test_sample_3(self):
        commands = ["arrive Anna", "arrive Elsa", "seat 5"]
        expected = ["Anna", "Elsa"]
        assert sushi_seating(commands) == expected

    def test_empty_commands(self):
        commands = []
        expected = []
        assert sushi_seating(commands) == expected

    def test_only_arrivals(self):
        commands = ["arrive Alice", "arrive Bob", "arrive Charlie"]
        expected = []
        assert sushi_seating(commands) == expected

    def test_only_seating(self):
        commands = ["seat 1", "seat 2", "seat 5"]
        expected = []
        assert sushi_seating(commands) == expected

    def test_seat_zero(self):
        commands = ["arrive Alice", "seat 0", "seat 1"]
        expected = ["Alice"]
        assert sushi_seating(commands) == expected

    def test_duplicate_arrivals_ignored(self):
        commands = [
            "arrive Alice",
            "arrive Alice",  # Should be ignored
            "arrive Bob",
            "seat 2",
        ]
        expected = ["Alice", "Bob"]
        assert sushi_seating(commands) == expected

    def test_multiple_seat_commands(self):
        commands = [
            "arrive A",
            "arrive B",
            "arrive C",
            "arrive D",
            "seat 1",
            "seat 1",
            "seat 1",
            "seat 1",
        ]
        expected = ["A", "B", "C", "D"]
        assert sushi_seating(commands) == expected

    def test_mixed_operations(self):
        commands = [
            "arrive John",
            "seat 1",
            "arrive Jane",
            "arrive Jack",
            "seat 1",
            "arrive Jill",
            "seat 2",
        ]
        expected = ["John", "Jane", "Jack", "Jill"]
        assert sushi_seating(commands) == expected

    def test_large_seat_number(self):
        commands = [
            "arrive A",
            "arrive B",
            "seat 100",  # Much larger than queue size
        ]
        expected = ["A", "B"]
        assert sushi_seating(commands) == expected

    def test_fifo_order_maintained(self):
        commands = [
            "arrive First",
            "arrive Second",
            "arrive Third",
            "arrive Fourth",
            "seat 2",
            "arrive Fifth",
            "seat 3",
        ]
        expected = ["First", "Second", "Third", "Fourth", "Fifth"]
        assert sushi_seating(commands) == expected

    def test_large_performance(self):
        """
        大規模パフォーマンステスト。
        1000人の顧客と多数の席案内をテスト。
        """
        commands = []
        # 1000人の顧客が到着
        for i in range(1000):
            commands.append(f"arrive Customer{i:04d}")

        # 100人ずつ10回に分けて席案内
        for _i in range(10):
            commands.append("seat 100")

        result = sushi_seating(commands)

        # 全ての顧客が正しい順序で席に案内されていることを確認
        expected = [f"Customer{i:04d}" for i in range(1000)]
        assert result == expected

    def test_negative_seat_numbers(self):
        """
        負の席数での動作テスト。
        """
        commands = [
            "arrive Alice",
            "arrive Bob",
            "seat -1",  # 負の値
            "seat 0",  # ゼロ
            "seat 1",  # 正の値
        ]
        expected = ["Alice"]
        assert sushi_seating(commands) == expected

    def test_very_long_customer_names(self):
        """
        非常に長い顧客名でのテスト。
        """
        long_name = "A" * 100  # 100文字の名前
        commands = [
            f"arrive {long_name}",
            "arrive Bob",
            "seat 2",
        ]
        expected = [long_name, "Bob"]
        assert sushi_seating(commands) == expected

    def test_special_characters_in_names(self):
        """
        特殊文字を含む顧客名でのテスト。
        """
        commands = [
            "arrive Alice-123",
            "arrive Bob_456",
            "arrive Charlie.789",
            "seat 3",
        ]
        expected = ["Alice-123", "Bob_456", "Charlie.789"]
        assert sushi_seating(commands) == expected
