/**
 * 画像内の特定の領域を、新しい色で塗りつぶす。
 *
 * @param image - 2次元のグリッド画像
 * @param sr - 開始行のインデックス
 * @param sc - 開始列のインデックス
 * @param newColor - 塗りつぶしに使用する色
 * @returns 塗りつぶし後の画像
 */
export function floodFill(
  image: number[][],
  sr: number,
  sc: number,
  newColor: number
): number[][] {
  if (!image || !image[0]) {
    return image;
  }

  const rows = image.length;
  const cols = image[0].length;
  const originalColor = image[sr][sc];

  // If the starting color is already the new color, no change needed
  if (originalColor === newColor) {
    return image;
  }

  // Create a deep copy of the image to avoid modifying the original
  const result = image.map(row => [...row]);

  function dfs(r: number, c: number): void {
    // Check bounds and if current cell has the original color
    if (r < 0 || r >= rows || c < 0 || c >= cols || result[r][c] !== originalColor) {
      return;
    }

    // Change the color
    result[r][c] = newColor;

    // Recursively fill adjacent cells (up, down, left, right)
    dfs(r - 1, c); // up
    dfs(r + 1, c); // down
    dfs(r, c - 1); // left
    dfs(r, c + 1); // right
  }

  dfs(sr, sc);
  return result;
}
