package tests

import (
	"fmt"
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestDeduplicateProducts(t *testing.T) {
	t.Run("Sample1", func(t *testing.T) {
		products := []src.Product{
			{Name: "apple", Price: 300},
			{Name: "banana", Price: 200},
			{Name: "apple", Price: 250},
			{Name: "orange", Price: 400},
		}
		expected := []src.Product{
			{Name: "orange", Price: 400},
			{Name: "apple", Price: 300},
			{Name: "banana", Price: 200},
		}
		result := src.DeduplicateProducts(products)
		assert.Equal(t, expected, result)
	})

	t.Run("Sample2", func(t *testing.T) {
		products := []src.Product{
			{Name: "watch", Price: 5000},
			{Name: "watch", Price: 5000},
			{Name: "ring", Price: 7000},
			{Name: "ring", Price: 6500},
		}
		expected := []src.Product{
			{Name: "ring", Price: 7000},
			{Name: "watch", Price: 5000},
		}
		result := src.DeduplicateProducts(products)
		assert.Equal(t, expected, result)
	})

	t.Run("Sample3", func(t *testing.T) {
		products := []src.Product{
			{Name: "pen", Price: 100},
			{Name: "notebook", Price: 200},
			{Name: "eraser", Price: 50},
			{Name: "pen", Price: 150},
		}
		expected := []src.Product{
			{Name: "notebook", Price: 200},
			{Name: "pen", Price: 150},
			{Name: "eraser", Price: 50},
		}
		result := src.DeduplicateProducts(products)
		assert.Equal(t, expected, result)
	})

	t.Run("Sample4", func(t *testing.T) {
		products := []src.Product{}
		expected := []src.Product{}
		result := src.DeduplicateProducts(products)
		assert.Equal(t, expected, result)
	})

	t.Run("Sample5", func(t *testing.T) {
		products := []src.Product{
			{Name: "bag", Price: 1200},
			{Name: "shoes", Price: 3000},
			{Name: "bag", Price: 1000},
			{Name: "hat", Price: 2500},
		}
		expected := []src.Product{
			{Name: "shoes", Price: 3000},
			{Name: "hat", Price: 2500},
			{Name: "bag", Price: 1200},
		}
		result := src.DeduplicateProducts(products)
		assert.Equal(t, expected, result)
	})

	t.Run("NoDuplicates", func(t *testing.T) {
		products := []src.Product{
			{Name: "apple", Price: 300},
			{Name: "banana", Price: 200},
			{Name: "orange", Price: 400},
		}
		expected := []src.Product{
			{Name: "orange", Price: 400},
			{Name: "apple", Price: 300},
			{Name: "banana", Price: 200},
		}
		result := src.DeduplicateProducts(products)
		assert.Equal(t, expected, result)
	})

	t.Run("AllSameProduct", func(t *testing.T) {
		products := []src.Product{
			{Name: "apple", Price: 100},
			{Name: "apple", Price: 300},
			{Name: "apple", Price: 200},
			{Name: "apple", Price: 250},
		}
		expected := []src.Product{
			{Name: "apple", Price: 300},
		}
		result := src.DeduplicateProducts(products)
		assert.Equal(t, expected, result)
	})

	t.Run("SamePriceDifferentProducts", func(t *testing.T) {
		products := []src.Product{
			{Name: "apple", Price: 100},
			{Name: "banana", Price: 100},
			{Name: "orange", Price: 100},
		}
		result := src.DeduplicateProducts(products)
		// All products should be present with same price
		assert.Len(t, result, 3)
		for _, product := range result {
			assert.Equal(t, 100, product.Price)
		}
		productNames := make(map[string]bool)
		for _, product := range result {
			productNames[product.Name] = true
		}
		expectedNames := map[string]bool{"apple": true, "banana": true, "orange": true}
		assert.Equal(t, expectedNames, productNames)
	})

	t.Run("SingleProduct", func(t *testing.T) {
		products := []src.Product{
			{Name: "apple", Price: 300},
		}
		expected := []src.Product{
			{Name: "apple", Price: 300},
		}
		result := src.DeduplicateProducts(products)
		assert.Equal(t, expected, result)
	})

	t.Run("MultipleSamePriceDuplicates", func(t *testing.T) {
		products := []src.Product{
			{Name: "apple", Price: 100},
			{Name: "apple", Price: 100},
			{Name: "banana", Price: 200},
		}
		expected := []src.Product{
			{Name: "banana", Price: 200},
			{Name: "apple", Price: 100},
		}
		result := src.DeduplicateProducts(products)
		assert.Equal(t, expected, result)
	})

	t.Run("LargeDatasetPerformance", func(t *testing.T) {
		// Test with large dataset
		var products []src.Product
		for i := 0; i < 1000; i++ {
			productName := fmt.Sprintf("product_%d", i%100) // 100 unique products
			price := (i%10)*100 + 100                       // Prices from 100 to 1000
			products = append(products, src.Product{Name: productName, Price: price})
		}

		result := src.DeduplicateProducts(products)

		// Should have 100 unique products
		assert.Len(t, result, 100)

		// Should be sorted by price descending
		for i := 1; i < len(result); i++ {
			assert.GreaterOrEqual(t, result[i-1].Price, result[i].Price)
		}
	})

	t.Run("EdgeCaseHighPrices", func(t *testing.T) {
		products := []src.Product{
			{Name: "luxury_item", Price: 1000000},
			{Name: "budget_item", Price: 1},
			{Name: "luxury_item", Price: 999999},
		}
		expected := []src.Product{
			{Name: "luxury_item", Price: 1000000},
			{Name: "budget_item", Price: 1},
		}
		result := src.DeduplicateProducts(products)
		assert.Equal(t, expected, result)
	})

	t.Run("ZeroPrices", func(t *testing.T) {
		products := []src.Product{
			{Name: "free_item", Price: 0},
			{Name: "paid_item", Price: 100},
			{Name: "free_item", Price: 0},
		}
		expected := []src.Product{
			{Name: "paid_item", Price: 100},
			{Name: "free_item", Price: 0},
		}
		result := src.DeduplicateProducts(products)
		assert.Equal(t, expected, result)
	})

	t.Run("NegativePrices", func(t *testing.T) {
		products := []src.Product{
			{Name: "discount_item", Price: -100},
			{Name: "regular_item", Price: 100},
			{Name: "discount_item", Price: -50},
		}
		expected := []src.Product{
			{Name: "regular_item", Price: 100},
			{Name: "discount_item", Price: -50},
		}
		result := src.DeduplicateProducts(products)
		assert.Equal(t, expected, result)
	})
}
