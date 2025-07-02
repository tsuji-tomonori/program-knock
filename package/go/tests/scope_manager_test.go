package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

// Sample test case 1
func TestScopeManagerSample1(t *testing.T) {
	sm := src.NewScopeManager()
	sm.SetVariable("x", 10)

	result := sm.GetVariable("x")
	assert.NotNil(t, result)
	assert.Equal(t, 10, *result) // "x" set in global scope is 10

	sm.EnterScope()
	result = sm.GetVariable("x")
	assert.NotNil(t, result)
	assert.Equal(t, 10, *result) // Outer "x" can be referenced from inner scope

	sm.SetVariable("x", 20)
	result = sm.GetVariable("x")
	assert.NotNil(t, result)
	assert.Equal(t, 20, *result) // "x" overwritten in inner scope is 20

	sm.ExitScope()
	result = sm.GetVariable("x")
	assert.NotNil(t, result)
	assert.Equal(t, 10, *result) // After exiting scope, global scope "x" (10) is referenced
}

// Sample test case 2
func TestScopeManagerSample2(t *testing.T) {
	sm := src.NewScopeManager()
	sm.SetVariable("y", 5)

	sm.EnterScope()
	sm.EnterScope()
	sm.SetVariable("z", 30)

	result := sm.GetVariable("z")
	assert.NotNil(t, result)
	assert.Equal(t, 30, *result)

	result = sm.GetVariable("y")
	assert.NotNil(t, result)
	assert.Equal(t, 5, *result) // Get "y" from outer scope

	sm.ExitScope()
	result = sm.GetVariable("z")
	assert.Nil(t, result) // "z" doesn't exist because inner scope was destroyed
}

// Sample test case 3
func TestScopeManagerSample3(t *testing.T) {
	sm := src.NewScopeManager()
	result := sm.GetVariable("unknown")
	assert.Nil(t, result) // Unset variable returns nil

	sm.EnterScope()
	result = sm.GetVariable("unknown")
	assert.Nil(t, result) // Returns nil if not found in inner scope either
}

// Sample test case 4
func TestScopeManagerSample4(t *testing.T) {
	sm := src.NewScopeManager()
	sm.SetVariable("a", 100)
	sm.SetVariable("b", 200)

	sm.EnterScope()
	sm.SetVariable("b", 300) // Overwrite "b" in inner scope

	result := sm.GetVariable("a")
	assert.NotNil(t, result)
	assert.Equal(t, 100, *result) // Outer "a" remains unchanged

	result = sm.GetVariable("b")
	assert.NotNil(t, result)
	assert.Equal(t, 300, *result) // Reference inner "b"

	sm.ExitScope()
	result = sm.GetVariable("b")
	assert.NotNil(t, result)
	assert.Equal(t, 200, *result) // Inner "b" is destroyed, outer "b" is referenced
}

// Sample test case 5
func TestScopeManagerSample5(t *testing.T) {
	sm := src.NewScopeManager()
	sm.ExitScope() // exit_scope() in global scope is ignored
	sm.SetVariable("x", 5)
	sm.ExitScope() // This is also ignored

	result := sm.GetVariable("x")
	assert.NotNil(t, result)
	assert.Equal(t, 5, *result) // "x" remains in global scope
}

// Deep nesting test (boundary value test example)
func TestScopeManagerDeepNesting(t *testing.T) {
	sm := src.NewScopeManager()
	sm.SetVariable("global", 1)

	// Create 10 levels of nesting and set unique variables at each level
	for i := 1; i <= 10; i++ {
		sm.EnterScope()
		sm.SetVariable("var"+string(rune(i+'0')), i)
		// Global variable should always be referenceable
		result := sm.GetVariable("global")
		assert.NotNil(t, result)
		assert.Equal(t, 1, *result)
	}

	// Exit scopes in order from innermost, confirming each scope's variables are destroyed
	for i := 10; i >= 1; i-- {
		result := sm.GetVariable("var" + string(rune(i+'0')))
		assert.NotNil(t, result)
		assert.Equal(t, i, *result)

		sm.ExitScope()
		// Variables set in that scope can no longer be referenced
		result = sm.GetVariable("var" + string(rune(i+'0')))
		assert.Nil(t, result)
	}

	// Finally, global variable should remain
	result := sm.GetVariable("global")
	assert.NotNil(t, result)
	assert.Equal(t, 1, *result)
}

