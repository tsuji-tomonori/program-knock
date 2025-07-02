package src

// Sale represents a single sales record
type Sale struct {
	Store         string // Store name
	PaymentMethod string // Payment method
	Product       string // Product name
	Quantity      int    // Sales quantity (integer >= 1)
}

// SalesKey represents the composite key for sales aggregation
type SalesKey struct {
	Store         string
	PaymentMethod string
	Product       string
}

// CountSales aggregates sales quantities by (store, payment method, product)
func CountSales(sales []Sale) map[SalesKey]int {
	results := make(map[SalesKey]int)

	for _, sale := range sales {
		key := SalesKey{
			Store:         sale.Store,
			PaymentMethod: sale.PaymentMethod,
			Product:       sale.Product,
		}
		results[key] += sale.Quantity
	}

	return results
}
