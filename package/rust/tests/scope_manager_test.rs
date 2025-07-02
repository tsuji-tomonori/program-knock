use program_knock::scope_manager::*;

#[test]
fn test_scope_manager_basic_operations() {
    let mut manager = ScopeManager::new();
    manager.set_variable("x".to_string(), 10);
    assert_eq!(manager.get_variable("x"), Some(10));

    manager.enter_scope();
    manager.set_variable("y".to_string(), 20);
    assert_eq!(manager.get_variable("x"), Some(10));
    assert_eq!(manager.get_variable("y"), Some(20));

    manager.exit_scope();
    assert_eq!(manager.get_variable("x"), Some(10));
    assert_eq!(manager.get_variable("y"), None);
}

#[test]
fn test_scope_manager_nested_scopes() {
    let mut manager = ScopeManager::new();
    manager.set_variable("a".to_string(), 1);

    manager.enter_scope();
    manager.set_variable("b".to_string(), 2);

    manager.enter_scope();
    manager.set_variable("c".to_string(), 3);

    assert_eq!(manager.get_variable("a"), Some(1));
    assert_eq!(manager.get_variable("b"), Some(2));
    assert_eq!(manager.get_variable("c"), Some(3));

    manager.exit_scope();
    assert_eq!(manager.get_variable("a"), Some(1));
    assert_eq!(manager.get_variable("b"), Some(2));
    assert_eq!(manager.get_variable("c"), None);

    manager.exit_scope();
    assert_eq!(manager.get_variable("a"), Some(1));
    assert_eq!(manager.get_variable("b"), None);
}

#[test]
fn test_scope_manager_undefined_variable() {
    let manager = ScopeManager::new();
    assert_eq!(manager.get_variable("undefined"), None);
}

#[test]
fn test_scope_manager_variable_overwriting() {
    let mut manager = ScopeManager::new();
    manager.set_variable("x".to_string(), 10);
    assert_eq!(manager.get_variable("x"), Some(10));

    manager.enter_scope();
    manager.set_variable("x".to_string(), 20);
    assert_eq!(manager.get_variable("x"), Some(20));

    manager.exit_scope();
    assert_eq!(manager.get_variable("x"), Some(10));
}

#[test]
fn test_scope_manager_global_scope_protection() {
    let mut manager = ScopeManager::new();
    manager.set_variable("global".to_string(), 42);

    manager.exit_scope();
    assert_eq!(manager.get_variable("global"), Some(42));

    manager.exit_scope();
    assert_eq!(manager.get_variable("global"), Some(42));
}

#[test]
fn test_scope_manager_empty_manager() {
    let manager = ScopeManager::new();
    assert_eq!(manager.get_variable("any"), None);
}

#[test]
fn test_scope_manager_deep_nesting() {
    let mut manager = ScopeManager::new();

    for i in 0..10 {
        manager.enter_scope();
        manager.set_variable(format!("var{}", i), i);
    }

    for i in 0..10 {
        assert_eq!(manager.get_variable(&format!("var{}", i)), Some(i));
    }

    for _ in 0..10 {
        manager.exit_scope();
    }

    for i in 0..10 {
        assert_eq!(manager.get_variable(&format!("var{}", i)), None);
    }
}

#[test]
fn test_scope_manager_large_variable_count() {
    let mut manager = ScopeManager::new();

    for i in 0..10000 {
        manager.set_variable(format!("var{}", i), i);
    }

    for i in 0..10000 {
        assert_eq!(manager.get_variable(&format!("var{}", i)), Some(i));
    }
}

#[test]
fn test_scope_manager_complex_patterns() {
    let mut manager = ScopeManager::new();
    manager.set_variable("global".to_string(), 0);

    manager.enter_scope();
    manager.set_variable("level1".to_string(), 1);
    manager.set_variable("global".to_string(), 10);

    manager.enter_scope();
    manager.set_variable("level2".to_string(), 2);
    assert_eq!(manager.get_variable("global"), Some(10));
    assert_eq!(manager.get_variable("level1"), Some(1));
    assert_eq!(manager.get_variable("level2"), Some(2));

    manager.exit_scope();
    assert_eq!(manager.get_variable("global"), Some(10));
    assert_eq!(manager.get_variable("level1"), Some(1));
    assert_eq!(manager.get_variable("level2"), None);

    manager.exit_scope();
    assert_eq!(manager.get_variable("global"), Some(0));
    assert_eq!(manager.get_variable("level1"), None);
}

#[test]
fn test_scope_manager_variable_shadowing() {
    let mut manager = ScopeManager::new();
    manager.set_variable("x".to_string(), 1);

    manager.enter_scope();
    manager.set_variable("x".to_string(), 2);

    manager.enter_scope();
    manager.set_variable("x".to_string(), 3);
    assert_eq!(manager.get_variable("x"), Some(3));

    manager.exit_scope();
    assert_eq!(manager.get_variable("x"), Some(2));

    manager.exit_scope();
    assert_eq!(manager.get_variable("x"), Some(1));
}
