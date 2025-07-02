def deduplicate_products(products: list[tuple[str, int]]) -> list[tuple[str, int]]:
    """
    Remove duplicate products, keeping the highest price for each product name.
    Sort the result by price in descending order.

    Args:
        products: List of (product_name, price) tuples

    Returns:
        Deduplicated list sorted by price (descending)
    """
    if not products:
        return []

    # Use dict to keep track of the highest price for each product
    product_max_price: dict[str, int] = {}
    for product_name, price in products:
        if product_name not in product_max_price or price > product_max_price[product_name]:
            product_max_price[product_name] = price

    # Convert back to list of tuples and sort by price (descending)
    result = list(product_max_price.items())
    result.sort(key=lambda x: x[1], reverse=True)

    return result
