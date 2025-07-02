package src

// ScopeManager manages variable scopes in a lexical scoping system
type ScopeManager struct {
	// Stack of scopes, where each scope is a map of variable names to values
	// The first scope is always the global scope
	scopes []map[string]int
}

// NewScopeManager creates a new scope manager with a global scope
func NewScopeManager() *ScopeManager {
	return &ScopeManager{
		scopes: []map[string]int{make(map[string]int)},
	}
}

// EnterScope creates a new scope and makes the current scope one level deeper
func (sm *ScopeManager) EnterScope() {
	// Add a new dictionary to the stack to represent the inner scope
	sm.scopes = append(sm.scopes, make(map[string]int))
}

// ExitScope destroys the current scope and returns to the one level up scope.
// If attempting to delete the top-level global scope, it does nothing.
func (sm *ScopeManager) ExitScope() {
	// Do nothing if the global scope is the only one
	if len(sm.scopes) > 1 {
		sm.scopes = sm.scopes[:len(sm.scopes)-1]
	}
}

// SetVariable saves the value with the variable name as the key in the current scope
func (sm *ScopeManager) SetVariable(name string, value int) {
	// Set the variable in the current (innermost) scope
	sm.scopes[len(sm.scopes)-1][name] = value
}

// GetVariable searches for the variable name from the current scope in order,
// and returns its value if found.
// Returns nil if it doesn't exist in any scope.
func (sm *ScopeManager) GetVariable(name string) *int {
	// Search from the innermost scope in order and return the first variable found (lexical scope behavior)
	for i := len(sm.scopes) - 1; i >= 0; i-- {
		if value, exists := sm.scopes[i][name]; exists {
			return &value
		}
	}
	return nil
}
