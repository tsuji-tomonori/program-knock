/**
 * ラングトンのアリを指定したステップ数移動させた後の黒いマスの座標を求める。
 *
 * @param steps - アリが移動するステップ数 (0 <= steps <= 10_000)
 * @returns 黒いマスの座標のリスト。各座標は [x, y] の配列形式で返す。
 */
export function simulateLangtonsAnt(steps: number): Array<[number, number]> {
  // 黒いマスの座標を保持するセット（最初はすべて白なので空）
  const blackCells = new Set<string>();

  // アリの初期位置・初期方向
  let x = 0;
  let y = 0;
  // 方向を示すベクトル（上, 右, 下, 左）
  const directions: Array<[number, number]> = [[0, 1], [1, 0], [0, -1], [-1, 0]];
  // 初期は上向き ( directions のインデックス 0 が上向き )
  let dirIndex = 0;

  for (let step = 0; step < steps; step++) {
    const cellKey = `${x},${y}`;

    if (blackCells.has(cellKey)) {
      // 黒いマス → 白に変更 → 左へ 90° 回転
      blackCells.delete(cellKey);
      dirIndex = (dirIndex - 1 + 4) % 4;
    } else {
      // 白いマス → 黒に変更 → 右へ 90° 回転
      blackCells.add(cellKey);
      dirIndex = (dirIndex + 1) % 4;
    }

    // 選択された方向へ 1 マス進む
    const [dx, dy] = directions[dirIndex];
    x += dx;
    y += dy;
  }

  // 黒いマスの座標を (x, y) の昇順にソートしてリストで返す
  const result: Array<[number, number]> = [];
  for (const cellKey of blackCells) {
    const [cellX, cellY] = cellKey.split(',').map(Number);
    result.push([cellX, cellY]);
  }

  result.sort((a, b) => a[0] - b[0] || a[1] - b[1]);
  return result;
}
