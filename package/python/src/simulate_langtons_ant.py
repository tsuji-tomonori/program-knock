def simulate_langtons_ant(steps: int) -> list[tuple[int, int]]:
    """
    ラングトンのアリを指定したステップ数移動させた後の黒いマスの座標を求める。

    Args:
        steps (int): アリが移動するステップ数 (0 <= steps <= 10_000)

    Returns:
        list[tuple[int, int]]: 黒いマスの座標のリスト。各座標は (x, y) のタプル形式で返す。
    """

    # 黒いマスの座標を保持するセット（最初はすべて白なので空）
    black_cells: set[tuple[int, int]] = set()

    # アリの初期位置・初期方向
    x, y = 0, 0
    # 方向を示すベクトル（上, 右, 下, 左）
    directions = [(0, 1), (1, 0), (0, -1), (-1, 0)]
    # 初期は上向き ( directions のインデックス 0 が上向き )
    dir_index = 0

    for _ in range(steps):
        if (x, y) in black_cells:
            # 黒いマス → 白に変更 → 左へ 90° 回転
            black_cells.remove((x, y))
            dir_index = (dir_index - 1) % 4
        else:
            # 白いマス → 黒に変更 → 右へ 90° 回転
            black_cells.add((x, y))
            dir_index = (dir_index + 1) % 4

        # 選択された方向へ 1 マス進む
        dx, dy = directions[dir_index]
        x += dx
        y += dy

    # 黒いマスの座標を (x, y) の昇順にソートしてリストで返す
    return sorted(black_cells)
