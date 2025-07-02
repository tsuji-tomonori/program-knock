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
