use std::collections::HashSet;

pub fn remove_duplicates(customer_ids: &[i32]) -> Vec<i32> {
    let mut seen = HashSet::new();
    let mut result = Vec::new();

    for &id in customer_ids {
        if seen.insert(id) {
            result.push(id);
        }
    }

    result
}
