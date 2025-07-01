from src.product_deduplication import deduplicate_products


class TestProductDeduplication:
    def test_sample_1(self):
        products = [("apple", 300), ("banana", 200), ("apple", 250), ("orange", 400)]
        expected = [("orange", 400), ("apple", 300), ("banana", 200)]
        assert deduplicate_products(products) == expected

    def test_sample_2(self):
        products = [("watch", 5000), ("watch", 5000), ("ring", 7000), ("ring", 6500)]
        expected = [("ring", 7000), ("watch", 5000)]
        assert deduplicate_products(products) == expected

    def test_sample_3(self):
        products = [("pen", 100), ("notebook", 200), ("eraser", 50), ("pen", 150)]
        expected = [("notebook", 200), ("pen", 150), ("eraser", 50)]
        assert deduplicate_products(products) == expected

    def test_sample_4(self):
        products = []
        expected = []
        assert deduplicate_products(products) == expected

    def test_sample_5(self):
        products = [("bag", 1200), ("shoes", 3000), ("bag", 1000), ("hat", 2500)]
        expected = [("shoes", 3000), ("hat", 2500), ("bag", 1200)]
        assert deduplicate_products(products) == expected

    def test_no_duplicates(self):
        products = [("apple", 300), ("banana", 200), ("orange", 400)]
        expected = [("orange", 400), ("apple", 300), ("banana", 200)]
        assert deduplicate_products(products) == expected

    def test_all_same_product(self):
        products = [("apple", 100), ("apple", 300), ("apple", 200), ("apple", 250)]
        expected = [("apple", 300)]
        assert deduplicate_products(products) == expected

    def test_same_price_different_products(self):
        products = [("apple", 100), ("banana", 100), ("orange", 100)]
        result = deduplicate_products(products)
        # All products should be present with same price
        assert len(result) == 3
        assert all(price == 100 for _, price in result)
        product_names = {name for name, _ in result}
        assert product_names == {"apple", "banana", "orange"}

    def test_single_product(self):
        products = [("apple", 300)]
        expected = [("apple", 300)]
        assert deduplicate_products(products) == expected

    def test_multiple_same_price_duplicates(self):
        products = [("apple", 100), ("apple", 100), ("banana", 200)]
        expected = [("banana", 200), ("apple", 100)]
        assert deduplicate_products(products) == expected

    def test_large_dataset_performance(self):
        # Test with large dataset
        products = []
        for i in range(1000):
            product_name = f"product_{i % 100}"  # 100 unique products
            price = (i % 10) * 100 + 100  # Prices from 100 to 1000
            products.append((product_name, price))

        result = deduplicate_products(products)

        # Should have 100 unique products
        assert len(result) == 100

        # Should be sorted by price descending
        prices = [price for _, price in result]
        assert prices == sorted(prices, reverse=True)

    def test_edge_case_high_prices(self):
        products = [
            ("luxury_item", 1000000),
            ("budget_item", 1),
            ("luxury_item", 999999),
        ]
        expected = [("luxury_item", 1000000), ("budget_item", 1)]
        assert deduplicate_products(products) == expected
