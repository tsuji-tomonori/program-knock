from typing import NamedTuple


class Sale(NamedTuple):
    store: str  # 店舗名
    payment_method: str  # 支払い方法
    product: str  # 商品名
    quantity: int  # 売上数量（1以上の整数）


def count_sales(sales: list[Sale]) -> dict[tuple[str, str, str], int]:
    """
    各 (店舗名, 支払い方法, 商品名) ごとの売上数量を集計する関数.

    Args:
        sales (list[Sale]): 売上データのリスト

    Returns:
        dict[tuple[str, str, str], int]: (店舗名, 支払い方法, 商品名)
            をキーとし、売上数量を値とする辞書
    """
    results: dict[tuple[str, str, str], int] = {}
    for sale in sales:
        key = (sale.store, sale.payment_method, sale.product)
        # すでにキーが存在しない場合は 0 を初期値として加算
        results[key] = results.get(key, 0) + sale.quantity
    return results