// Test handling multiple variables in same scope
func TestScopeManagerMultipleVariables(t *testing.T) {
	sm := src.NewScopeManager()
	sm.SetVariable("a", 1)
	sm.SetVariable("b", 2)
	sm.SetVariable("c", 3)

	result := sm.GetVariable("a")
	assert.NotNil(t, result)
	assert.Equal(t, 1, *result)

	result = sm.GetVariable("b")
	assert.NotNil(t, result)
	assert.Equal(t, 2, *result)

	result = sm.GetVariable("c")
	assert.NotNil(t, result)
	assert.Equal(t, 3, *result)

	sm.EnterScope()
	// Overwrite "b" in inner scope, other variables reference outer values
	sm.SetVariable("b", 20)

	result = sm.GetVariable("a")
	assert.NotNil(t, result)
	assert.Equal(t, 1, *result)

	result = sm.GetVariable("b")
	assert.NotNil(t, result)
	assert.Equal(t, 20, *result)

	result = sm.GetVariable("c")
	assert.NotNil(t, result)
	assert.Equal(t, 3, *result)

	sm.ExitScope()

	// Inner overwrite is destroyed
	result = sm.GetVariable("b")
	assert.NotNil(t, result)
	assert.Equal(t, 2, *result)
}

// Test variable overwriting within same scope
func TestScopeManagerOverwritingSameScope(t *testing.T) {
	sm := src.NewScopeManager()
	sm.SetVariable("x", 100)

	result := sm.GetVariable("x")
	assert.NotNil(t, result)
	assert.Equal(t, 100, *result)

	sm.SetVariable("x", 200)
	result = sm.GetVariable("x")
	assert.NotNil(t, result)
	assert.Equal(t, 200, *result) // Overwritten value in same scope is referenced
}

// Boundary value test for variable names: handling empty strings and special character keys
func TestScopeManagerBoundaryVariableNames(t *testing.T) {
	sm := src.NewScopeManager()
	sm.SetVariable("", 0)
	sm.SetVariable("!@#$%^&*()", 999)

	result := sm.GetVariable("")
	assert.NotNil(t, result)
	assert.Equal(t, 0, *result)

	result = sm.GetVariable("!@#$%^&*()")
	assert.NotNil(t, result)
	assert.Equal(t, 999, *result)

	sm.EnterScope()
	// Check if outer values are visible without overwriting in inner scope
	result = sm.GetVariable("")
	assert.NotNil(t, result)
	assert.Equal(t, 0, *result)

	sm.SetVariable("", 1)
	result = sm.GetVariable("")
	assert.NotNil(t, result)
	assert.Equal(t, 1, *result)

	sm.ExitScope()
	result = sm.GetVariable("")
	assert.NotNil(t, result)
	assert.Equal(t, 0, *result)
}

// Boundary value test handling extreme integer values
func TestScopeManagerBoundaryExtremeValues(t *testing.T) {
	sm := src.NewScopeManager()
	minVal := -(1 << 31)
	maxVal := (1 << 31) - 1
	sm.SetVariable("min", minVal)
	sm.SetVariable("max", maxVal)

	result := sm.GetVariable("min")
	assert.NotNil(t, result)
	assert.Equal(t, minVal, *result)

	result = sm.GetVariable("max")
	assert.NotNil(t, result)
	assert.Equal(t, maxVal, *result)

	sm.EnterScope()
	newMin := -(1 << 63)
	newMax := (1 << 63) - 1
	sm.SetVariable("min", newMin)
	sm.SetVariable("max", newMax)

	result = sm.GetVariable("min")
	assert.NotNil(t, result)
	assert.Equal(t, newMin, *result)

	result = sm.GetVariable("max")
	assert.NotNil(t, result)
	assert.Equal(t, newMax, *result)

	sm.ExitScope()

	// After exiting scope, returns to original values
	result = sm.GetVariable("min")
	assert.NotNil(t, result)
	assert.Equal(t, minVal, *result)

	result = sm.GetVariable("max")
	assert.NotNil(t, result)
	assert.Equal(t, maxVal, *result)
}

// Test entering and exiting scopes
func TestScopeManagerReenterScopePreservesGlobalVariables(t *testing.T) {
	// Set variable "x" to 10 in global scope
	sm := src.NewScopeManager()
	sm.SetVariable("x", 10)

	result := sm.GetVariable("x")
	assert.NotNil(t, result)
	assert.Equal(t, 10, *result)

	// Enter inner scope and overwrite "x" to 20
	sm.EnterScope()
	sm.SetVariable("x", 20)

	result = sm.GetVariable("x")
	assert.NotNil(t, result)
	assert.Equal(t, 20, *result)

	// After exiting inner scope, global scope "x" value (10) should be restored
	sm.ExitScope()
	result = sm.GetVariable("x")
	assert.NotNil(t, result)
	assert.Equal(t, 10, *result)

	// When entering inner scope again, global scope "x" should be referenceable
	sm.EnterScope()
	// At this point, global value 10 should be referenced before being overwritten in inner scope
	result = sm.GetVariable("x")
	assert.NotNil(t, result)
	assert.Equal(t, 10, *result)

	// Test overwriting "x" to 30 in inner scope and exiting scope again
	sm.SetVariable("x", 30)
	result = sm.GetVariable("x")
	assert.NotNil(t, result)
	assert.Equal(t, 30, *result)

	sm.ExitScope()
	// After exiting scope, global "x" (10) should be referenced again
	result = sm.GetVariable("x")
	assert.NotNil(t, result)
	assert.Equal(t, 10, *result)
}
