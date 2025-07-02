use std::collections::HashMap;

#[derive(Debug, Clone)]
pub struct Sale {
    pub store: String,
    pub payment_method: String,
    pub product: String,
    pub quantity: i32,
}

pub fn aggregate_sales(sales: &[Sale]) -> HashMap<(String, String, String), i32> {
    let mut aggregated = HashMap::new();

    for sale in sales {
        let key = (
            sale.store.clone(),
            sale.payment_method.clone(),
            sale.product.clone(),
        );
        *aggregated.entry(key).or_insert(0) += sale.quantity;
    }

    aggregated
}
