use program_knock::restaurant_seating::*;

#[test]
fn test_restaurant_seating_basic() {
    let commands = vec![
        "arrive Alice".to_string(),
        "arrive Bob".to_string(),
        "seat 1".to_string(),
        "arrive Charlie".to_string(),
        "seat 2".to_string(),
    ];
    let result = restaurant_seating(&commands);
    assert_eq!(result, vec!["Alice", "Bob", "Charlie"]);
}

#[test]
fn test_restaurant_seating_multiple_seatings() {
    let commands = vec![
        "arrive Alice".to_string(),
        "arrive Bob".to_string(),
        "arrive Charlie".to_string(),
        "seat 2".to_string(),
        "seat 1".to_string(),
    ];
    let result = restaurant_seating(&commands);
    assert_eq!(result, vec!["Alice", "Bob", "Charlie"]);
}

#[test]
fn test_restaurant_seating_excess_capacity() {
    let commands = vec![
        "arrive Alice".to_string(),
        "seat 5".to_string(),
    ];
    let result = restaurant_seating(&commands);
    assert_eq!(result, vec!["Alice"]);
}

#[test]
fn test_restaurant_seating_empty_queue() {
    let commands = vec!["seat 3".to_string()];
    let result = restaurant_seating(&commands);
    assert_eq!(result, vec![] as Vec<String>);
}

#[test]
fn test_restaurant_seating_single_person() {
    let commands = vec![
        "arrive Alice".to_string(),
        "seat 1".to_string(),
    ];
    let result = restaurant_seating(&commands);
    assert_eq!(result, vec!["Alice"]);
}

#[test]
fn test_restaurant_seating_consecutive_seatings() {
    let commands = vec![
        "arrive Alice".to_string(),
        "arrive Bob".to_string(),
        "arrive Charlie".to_string(),
        "seat 1".to_string(),
        "seat 1".to_string(),
        "seat 1".to_string(),
    ];
    let result = restaurant_seating(&commands);
    assert_eq!(result, vec!["Alice", "Bob", "Charlie"]);
}

#[test]
fn test_restaurant_seating_maximum_capacity() {
    let mut commands = Vec::new();
    for i in 1..=100 {
        commands.push(format!("arrive Person{}", i));
    }
    commands.push("seat 100".to_string());

    let result = restaurant_seating(&commands);
    assert_eq!(result.len(), 100);
    assert_eq!(result[0], "Person1");
    assert_eq!(result[99], "Person100");
}

#[test]
fn test_restaurant_seating_large_queue() {
    let mut commands = Vec::new();
    for i in 1..=1000 {
        commands.push(format!("arrive Customer{}", i));
    }
    commands.push("seat 500".to_string());

    let result = restaurant_seating(&commands);
    assert_eq!(result.len(), 500);
}

#[test]
fn test_restaurant_seating_complex_names() {
    let commands = vec![
        "arrive Alice-Smith".to_string(),
        "arrive Bob O'Connor".to_string(),
        "arrive Charlie_Brown".to_string(),
        "seat 3".to_string(),
    ];
    let result = restaurant_seating(&commands);
    assert_eq!(result, vec!["Alice-Smith", "Bob O'Connor", "Charlie_Brown"]);
}

#[test]
fn test_restaurant_seating_fifo_order() {
    let commands = vec![
        "arrive First".to_string(),
        "arrive Second".to_string(),
        "arrive Third".to_string(),
        "arrive Fourth".to_string(),
        "seat 2".to_string(),
        "arrive Fifth".to_string(),
        "seat 3".to_string(),
    ];
    let result = restaurant_seating(&commands);
    assert_eq!(result, vec!["First", "Second", "Third", "Fourth", "Fifth"]);
}
