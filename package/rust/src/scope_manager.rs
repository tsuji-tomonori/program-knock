use std::collections::HashMap;

pub struct ScopeManager {
    scopes: Vec<HashMap<String, i32>>,
}

impl Default for ScopeManager {
    fn default() -> Self {
        Self::new()
    }
}

impl ScopeManager {
    pub fn new() -> Self {
        ScopeManager {
            scopes: vec![HashMap::new()],
        }
    }

    pub fn enter_scope(&mut self) {
        self.scopes.push(HashMap::new());
    }

    pub fn exit_scope(&mut self) {
        if self.scopes.len() > 1 {
            self.scopes.pop();
        }
    }

    pub fn set_variable(&mut self, name: String, value: i32) {
        if let Some(current_scope) = self.scopes.last_mut() {
            current_scope.insert(name, value);
        }
    }

    pub fn get_variable(&self, name: &str) -> Option<i32> {
        for scope in self.scopes.iter().rev() {
            if let Some(&value) = scope.get(name) {
                return Some(value);
            }
        }
        None
    }
}
