import { countSales, Sale } from '../src/countSales';

describe('countSales', () => {
  // サンプル1
  test('basic test', () => {
    const sales: Sale[] = [
      { store: "Tokyo", paymentMethod: "Credit", product: "Apple", quantity: 3 },
      { store: "Tokyo", paymentMethod: "Cash", product: "Apple", quantity: 2 },
      { store: "Osaka", paymentMethod: "Credit", product: "Apple", quantity: 5 },
      { store: "Tokyo", paymentMethod: "Credit", product: "Apple", quantity: 1 },
      { store: "Osaka", paymentMethod: "Credit", product: "Orange", quantity: 4 },
      { store: "Tokyo", paymentMethod: "Cash", product: "Banana", quantity: 2 },
      { store: "Tokyo", paymentMethod: "Credit", product: "Apple", quantity: 2 },
    ];

    const result = countSales(sales);
    expect(result.get("Tokyo,Credit,Apple")).toBe(6); // 3 + 1 + 2
    expect(result.get("Tokyo,Cash,Apple")).toBe(2); // 2
    expect(result.get("Osaka,Credit,Apple")).toBe(5); // 5
    expect(result.get("Osaka,Credit,Orange")).toBe(4); // 4
    expect(result.get("Tokyo,Cash,Banana")).toBe(2); // 2
    expect(result.size).toBe(5);
  });

  // サンプル2
  test('empty sales', () => {
    const sales: Sale[] = [];
    const result = countSales(sales);
    expect(result.size).toBe(0);
  });

  // サンプル3
  test('multiple payment methods', () => {
    const sales: Sale[] = [
      { store: "Tokyo", paymentMethod: "Credit", product: "Apple", quantity: 4 },
      { store: "Tokyo", paymentMethod: "Cash", product: "Apple", quantity: 5 },
      { store: "Tokyo", paymentMethod: "Credit", product: "Apple", quantity: 3 },
      { store: "Tokyo", paymentMethod: "MobilePay", product: "Apple", quantity: 2 },
    ];

    const result = countSales(sales);
    expect(result.get("Tokyo,Credit,Apple")).toBe(7); // 4 + 3
    expect(result.get("Tokyo,Cash,Apple")).toBe(5); // 5
    expect(result.get("Tokyo,MobilePay,Apple")).toBe(2); // 2
    expect(result.size).toBe(3);
  });

  // 追加テストケース1: 単一の売上データ
  test('single sale', () => {
    const sales: Sale[] = [
      { store: "Nagoya", paymentMethod: "Cash", product: "Grape", quantity: 5 },
    ];
    const result = countSales(sales);
    expect(result.get("Nagoya,Cash,Grape")).toBe(5);
    expect(result.size).toBe(1);
  });

  // 追加テストケース2: 同一店舗・同一支払い方法で異なる商品
  test('same store different products', () => {
    const sales: Sale[] = [
      { store: "Fukuoka", paymentMethod: "Credit", product: "Melon", quantity: 2 },
      { store: "Fukuoka", paymentMethod: "Credit", product: "Banana", quantity: 3 },
      { store: "Fukuoka", paymentMethod: "Credit", product: "Melon", quantity: 1 },
    ];
    // Melon 2 + 1 = 3
    // Banana 3
    const result = countSales(sales);
    expect(result.get("Fukuoka,Credit,Melon")).toBe(3);
    expect(result.get("Fukuoka,Credit,Banana")).toBe(3);
    expect(result.size).toBe(2);
  });

  // 追加テストケース3: 数量が最小の境界値(1)
  test('quantity min', () => {
    const sales: Sale[] = [
      { store: "Tokyo", paymentMethod: "Cash", product: "Apple", quantity: 1 },
      { store: "Tokyo", paymentMethod: "Cash", product: "Apple", quantity: 1 },
    ];
    // 1 + 1 = 2
    const result = countSales(sales);
    expect(result.get("Tokyo,Cash,Apple")).toBe(2);
    expect(result.size).toBe(1);
  });

  // 追加テストケース4: 数量が非常に大きい場合
  test('large quantity', () => {
    const largeNum = 10 ** 6;
    const sales: Sale[] = [
      { store: "Osaka", paymentMethod: "Credit", product: "Laptop", quantity: largeNum },
      { store: "Osaka", paymentMethod: "Credit", product: "Laptop", quantity: largeNum },
    ];
    // 10^6 + 10^6 = 2 * 10^6
    const result = countSales(sales);
    expect(result.get("Osaka,Credit,Laptop")).toBe(2 * largeNum);
    expect(result.size).toBe(1);
  });

  // 追加テストケース5: 完全に同一のデータが複数ある場合
  test('duplicate sales', () => {
    const sales: Sale[] = [
      { store: "Sapporo", paymentMethod: "Credit", product: "IceCream", quantity: 3 },
      { store: "Sapporo", paymentMethod: "Credit", product: "IceCream", quantity: 3 },
      { store: "Sapporo", paymentMethod: "Credit", product: "IceCream", quantity: 3 },
    ];
    // 3 + 3 + 3 = 9
    const result = countSales(sales);
    expect(result.get("Sapporo,Credit,IceCream")).toBe(9);
    expect(result.size).toBe(1);
  });

  // 追加テストケース6: 大文字・小文字が違う店舗名/商品名
  test('case sensitivity', () => {
    const sales: Sale[] = [
      { store: "Tokyo", paymentMethod: "Cash", product: "apple", quantity: 2 },
      { store: "tokyo", paymentMethod: "Cash", product: "apple", quantity: 4 },
      { store: "Tokyo", paymentMethod: "Cash", product: "Apple", quantity: 1 },
    ];
    // (Tokyo, Cash, apple) = 2
    // (tokyo, Cash, apple) = 4
    // (Tokyo, Cash, Apple) = 1
    // それぞれ別のキーとして扱われる
    const result = countSales(sales);
    expect(result.get("Tokyo,Cash,apple")).toBe(2);
    expect(result.get("tokyo,Cash,apple")).toBe(4);
    expect(result.get("Tokyo,Cash,Apple")).toBe(1);
    expect(result.size).toBe(3);
  });

  // 追加テストケース7: 特殊文字や空文字列が含まれる場合
  test('special characters', () => {
    const sales: Sale[] = [
      { store: "", paymentMethod: "PayPal", product: "???", quantity: 2 },
      { store: "", paymentMethod: "PayPal", product: "???", quantity: 1 },
      { store: "New-Line\nStore", paymentMethod: "Ca$h", product: "Product\tTab", quantity: 3 },
    ];
    // ("", "PayPal", "???") = 2 + 1 = 3
    // ("New-Line\nStore", "Ca$h", "Product\tTab") = 3
    const result = countSales(sales);
    expect(result.get(",PayPal,???")).toBe(3);
    expect(result.get("New-Line\nStore,Ca$h,Product\tTab")).toBe(3);
    expect(result.size).toBe(2);
  });
});
