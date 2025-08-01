from src.simulate_langtons_ant import simulate_langtons_ant


def test_steps_0():
    """
    サンプル1: ステップ数0
    まだ移動していないため、黒いマスは存在しない。
    出力: []
    """
    assert simulate_langtons_ant(0) == []


def test_steps_1():
    """
    サンプル2: ステップ数1
    初期位置(0, 0)が白→黒になり右折して(1, 0)へ。
    黒いマスは[(0, 0)]。
    """
    assert simulate_langtons_ant(1) == [(0, 0)]


def test_steps_2():
    """
    ステップ数2
    1ステップ目: (0,0) 白→黒, 右折→(1,0)へ
    2ステップ目: (1,0) 白→黒, 右折→(1,-1)へ
    黒いマス: (0,0), (1,0)
    ソート結果: [(0, 0), (1, 0)]
    """
    assert simulate_langtons_ant(2) == [(0, 0), (1, 0)]


def test_steps_3():
    """
    ステップ数3
    3ステップ目までに黒くなったマス: (0,0), (1,0), (1,-1)
    ソート結果: [(0, 0), (1, -1), (1, 0)]
    """
    assert simulate_langtons_ant(3) == [(0, 0), (1, -1), (1, 0)]


def test_steps_4():
    """
    ステップ数4
    4ステップ目までに黒くなったマス: (0,0), (1,0), (1,-1), (0,-1)
    ソート結果: [(0, -1), (0, 0), (1, -1), (1, 0)]
    """
    assert simulate_langtons_ant(4) == [(0, -1), (0, 0), (1, -1), (1, 0)]


def test_steps_5():
    """
    サンプル3: ステップ数5
    黒いマスのリスト: [(0, -1), (1, -1), (1, 0)]
    """
    assert simulate_langtons_ant(5) == [(0, -1), (1, -1), (1, 0)]


def test_steps_6():
    """
    ステップ数6
    黒いマスのリスト: [(-1, 0), (0, -1), (1, -1), (1, 0)]
    """
    assert simulate_langtons_ant(6) == [(-1, 0), (0, -1), (1, -1), (1, 0)]


def test_steps_7():
    """
    ステップ数7
    黒いマスのリスト: [(-1, 0), (-1, 1), (0, -1), (1, -1), (1, 0)]
    """
    assert simulate_langtons_ant(7) == [(-1, 0), (-1, 1), (0, -1), (1, -1), (1, 0)]


def test_steps_10():
    """
    サンプル4: ステップ数10
    黒いマスのリスト(ソート済み):
    [(-2, 0), (-1, 0), (-1, 1), (0, -1), (0, 1), (1, -1)]
    """
    assert simulate_langtons_ant(10) == [
        (-1, 1),
        (0, -1),
        (0, 0),
        (0, 1),
        (1, -1),
        (1, 0),
    ]


