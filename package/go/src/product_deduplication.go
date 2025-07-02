package src

import (
	"sort"
)

// Product represents a product with name and price
type Product struct {
	Name  string
	Price int
}

// DeduplicateProducts removes duplicate products, keeping the highest price for each product name.
// Sorts the result by price in descending order.
func DeduplicateProducts(products []Product) []Product {
	if len(products) == 0 {
		return []Product{}
	}

	// Use map to keep track of the highest price for each product
	productMaxPrice := make(map[string]int)
	for _, product := range products {
		if currentPrice, exists := productMaxPrice[product.Name]; !exists || product.Price > currentPrice {
			productMaxPrice[product.Name] = product.Price
		}
	}

	// Convert back to slice of products
	var result []Product
	for name, price := range productMaxPrice {
		result = append(result, Product{Name: name, Price: price})
	}

	// Sort by price (descending)
	sort.Slice(result, func(i, j int) bool {
		return result[i].Price > result[j].Price
	})

	return result
}
