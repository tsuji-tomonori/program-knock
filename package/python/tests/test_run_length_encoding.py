from src.run_length_encoding import run_length_encoding


# サンプルテストケース
def test_sample1():
    # "aaabbcdddd" -> [("a", 3), ("b", 2), ("c", 1), ("d", 4)]
    assert run_length_encoding("aaabbcdddd") == [("a", 3), ("b", 2), ("c", 1), ("d", 4)]


def test_sample2():
    # "abc" -> [("a", 1), ("b", 1), ("c", 1)]
    assert run_length_encoding("abc") == [("a", 1), ("b", 1), ("c", 1)]


def test_sample3():
    # "aaaaaaa" -> [("a", 7)]
    assert run_length_encoding("aaaaaaa") == [("a", 7)]


# その他のテストケース
def test_alternating_characters():
    # 交互に並ぶ文字: 各文字が1回ずつ連続する
    s = "abababab"
    expected = [
        ("a", 1),
        ("b", 1),
        ("a", 1),
        ("b", 1),
        ("a", 1),
        ("b", 1),
        ("a", 1),
        ("b", 1),
    ]
    assert run_length_encoding(s) == expected


def test_single_character_max_length():
    # 境界値テスト: 最大文字数 100,000 の同一文字
    s = "a" * 100000
    expected = [("a", 100000)]
    assert run_length_encoding(s) == expected


def test_non_repeating_characters():
    # 連続していない全て異なる文字（アルファベット順）
    s = "abcdefghijklmnopqrstuvwxyz"
    expected = [(chr(c), 1) for c in range(ord("a"), ord("z") + 1)]
    assert run_length_encoding(s) == expected


def test_varied_repetition():
    # 各文字の繰り返し回数が異なる
    s = "aabbbccccddddd"
    expected = [("a", 2), ("b", 3), ("c", 4), ("d", 5)]
    assert run_length_encoding(s) == expected


def test_mixed_groups():
    # 連続グループが混在するケース
    s = "zzzzzyxx"
    expected = [("z", 5), ("y", 1), ("x", 2)]
    assert run_length_encoding(s) == expected


def test_increasing_group_sizes():
    # グループのサイズが順に増加するケース
    s = "abbcccddddeeeee"
    expected = [("a", 1), ("b", 2), ("c", 3), ("d", 4), ("e", 5)]
    assert run_length_encoding(s) == expected


def test_minimum_length_string():
    # 境界値テスト: 最小の文字列（長さ1）
    s = "a"
    expected = [("a", 1)]
    assert run_length_encoding(s) == expected