def test_steps_10000():
    """
    境界値テスト: ステップ数10000
    """
    result = simulate_langtons_ant(10000)
    assert result == [
        (-19, 12),
        (-19, 13),
        (-18, 9),
        (-18, 10),
        (-18, 13),
        (-18, 14),
        (-17, 6),
        (-17, 7),
        (-17, 10),
        (-17, 12),
        (-17, 13),
        (-17, 17),
        (-16, -5),
        (-16, -4),
        (-16, -3),
        (-16, -2),
        (-16, 5),
        (-16, 7),
        (-16, 8),
        (-16, 9),
        (-16, 10),
        (-16, 12),
        (-16, 15),
        (-16, 16),
        (-16, 17),
        (-15, -6),
        (-15, -1),
        (-15, 4),
        (-15, 5),
        (-15, 6),
        (-15, 7),
        (-15, 9),
        (-15, 10),
        (-15, 11),
        (-15, 12),
        (-15, 13),
        (-15, 17),
        (-14, -7),
        (-14, -6),
        (-14, -5),
        (-14, 0),
        (-14, 4),
        (-14, 6),
        (-14, 7),
        (-14, 8),
        (-14, 13),
        (-14, 15),
        (-14, 16),
        (-14, 18),
        (-13, -7),
        (-13, -6),
        (-13, -5),
        (-13, 0),
        (-13, 2),
        (-13, 3),
        (-13, 9),
        (-13, 11),
        (-13, 12),
        (-13, 15),
        (-13, 17),
        (-13, 18),
        (-12, -6),
        (-12, -1),
        (-12, 3),
        (-12, 4),
        (-12, 6),
        (-12, 8),
        (-12, 14),
        (-12, 15),
        (-11, -6),
        (-11, -4),
        (-11, 3),
        (-11, 5),
        (-11, 6),
        (-11, 7),
        (-11, 8),
        (-11, 9),
        (-11, 12),
        (-11, 16),
        (-10, -7),
        (-10, -3),
        (-10, -2),
        (-10, -1),
        (-10, 0),
        (-10, 1),
        (-10, 12),
        (-10, 13),
        (-10, 15),
        (-10, 16),
        (-10, 17),
        (-10, 18),
        (-10, 19),
        (-10, 20),
        (-9, -7),
        (-9, -6),
        (-9, -5),
        (-9, -2),
        (-9, -1),
        (-9, 2),
        (-9, 4),
        (-9, 5),
        (-9, 7),
        (-9, 9),
        (-9, 11),
        (-9, 15),
        (-9, 16),
        (-9, 18),
        (-9, 20),
        (-9, 21),
        (-8, -9),
        (-8, -8),
        (-8, -5),
        (-8, -3),
        (-8, -2),
        (-8, -1),
        (-8, 0),
        (-8, 1),
        (-8, 2),
        (-8, 3),
        (-8, 5),
        (-8, 9),
        (-8, 12),
        (-8, 13),
        (-8, 14),
        (-8, 19),
        (-8, 20),
        (-8, 22),
        (-7, -10),
        (-7, -7),
        (-7, -4),
        (-7, -3),
        (-7, -2),
        (-7, -1),
        (-7, 0),
        (-7, 1),
        (-7, 3),
        (-7, 4),
        (-7, 8),
        (-7, 11),
        (-7, 13),
        (-7, 14),
        (-7, 18),
        (-7, 22),
        (-6, -11),
        (-6, -6),
        (-6, -4),
        (-6, -2),
        (-6, -1),
        (-6, 1),
        (-6, 4),
        (-6, 5),
        (-6, 6),
        (-6, 7),
        (-6, 8),
        (-6, 9),
        (-6, 11),
        (-6, 12),
        (-6, 13),
        (-6, 14),
        (-6, 15),
        (-6, 16),
        (-6, 17),
        (-6, 21),
        (-5, -11),
        (-5, -9),
        (-5, -8),
        (-5, -7),
        (-5, -6),
        (-5, -4),
        (-5, -3),
        (-5, -1),
        (-5, 1),
        (-5, 2),
        (-5, 3),
        (-5, 4),
        (-5, 9),
        (-5, 10),
        (-5, 13),
        (-5, 14),
        (-5, 16),
        (-5, 18),
        (-5, 19),
        (-5, 21),
        (-4, -10),
        (-4, -5),
        (-4, -4),
        (-4, -3),
        (-4, -2),
        (-4, 2),
        (-4, 5),
        (-4, 7),
        (-4, 8),
        (-4, 9),
        (-4, 10),
        (-4, 11),
        (-4, 12),
        (-4, 14),
        (-4, 15),
        (-4, 20),
        (-4, 21),
        (-4, 22),
        (-3, -7),
        (-3, -3),
        (-3, -1),
        (-3, 0),
        (-3, 2),
        (-3, 4),
        (-3, 5),
        (-3, 6),
        (-3, 8),
        (-3, 11),
        (-3, 12),
        (-3, 15),
        (-3, 16),
        (-3, 20),
        (-3, 21),
        (-3, 22),
        (-2, -4),
        (-2, -3),
        (-2, -2),
        (-2, -1),
        (-2, 0),
        (-2, 1),
        (-2, 2),
        (-2, 7),
        (-2, 10),
        (-2, 11),
        (-2, 13),
        (-2, 14),
        (-2, 16),
        (-2, 22),
        (-1, -12),
        (-1, -11),
        (-1, -10),
        (-1, -9),
        (-1, -6),
        (-1, -5),
        (-1, -3),
        (-1, -2),
        (-1, 1),
        (-1, 2),
        (-1, 3),
        (-1, 4),
        (-1, 6),
        (-1, 7),
        (-1, 9),
        (-1, 10),
        (-1, 12),
        (-1, 13),
        (-1, 16),
        (-1, 22),
        (0, -13),
        (0, -8),
        (0, -6),
        (0, -2),
        (0, -1),
        (0, 0),
        (0, 2),
        (0, 3),
        (0, 5),
        (0, 6),
        (0, 7),
        (0, 12),
        (0, 14),
        (0, 15),
        (0, 16),
        (0, 17),
        (0, 22),
        (1, -14),
        (1, -13),
        (1, -12),
        (1, -4),
        (1, -3),
        (1, -2),
        (1, 0),
        (1, 2),
        (1, 4),
        (1, 5),
        (1, 6),
        (1, 7),
        (1, 8),
        (1, 13),
        (1, 15),
        (1, 22),
        (2, -14),
        (2, -12),
        (2, -8),
        (2, -7),
        (2, -6),
        (2, -4),
        (2, -3),
        (2, -2),
        (2, -1),
        (2, 1),
        (2, 2),
        (2, 4),
        (2, 8),
        (2, 9),
        (2, 11),
        (2, 12),
        (2, 13),
        (2, 15),
        (2, 16),
        (2, 22),
        (3, -8),
        (3, -7),
        (3, -5),
        (3, -4),
        (3, -1),
        (3, 0),
        (3, 1),
        (3, 2),
        (3, 7),
        (3, 8),
        (3, 9),
        (3, 10),
        (3, 12),
        (3, 14),
        (3, 16),
        (3, 22),
        (4, -13),
        (4, -8),
        (4, -5),
        (4, -4),
        (4, 0),
        (4, 1),
        (4, 2),
        (4, 5),
        (4, 6),
        (4, 7),
        (4, 13),
        (4, 14),
        (4, 15),
        (4, 22),
        (5, -13),
        (5, -12),
        (5, -8),
        (5, -7),
        (5, -5),
        (5, -4),
        (5, -3),
        (5, -1),
        (5, 0),
        (5, 1),
        (5, 2),
        (5, 5),
        (5, 12),
        (5, 13),
        (5, 14),
        (5, 18),
        (5, 19),
        (5, 22),
        (6, -13),
        (6, -12),
        (6, -10),
        (6, -8),
        (6, -7),
        (6, -6),
        (6, -5),
        (6, 1),
        (6, 5),
        (6, 8),
        (6, 10),
        (6, 11),
        (6, 13),
        (6, 14),
        (6, 15),
        (6, 17),
        (6, 18),
        (6, 22),
        (7, -14),
        (7, -13),
        (7, -12),
        (7, -11),
        (7, -9),
        (7, -8),
        (7, -4),
        (7, -3),
        (7, -1),
        (7, 0),
        (7, 1),
        (7, 2),
        (7, 5),
        (7, 7),
        (7, 10),
        (7, 13),
        (7, 16),
        (7, 17),
        (7, 18),
        (7, 22),
        (8, -14),
        (8, -12),
        (8, -11),
        (8, -9),
        (8, -8),
        (8, -7),
        (8, -4),
        (8, -2),
        (8, 0),
        (8, 1),
        (8, 3),
        (8, 5),
        (8, 11),
        (8, 13),
        (8, 19),
        (8, 21),
        (9, -10),
        (9, -8),
        (9, -5),
        (9, 0),
        (9, 1),
        (9, 3),
        (9, 4),
        (9, 7),
        (9, 9),
        (9, 12),
        (9, 13),
        (9, 14),
        (9, 16),
        (9, 17),
        (10, -10),
        (10, -9),
        (10, -7),
        (10, -2),
        (10, 1),
        (10, 2),
        (10, 3),
        (10, 4),
        (10, 5),
        (10, 7),
        (10, 12),
        (10, 17),
        (10, 20),
        (10, 22),
        (11, -11),
        (11, -9),
        (11, -8),
        (11, -6),
        (11, -3),
        (11, 2),
        (11, 3),
        (11, 5),
        (11, 6),
        (11, 8),
        (11, 11),
        (11, 12),
        (11, 13),
        (11, 20),
        (11, 21),
        (11, 22),
        (12, -13),
        (12, -11),
        (12, -7),
        (12, -4),
        (12, -1),
        (12, 2),
        (12, 5),
        (12, 6),
        (12, 7),
        (12, 11),
        (12, 12),
        (12, 15),
        (12, 16),
        (12, 21),
        (13, -14),
        (13, -13),
        (13, -12),
        (13, -10),
        (13, -8),
        (13, -7),
        (13, -6),
        (13, -5),
        (13, -4),
        (13, -2),
        (13, -1),
        (13, 0),
        (13, 1),
        (13, 2),
        (13, 3),
        (13, 5),
        (13, 6),
        (13, 7),
        (13, 9),
        (13, 10),
        (13, 11),
        (13, 12),
        (13, 13),
        (13, 14),
        (13, 15),
        (13, 17),
        (13, 19),
        (13, 20),
        (14, -14),
        (14, -12),
        (14, -10),
        (14, -5),
        (14, -4),
        (14, -3),
        (14, -2),
        (14, -1),
        (14, 3),
        (14, 4),
        (14, 7),
        (14, 8),
        (14, 9),
        (14, 10),
        (14, 11),
        (14, 13),
        (14, 14),
        (14, 15),
        (14, 16),
        (14, 17),
        (15, -12),
        (15, -9),
        (15, -8),
        (15, -4),
        (15, 3),
        (15, 6),
        (15, 8),
        (15, 9),
        (15, 12),
        (15, 13),
        (15, 14),
        (15, 16),
        (15, 17),
        (15, 18),
        (16, -15),
        (16, -14),
        (16, -13),
        (16, -12),
        (16, -8),
        (16, -7),
        (16, -6),
        (16, -5),
        (16, -4),
        (16, -2),
        (16, -1),
        (16, 0),
        (16, 1),
        (16, 2),
        (16, 3),
        (16, 4),
        (16, 5),
        (16, 6),
        (16, 10),
        (16, 12),
        (17, -20),
        (17, -19),
        (17, -14),
        (17, -11),
        (17, -5),
        (17, -4),
        (17, -3),
        (17, -1),
        (17, 1),
        (17, 5),
        (17, 7),
        (17, 8),
        (17, 9),
        (17, 12),
        (17, 13),
        (17, 14),
        (18, -21),
        (18, -18),
        (18, -15),
        (18, -14),
        (18, -13),
        (18, -12),
        (18, -10),
        (18, -9),
        (18, -5),
        (18, -4),
        (18, -3),
        (18, -1),
        (18, 0),
        (18, 4),
        (18, 5),
        (18, 6),
        (18, 8),
        (18, 9),
        (18, 15),
        (18, 16),
        (19, -22),
        (19, -21),
        (19, -20),
        (19, -15),
        (19, -13),
        (19, -12),
        (19, -10),
        (19, -8),
        (19, -7),
        (19, -6),
        (19, -5),
        (19, -4),
        (19, 0),
        (19, 5),
        (19, 8),
        (19, 11),
        (19, 12),
        (19, 14),
        (19, 15),
        (19, 16),
        (20, -22),
        (20, -20),
        (20, -19),
        (20, -18),
        (20, -17),
        (20, -16),
        (20, -14),
        (20, -12),
        (20, -8),
        (20, -7),
        (20, -4),
        (20, -3),
        (20, 3),
        (20, 8),
        (20, 12),
        (20, 15),
        (21, -18),
        (21, -17),
        (21, -16),
        (21, -15),
        (21, -14),
        (21, -13),
        (21, -11),
        (21, -10),
        (21, -9),
        (21, -8),
        (21, -5),
        (21, -4),
        (21, -2),
        (21, 2),
        (21, 5),
        (21, 6),
        (21, 9),
        (21, 11),
        (21, 13),
        (21, 14),
        (22, -20),
        (22, -19),
        (22, -12),
        (22, -10),
        (22, -9),
        (22, -8),
        (22, -6),
        (22, -5),
        (22, -2),
        (22, -1),
        (22, 0),
        (22, 1),
        (22, 5),
        (22, 9),
        (22, 10),
        (22, 11),
        (23, -19),
        (23, -16),
        (23, -14),
        (23, -13),
        (23, -12),
        (23, -11),
        (23, -10),
        (23, -7),
        (23, -3),
        (23, -1),
        (23, 0),
        (23, 4),
        (23, 7),
        (23, 10),
        (24, -19),
        (24, -18),
        (24, -16),
        (24, -15),
        (24, -14),
        (24, -12),
        (24, -11),
        (24, -10),
        (24, -9),
        (24, -8),
        (24, -7),
        (24, -6),
        (24, 0),
        (24, 6),
        (24, 8),
        (24, 9),
        (25, -20),
        (25, -18),
        (25, -15),
        (25, -14),
        (25, -12),
        (25, -11),
        (25, -4),
        (25, 0),
        (25, 1),
        (25, 6),
        (26, -21),
        (26, -18),
        (26, -16),
        (26, -15),
        (26, -14),
        (26, -13),
        (26, -4),
        (26, -3),
        (26, -2),
        (26, 1),
        (26, 2),
        (26, 5),
        (27, -21),
        (27, -19),
        (27, -18),
        (27, -16),
        (27, -15),
        (27, -14),
        (27, -1),
        (27, 0),
        (27, 3),
        (27, 4),
        (28, -20),
        (28, -19),
        (29, -19),
        (29, -18),
    ]
