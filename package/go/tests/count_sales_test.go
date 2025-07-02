package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestCountSales(t *testing.T) {
	t.Run("Basic", func(t *testing.T) {
		sales := []src.Sale{
			{Store: "Tokyo", PaymentMethod: "Credit", Product: "Apple", Quantity: 3},
			{Store: "Tokyo", PaymentMethod: "Cash", Product: "Apple", Quantity: 2},
			{Store: "Osaka", PaymentMethod: "Credit", Product: "Apple", Quantity: 5},
			{Store: "Tokyo", PaymentMethod: "Credit", Product: "Apple", Quantity: 1},
			{Store: "Osaka", PaymentMethod: "Credit", Product: "Orange", Quantity: 4},
			{Store: "Tokyo", PaymentMethod: "Cash", Product: "Banana", Quantity: 2},
			{Store: "Tokyo", PaymentMethod: "Credit", Product: "Apple", Quantity: 2},
		}

		result := src.CountSales(sales)
		expected := map[src.SalesKey]int{
			{Store: "Tokyo", PaymentMethod: "Credit", Product: "Apple"}:  6, // 3 + 1 + 2
			{Store: "Tokyo", PaymentMethod: "Cash", Product: "Apple"}:    2, // 2
			{Store: "Osaka", PaymentMethod: "Credit", Product: "Apple"}:  5, // 5
			{Store: "Osaka", PaymentMethod: "Credit", Product: "Orange"}: 4, // 4
			{Store: "Tokyo", PaymentMethod: "Cash", Product: "Banana"}:   2, // 2
		}
		assert.Equal(t, expected, result)
	})

	t.Run("Empty", func(t *testing.T) {
		sales := []src.Sale{}
		result := src.CountSales(sales)
		expected := map[src.SalesKey]int{}
		assert.Equal(t, expected, result)
	})

	t.Run("MultiplePaymentMethods", func(t *testing.T) {
		sales := []src.Sale{
			{Store: "Tokyo", PaymentMethod: "Credit", Product: "Apple", Quantity: 4},
			{Store: "Tokyo", PaymentMethod: "Cash", Product: "Apple", Quantity: 5},
			{Store: "Tokyo", PaymentMethod: "Credit", Product: "Apple", Quantity: 3},
			{Store: "Tokyo", PaymentMethod: "MobilePay", Product: "Apple", Quantity: 2},
		}

		result := src.CountSales(sales)
		expected := map[src.SalesKey]int{
			{Store: "Tokyo", PaymentMethod: "Credit", Product: "Apple"}:    7, // 4 + 3
			{Store: "Tokyo", PaymentMethod: "Cash", Product: "Apple"}:      5, // 5
			{Store: "Tokyo", PaymentMethod: "MobilePay", Product: "Apple"}: 2, // 2
		}
		assert.Equal(t, expected, result)
	})

	t.Run("SingleSale", func(t *testing.T) {
		sales := []src.Sale{
			{Store: "Nagoya", PaymentMethod: "Cash", Product: "Grape", Quantity: 5},
		}
		result := src.CountSales(sales)
		expected := map[src.SalesKey]int{
			{Store: "Nagoya", PaymentMethod: "Cash", Product: "Grape"}: 5,
		}
		assert.Equal(t, expected, result)
	})

	t.Run("SameStoreDifferentProducts", func(t *testing.T) {
		sales := []src.Sale{
			{Store: "Fukuoka", PaymentMethod: "Credit", Product: "Melon", Quantity: 2},
			{Store: "Fukuoka", PaymentMethod: "Credit", Product: "Banana", Quantity: 3},
			{Store: "Fukuoka", PaymentMethod: "Credit", Product: "Melon", Quantity: 1},
		}
		result := src.CountSales(sales)
		expected := map[src.SalesKey]int{
			{Store: "Fukuoka", PaymentMethod: "Credit", Product: "Melon"}:  3, // 2 + 1
			{Store: "Fukuoka", PaymentMethod: "Credit", Product: "Banana"}: 3, // 3
		}
		assert.Equal(t, expected, result)
	})

	t.Run("QuantityMin", func(t *testing.T) {
		sales := []src.Sale{
			{Store: "Tokyo", PaymentMethod: "Cash", Product: "Apple", Quantity: 1},
			{Store: "Tokyo", PaymentMethod: "Cash", Product: "Apple", Quantity: 1},
		}
		result := src.CountSales(sales)
		expected := map[src.SalesKey]int{
			{Store: "Tokyo", PaymentMethod: "Cash", Product: "Apple"}: 2, // 1 + 1
		}
		assert.Equal(t, expected, result)
	})

	t.Run("LargeQuantity", func(t *testing.T) {
		largeNum := 1000000
		sales := []src.Sale{
			{Store: "Osaka", PaymentMethod: "Credit", Product: "Laptop", Quantity: largeNum},
			{Store: "Osaka", PaymentMethod: "Credit", Product: "Laptop", Quantity: largeNum},
		}
		result := src.CountSales(sales)
		expected := map[src.SalesKey]int{
			{Store: "Osaka", PaymentMethod: "Credit", Product: "Laptop"}: 2 * largeNum,
		}
		assert.Equal(t, expected, result)
	})

	t.Run("DuplicateSales", func(t *testing.T) {
		sales := []src.Sale{
			{Store: "Sapporo", PaymentMethod: "Credit", Product: "IceCream", Quantity: 3},
			{Store: "Sapporo", PaymentMethod: "Credit", Product: "IceCream", Quantity: 3},
			{Store: "Sapporo", PaymentMethod: "Credit", Product: "IceCream", Quantity: 3},
		}
		result := src.CountSales(sales)
		expected := map[src.SalesKey]int{
			{Store: "Sapporo", PaymentMethod: "Credit", Product: "IceCream"}: 9, // 3 + 3 + 3
		}
		assert.Equal(t, expected, result)
	})

	t.Run("CaseSensitivity", func(t *testing.T) {
		sales := []src.Sale{
			{Store: "Tokyo", PaymentMethod: "Cash", Product: "apple", Quantity: 2},
			{Store: "tokyo", PaymentMethod: "Cash", Product: "apple", Quantity: 4},
			{Store: "Tokyo", PaymentMethod: "Cash", Product: "Apple", Quantity: 1},
		}
		result := src.CountSales(sales)
		expected := map[src.SalesKey]int{
			{Store: "Tokyo", PaymentMethod: "Cash", Product: "apple"}: 2,
			{Store: "tokyo", PaymentMethod: "Cash", Product: "apple"}: 4,
			{Store: "Tokyo", PaymentMethod: "Cash", Product: "Apple"}: 1,
		}
		assert.Equal(t, expected, result)
	})

	t.Run("SpecialCharacters", func(t *testing.T) {
		sales := []src.Sale{
			{Store: "", PaymentMethod: "PayPal", Product: "???", Quantity: 2},
			{Store: "", PaymentMethod: "PayPal", Product: "???", Quantity: 1},
			{Store: "New-Line\nStore", PaymentMethod: "Ca$h", Product: "Product\tTab", Quantity: 3},
		}
		result := src.CountSales(sales)
		expected := map[src.SalesKey]int{
			{Store: "", PaymentMethod: "PayPal", Product: "???"}:                           3, // 2 + 1
			{Store: "New-Line\nStore", PaymentMethod: "Ca$h", Product: "Product\tTab"}: 3, // 3
		}
		assert.Equal(t, expected, result)
	})

	t.Run("ManyDifferentStores", func(t *testing.T) {
		sales := []src.Sale{
			{Store: "Store1", PaymentMethod: "Credit", Product: "Product1", Quantity: 1},
			{Store: "Store2", PaymentMethod: "Credit", Product: "Product1", Quantity: 2},
			{Store: "Store3", PaymentMethod: "Credit", Product: "Product1", Quantity: 3},
			{Store: "Store4", PaymentMethod: "Credit", Product: "Product1", Quantity: 4},
			{Store: "Store5", PaymentMethod: "Credit", Product: "Product1", Quantity: 5},
		}
		result := src.CountSales(sales)
		expected := map[src.SalesKey]int{
			{Store: "Store1", PaymentMethod: "Credit", Product: "Product1"}: 1,
			{Store: "Store2", PaymentMethod: "Credit", Product: "Product1"}: 2,
			{Store: "Store3", PaymentMethod: "Credit", Product: "Product1"}: 3,
			{Store: "Store4", PaymentMethod: "Credit", Product: "Product1"}: 4,
			{Store: "Store5", PaymentMethod: "Credit", Product: "Product1"}: 5,
		}
		assert.Equal(t, expected, result)
	})
}
