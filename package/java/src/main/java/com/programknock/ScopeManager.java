package com.programknock;

import java.util.*;

public class ScopeManager {
    private List<Map<String, Integer>> scopes;

    public ScopeManager() {
        // Initialize with global scope
        scopes = new ArrayList<>();
        scopes.add(new HashMap<>());
    }

    public void enterScope() {
        // Create a new scope by adding a new dictionary to the stack
        scopes.add(new HashMap<>());
    }

    public void exitScope() {
        // Exit the current scope and return to the outer scope
        // Do nothing if trying to delete the global scope (top-level scope)
        if (scopes.size() > 1) {
            scopes.remove(scopes.size() - 1);
        }
    }

    public void setVariable(String name, int value) {
        // Set the variable in the current (innermost) scope
        scopes.get(scopes.size() - 1).put(name, value);
    }

    public Integer getVariable(String name) {
        // Search for the variable starting from the current scope towards outer scopes
        // This implements lexical scoping behavior
        for (int i = scopes.size() - 1; i >= 0; i--) {
            Map<String, Integer> scope = scopes.get(i);
            if (scope.containsKey(name)) {
                return scope.get(name);
            }
        }
        return null;
    }
}
