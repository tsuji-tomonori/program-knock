from src.count_sales import Sale, count_sales


# サンプル1
def test_basic():
    sales = [
        Sale("Tokyo", "Credit", "Apple", 3),
        Sale("Tokyo", "Cash", "Apple", 2),
        Sale("Osaka", "Credit", "Apple", 5),
        Sale("Tokyo", "Credit", "Apple", 1),
        Sale("Osaka", "Credit", "Orange", 4),
        Sale("Tokyo", "Cash", "Banana", 2),
        Sale("Tokyo", "Credit", "Apple", 2),
    ]

    assert count_sales(sales) == {
        ("Tokyo", "Credit", "Apple"): 6,  # 3 + 1 + 2
        ("Tokyo", "Cash", "Apple"): 2,  # 2
        ("Osaka", "Credit", "Apple"): 5,  # 5
        ("Osaka", "Credit", "Orange"): 4,  # 4
        ("Tokyo", "Cash", "Banana"): 2,  # 2
    }


# サンプル2
def test_empty():
    sales = []
    assert count_sales(sales) == {}


# サンプル3
def test_multiple_payment_methods():
    sales = [
        Sale("Tokyo", "Credit", "Apple", 4),
        Sale("Tokyo", "Cash", "Apple", 5),
        Sale("Tokyo", "Credit", "Apple", 3),
        Sale("Tokyo", "MobilePay", "Apple", 2),
    ]

    assert count_sales(sales) == {
        ("Tokyo", "Credit", "Apple"): 7,  # 4 + 3
        ("Tokyo", "Cash", "Apple"): 5,  # 5
        ("Tokyo", "MobilePay", "Apple"): 2,  # 2
    }


# 追加テストケース1: 単一の売上データ
def test_single_sale():
    sales = [
        Sale("Nagoya", "Cash", "Grape", 5),
    ]
    assert count_sales(sales) == {("Nagoya", "Cash", "Grape"): 5}


# 追加テストケース2: 同一店舗・同一支払い方法で異なる商品
def test_same_store_different_products():
    sales = [
        Sale("Fukuoka", "Credit", "Melon", 2),
        Sale("Fukuoka", "Credit", "Banana", 3),
        Sale("Fukuoka", "Credit", "Melon", 1),
    ]
    # Melon 2 + 1 = 3
    # Banana 3
    assert count_sales(sales) == {
        ("Fukuoka", "Credit", "Melon"): 3,
        ("Fukuoka", "Credit", "Banana"): 3,
    }


# 追加テストケース3: 数量が最小の境界値(1)
def test_quantity_min():
    sales = [
        Sale("Tokyo", "Cash", "Apple", 1),
        Sale("Tokyo", "Cash", "Apple", 1),
    ]
    # 1 + 1 = 2
    assert count_sales(sales) == {("Tokyo", "Cash", "Apple"): 2}


# 追加テストケース4: 数量が非常に大きい場合
def test_large_quantity():
    large_num = 10**6
    sales = [
        Sale("Osaka", "Credit", "Laptop", large_num),
        Sale("Osaka", "Credit", "Laptop", large_num),
    ]
    # 10^6 + 10^6 = 2 * 10^6
    assert count_sales(sales) == {("Osaka", "Credit", "Laptop"): 2 * large_num}


# 追加テストケース5: 完全に同一のデータが複数ある場合
def test_duplicate_sales():
    sales = [
        Sale("Sapporo", "Credit", "IceCream", 3),
        Sale("Sapporo", "Credit", "IceCream", 3),
        Sale("Sapporo", "Credit", "IceCream", 3),
    ]
    # 3 + 3 + 3 = 9
    assert count_sales(sales) == {("Sapporo", "Credit", "IceCream"): 9}


# 追加テストケース6: 大文字・小文字が違う店舗名/商品名
def test_case_sensitivity():
    sales = [
        Sale("Tokyo", "Cash", "apple", 2),
        Sale("tokyo", "Cash", "apple", 4),
        Sale("Tokyo", "Cash", "Apple", 1),
    ]
    # (Tokyo, Cash, apple) = 2
    # (tokyo, Cash, apple) = 4
    # (Tokyo, Cash, Apple) = 1
    # それぞれ別のキーとして扱われる
    result = count_sales(sales)
    assert result == {
        ("Tokyo", "Cash", "apple"): 2,
        ("tokyo", "Cash", "apple"): 4,
        ("Tokyo", "Cash", "Apple"): 1,
    }


# 追加テストケース7: 特殊文字や空文字列が含まれる場合
def test_special_characters():
    sales = [
        Sale("", "PayPal", "???", 2),
        Sale("", "PayPal", "???", 1),
        Sale("New-Line\nStore", "Ca$h", "Product\tTab", 3),
    ]
    # ("", "PayPal", "???") = 2 + 1 = 3
    # ("New-Line\nStore", "Ca$h", "Product\tTab") = 3
    assert count_sales(sales) == {
        ("", "PayPal", "???"): 3,
        ("New-Line\nStore", "Ca$h", "Product\tTab"): 3,
    }
