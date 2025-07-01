def remove_duplicate_customers(customer_ids: list[int]) -> tuple[int, ...]:
    """
    問い合わせ履歴から重複した顧客IDを除去し、最初に出現した順序を保持したタプルとして返す関数。

    Args:
        customer_ids (list[int]): 顧客IDのリスト

    Returns:
        tuple[int, ...]: 重複を除いた顧客IDのタプル
    """
    seen = set()  # 既に出現した顧客IDを記録するためのセット
    unique_ids = []  # 順序を保持するためのリスト

    for cid in customer_ids:
        if cid not in seen:
            seen.add(cid)
            unique_ids.append(cid)

    return tuple(unique_ids)
