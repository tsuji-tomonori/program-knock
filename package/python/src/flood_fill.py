def flood_fill(
    image: list[list[int]], sr: int, sc: int, new_color: int
) -> list[list[int]]:
    """
    画像内の特定の領域を、新しい色で塗りつぶす。

    Args:
        image (list[list[int]]): 2次元のグリッド画像
        sr (int): 開始行のインデックス
        sc (int): 開始列のインデックス
        new_color (int): 塗りつぶしに使用する色

    Returns:
        list[list[int]]: 塗りつぶし後の画像
    """
    if not image or not image[0]:
        return image

    rows, cols = len(image), len(image[0])
    original_color = image[sr][sc]

    # If the starting color is already the new color, no change needed
    if original_color == new_color:
        return image

    # Create a deep copy of the image to avoid modifying the original
    result = [row[:] for row in image]

    def dfs(r: int, c: int) -> None:
        # Check bounds and if current cell has the original color
        if r < 0 or r >= rows or c < 0 or c >= cols or result[r][c] != original_color:
            return

        # Change the color
        result[r][c] = new_color

        # Recursively fill adjacent cells (up, down, left, right)
        dfs(r - 1, c)  # up
        dfs(r + 1, c)  # down
        dfs(r, c - 1)  # left
        dfs(r, c + 1)  # right

    dfs(sr, sc)
    return result
