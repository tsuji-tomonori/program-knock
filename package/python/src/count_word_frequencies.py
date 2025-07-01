def count_word_frequencies(text: str) -> dict[str, int]:
    """
    入力されたテキスト中の各単語の出現回数をカウントする関数.

    Args:
        text (str): 空白区切りの単語を含む文字列。

    Returns:
        dict[str, int]: 各単語をキーとし、その出現回数を値とする辞書。
    """
    # 空文字の場合は空辞書を返す
    if not text:
        return {}

    # スペース区切りで単語をリスト化
    words = text.split()

    # 単語の出現回数をカウント
    frequency_map: dict[str, int] = {}
    for w in words:
        frequency_map[w] = frequency_map.get(w, 0) + 1

    # ソート: 出現回数(降順), 単語(昇順)
    # items() で (単語, 出現回数) のタプルを取り出し、
    # key 引数で (-出現回数, 単語) の順にソート
    sorted_items = sorted(frequency_map.items(), key=lambda x: (-x[1], x[0]))

    # ソート結果をもとに再度辞書化（Python 3.7+ では挿入順が保持される）
    return dict(sorted_items)
