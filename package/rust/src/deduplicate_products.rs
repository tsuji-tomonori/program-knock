use std::collections::HashMap;

pub fn deduplicate_products(products: &[(String, i32)]) -> Vec<(String, i32)> {
    let mut product_map = HashMap::new();

    for (name, price) in products {
        product_map.entry(name.clone())
            .and_modify(|current_price| {
                if *price > *current_price {
                    *current_price = *price;
                }
            })
            .or_insert(*price);
    }

    let mut result: Vec<(String, i32)> = product_map.into_iter().collect();
    result.sort_by(|a, b| b.1.cmp(&a.1));
    result
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_deduplicate_products_basic() {
        let products = vec![
            ("Apple".to_string(), 100),
            ("Banana".to_string(), 50),
            ("Apple".to_string(), 120),
        ];
        let result = deduplicate_products(&products);
        let expected = vec![
            ("Apple".to_string(), 120),
            ("Banana".to_string(), 50),
        ];
        assert_eq!(result, expected);
    }

    #[test]
    fn test_deduplicate_products_same_price() {
        let products = vec![
            ("Apple".to_string(), 100),
            ("Banana".to_string(), 100),
            ("Cherry".to_string(), 100),
        ];
        let result = deduplicate_products(&products);
        assert_eq!(result.len(), 3);
        assert!(result.iter().all(|(_, price)| *price == 100));
    }

    #[test]
    fn test_deduplicate_products_empty_list() {
        let products = vec![];
        let result = deduplicate_products(&products);
        assert_eq!(result, vec![]);
    }

    #[test]
    fn test_deduplicate_products_single_product() {
        let products = vec![("Apple".to_string(), 100)];
        let result = deduplicate_products(&products);
        assert_eq!(result, vec![("Apple".to_string(), 100)]);
    }

    #[test]
    fn test_deduplicate_products_all_same_product_different_prices() {
        let products = vec![
            ("Apple".to_string(), 50),
            ("Apple".to_string(), 100),
            ("Apple".to_string(), 75),
        ];
        let result = deduplicate_products(&products);
        assert_eq!(result, vec![("Apple".to_string(), 100)]);
    }

    #[test]
    fn test_deduplicate_products_boundary_prices() {
        let products = vec![
            ("Cheap".to_string(), 1),
            ("Expensive".to_string(), i32::MAX),
            ("Free".to_string(), 0),
        ];
        let result = deduplicate_products(&products);
        assert_eq!(result[0], ("Expensive".to_string(), i32::MAX));
        assert_eq!(result[1], ("Cheap".to_string(), 1));
        assert_eq!(result[2], ("Free".to_string(), 0));
    }

    #[test]
    fn test_deduplicate_products_long_product_names() {
        let products = vec![
            ("Very Long Product Name With Many Words".to_string(), 100),
            ("Short".to_string(), 200),
            ("Very Long Product Name With Many Words".to_string(), 150),
        ];
        let result = deduplicate_products(&products);
        assert_eq!(result.len(), 2);
        assert_eq!(result[0], ("Short".to_string(), 200));
        assert_eq!(result[1], ("Very Long Product Name With Many Words".to_string(), 150));
    }

    #[test]
    fn test_deduplicate_products_same_price_ordering() {
        let products = vec![
            ("Zebra".to_string(), 100),
            ("Apple".to_string(), 100),
            ("Banana".to_string(), 100),
        ];
        let result = deduplicate_products(&products);
        assert_eq!(result.len(), 3);
        assert!(result.iter().all(|(_, price)| *price == 100));
    }

    #[test]
    fn test_deduplicate_products_performance() {
        let products: Vec<(String, i32)> = (0..100000).map(|i| {
            (format!("Product{}", i % 30000), i % 1000)
        }).collect();
        let result = deduplicate_products(&products);
        assert!(result.len() <= 30000);
    }

    #[test]
    fn test_deduplicate_products_descending_price_sort() {
        let products = vec![
            ("Low".to_string(), 10),
            ("High".to_string(), 100),
            ("Medium".to_string(), 50),
        ];
        let result = deduplicate_products(&products);
        assert_eq!(result, vec![
            ("High".to_string(), 100),
            ("Medium".to_string(), 50),
            ("Low".to_string(), 10),
        ]);
    }

    #[test]
    fn test_deduplicate_products_highest_price_selection() {
        let products = vec![
            ("Product A".to_string(), 10),
            ("Product B".to_string(), 20),
            ("Product A".to_string(), 5),
            ("Product B".to_string(), 25),
            ("Product A".to_string(), 15),
        ];
        let result = deduplicate_products(&products);
        assert_eq!(result, vec![
            ("Product B".to_string(), 25),
            ("Product A".to_string(), 15),
        ]);
    }
}
