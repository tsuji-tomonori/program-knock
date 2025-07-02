interface Sale {
  store: string; // 店舗名
  paymentMethod: string; // 支払い方法
  product: string; // 商品名
  quantity: number; // 売上数量（1以上の整数）
}

/**
 * 各 (店舗名, 支払い方法, 商品名) ごとの売上数量を集計する関数.
 *
 * @param sales - 売上データのリスト
 * @returns (店舗名, 支払い方法, 商品名) をキーとし、売上数量を値とするMap
 */
export function countSales(sales: Sale[]): Map<string, number> {
  const results = new Map<string, number>();

  for (const sale of sales) {
    const key = `${sale.store},${sale.paymentMethod},${sale.product}`;
    // すでにキーが存在しない場合は 0 を初期値として加算
    const currentValue = results.get(key) || 0;
    results.set(key, currentValue + sale.quantity);
  }

  return results;
}

export type { Sale };
