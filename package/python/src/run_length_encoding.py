from itertools import groupby


def run_length_encoding(s: str) -> list[tuple[str, int]]:
    """
    文字列 s をランレングス符号化する関数

    Args:
        s (str): 符号化する文字列（英小文字のみ、1文字以上）

    Returns:
        list[tuple[str, int]]: 連続する文字とその回数のタプルからなるリスト
    """
    # itertools.groupby を用いて連続する文字のグループを作成し、
    # 各グループの要素数を数える
    return [(char, sum(1 for _ in group)) for char, group in groupby(s)]
