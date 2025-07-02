use program_knock::aggregate_sales::*;

#[test]
fn test_aggregate_sales_basic() {
    let sales = vec![
        Sale {
            store: "Store A".to_string(),
            payment_method: "Cash".to_string(),
            product: "Product X".to_string(),
            quantity: 5,
        },
        Sale {
            store: "Store A".to_string(),
            payment_method: "Cash".to_string(),
            product: "Product X".to_string(),
            quantity: 3,
        },
    ];
    let result = aggregate_sales(&sales);
    let key = ("Store A".to_string(), "Cash".to_string(), "Product X".to_string());
    assert_eq!(result.get(&key), Some(&8));
}

#[test]
fn test_aggregate_sales_empty() {
    let sales = vec![];
    let result = aggregate_sales(&sales);
    assert!(result.is_empty());
}

#[test]
fn test_aggregate_sales_payment_method_distinction() {
    let sales = vec![
        Sale {
            store: "Store A".to_string(),
            payment_method: "Cash".to_string(),
            product: "Product X".to_string(),
            quantity: 5,
        },
        Sale {
            store: "Store A".to_string(),
            payment_method: "Credit".to_string(),
            product: "Product X".to_string(),
            quantity: 3,
        },
    ];
    let result = aggregate_sales(&sales);
    let cash_key = ("Store A".to_string(), "Cash".to_string(), "Product X".to_string());
    let credit_key = ("Store A".to_string(), "Credit".to_string(), "Product X".to_string());
    assert_eq!(result.get(&cash_key), Some(&5));
    assert_eq!(result.get(&credit_key), Some(&3));
}

#[test]
fn test_aggregate_sales_single_sale() {
    let sales = vec![
        Sale {
            store: "Store A".to_string(),
            payment_method: "Cash".to_string(),
            product: "Product X".to_string(),
            quantity: 10,
        },
    ];
    let result = aggregate_sales(&sales);
    assert_eq!(result.len(), 1);
    let key = ("Store A".to_string(), "Cash".to_string(), "Product X".to_string());
    assert_eq!(result.get(&key), Some(&10));
}

#[test]
fn test_aggregate_sales_identical_keys() {
    let sales = vec![
        Sale {
            store: "Store A".to_string(),
            payment_method: "Cash".to_string(),
            product: "Product X".to_string(),
            quantity: 1,
        },
        Sale {
            store: "Store A".to_string(),
            payment_method: "Cash".to_string(),
            product: "Product X".to_string(),
            quantity: 2,
        },
        Sale {
            store: "Store A".to_string(),
            payment_method: "Cash".to_string(),
            product: "Product X".to_string(),
            quantity: 3,
        },
    ];
    let result = aggregate_sales(&sales);
    let key = ("Store A".to_string(), "Cash".to_string(), "Product X".to_string());
    assert_eq!(result.get(&key), Some(&6));
}

#[test]
fn test_aggregate_sales_different_stores() {
    let sales = vec![
        Sale {
            store: "Store A".to_string(),
            payment_method: "Cash".to_string(),
            product: "Product X".to_string(),
            quantity: 5,
        },
        Sale {
            store: "Store B".to_string(),
            payment_method: "Cash".to_string(),
            product: "Product X".to_string(),
            quantity: 3,
        },
    ];
    let result = aggregate_sales(&sales);
    assert_eq!(result.len(), 2);
    let key_a = ("Store A".to_string(), "Cash".to_string(), "Product X".to_string());
    let key_b = ("Store B".to_string(), "Cash".to_string(), "Product X".to_string());
    assert_eq!(result.get(&key_a), Some(&5));
    assert_eq!(result.get(&key_b), Some(&3));
}

#[test]
fn test_aggregate_sales_large_quantities() {
    let sales = vec![
        Sale {
            store: "Store A".to_string(),
            payment_method: "Cash".to_string(),
            product: "Product X".to_string(),
            quantity: 1000000,
        },
        Sale {
            store: "Store A".to_string(),
            payment_method: "Cash".to_string(),
            product: "Product X".to_string(),
            quantity: 2000000,
        },
    ];
    let result = aggregate_sales(&sales);
    let key = ("Store A".to_string(), "Cash".to_string(), "Product X".to_string());
    assert_eq!(result.get(&key), Some(&3000000));
}

#[test]
fn test_aggregate_sales_special_characters() {
    let sales = vec![
        Sale {
            store: "Store-A&B".to_string(),
            payment_method: "Cash@Card".to_string(),
            product: "Product#1".to_string(),
            quantity: 5,
        },
    ];
    let result = aggregate_sales(&sales);
    let key = ("Store-A&B".to_string(), "Cash@Card".to_string(), "Product#1".to_string());
    assert_eq!(result.get(&key), Some(&5));
}

#[test]
fn test_aggregate_sales_complex_combinations() {
    let sales = vec![
        Sale { store: "A".to_string(), payment_method: "Cash".to_string(), product: "X".to_string(), quantity: 1 },
        Sale { store: "A".to_string(), payment_method: "Card".to_string(), product: "X".to_string(), quantity: 2 },
        Sale { store: "A".to_string(), payment_method: "Cash".to_string(), product: "Y".to_string(), quantity: 3 },
        Sale { store: "B".to_string(), payment_method: "Cash".to_string(), product: "X".to_string(), quantity: 4 },
    ];
    let result = aggregate_sales(&sales);
    assert_eq!(result.len(), 4);
}

#[test]
fn test_aggregate_sales_performance() {
    let sales: Vec<Sale> = (0..10000).map(|i| Sale {
        store: format!("Store{}", i % 10),
        payment_method: format!("Payment{}", i % 5),
        product: format!("Product{}", i % 20),
        quantity: 1,
    }).collect();
    let result = aggregate_sales(&sales);
    assert!(result.len() <= 1000);
}
