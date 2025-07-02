/**
 * Remove duplicate products, keeping the highest price for each product name.
 * Sort the result by price in descending order.
 *
 * @param products - List of [productName, price] tuples
 * @returns Deduplicated list sorted by price (descending)
 */
export function deduplicateProducts(products: Array<[string, number]>): Array<[string, number]> {
  if (!products.length) {
    return [];
  }

  // Use Map to keep track of the highest price for each product
  const productMaxPrice = new Map<string, number>();

  for (const [productName, price] of products) {
    if (!productMaxPrice.has(productName) || price > productMaxPrice.get(productName)!) {
      productMaxPrice.set(productName, price);
    }
  }

  // Convert back to list of tuples and sort by price (descending)
  const result = Array.from(productMaxPrice.entries());
  result.sort((a, b) => b[1] - a[1]);

  return result;
}
