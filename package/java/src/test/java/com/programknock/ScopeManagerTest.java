package com.programknock;

import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class ScopeManagerTest {

    @Test
    void testSample1() {
        ScopeManager sm = new ScopeManager();
        sm.setVariable("x", 10);
        assertEquals(Integer.valueOf(10), sm.getVariable("x")); // Global scope "x" is 10

        sm.enterScope();
        assertEquals(Integer.valueOf(10), sm.getVariable("x")); // Inner scope can reference outer "x"

        sm.setVariable("x", 20);
        assertEquals(Integer.valueOf(20), sm.getVariable("x")); // Inner scope overrides "x" to 20

        sm.exitScope();
        assertEquals(Integer.valueOf(10), sm.getVariable("x")); // Back to global scope "x" (10)
    }

    @Test
    void testSample2() {
        ScopeManager sm = new ScopeManager();
        sm.setVariable("y", 5);

        sm.enterScope();
        sm.enterScope();
        sm.setVariable("z", 30);
        assertEquals(Integer.valueOf(30), sm.getVariable("z"));
        assertEquals(Integer.valueOf(5), sm.getVariable("y")); // Get "y" from outer scope

        sm.exitScope();
        assertNull(sm.getVariable("z")); // "z" doesn't exist because inner scope was destroyed
    }

    @Test
    void testSample3() {
        ScopeManager sm = new ScopeManager();
        assertNull(sm.getVariable("unknown")); // Unset variable is null

        sm.enterScope();
        assertNull(sm.getVariable("unknown")); // Not found in inner scope either
    }

    @Test
    void testSample4() {
        ScopeManager sm = new ScopeManager();
        sm.setVariable("a", 100);
        sm.setVariable("b", 200);

        sm.enterScope();
        sm.setVariable("b", 300); // Override "b" in new scope
        assertEquals(Integer.valueOf(100), sm.getVariable("a")); // Outer "a" remains
        assertEquals(Integer.valueOf(300), sm.getVariable("b")); // Inner "b" is referenced

        sm.exitScope();
        assertEquals(Integer.valueOf(200), sm.getVariable("b")); // Inner "b" is destroyed, outer "b" is referenced
    }

    @Test
    void testSample5() {
        ScopeManager sm = new ScopeManager();
        sm.exitScope(); // exit_scope() at global scope is ignored
        sm.setVariable("x", 5);
        sm.exitScope(); // This is also ignored
        assertEquals(Integer.valueOf(5), sm.getVariable("x")); // "x" remains in global scope
    }

    @Test
    void testDeepNesting() {
        ScopeManager sm = new ScopeManager();
        sm.setVariable("global", 1);

        // Create 10 levels of nesting and set unique variables at each level
        for (int i = 1; i <= 10; i++) {
            sm.enterScope();
            sm.setVariable("var" + i, i);
            // Global variable should always be accessible
            assertEquals(Integer.valueOf(1), sm.getVariable("global"));
        }

        // Exit scopes in reverse order and confirm each scope's variables are destroyed
        for (int i = 10; i >= 1; i--) {
            assertEquals(Integer.valueOf(i), sm.getVariable("var" + i));
            sm.exitScope();
            // Variables from that scope should no longer be accessible
            assertNull(sm.getVariable("var" + i));
        }

        // Global variable should still remain
        assertEquals(Integer.valueOf(1), sm.getVariable("global"));
    }

    @Test
    void testMultipleVariables() {
        ScopeManager sm = new ScopeManager();
        sm.setVariable("a", 1);
        sm.setVariable("b", 2);
        sm.setVariable("c", 3);
        assertEquals(Integer.valueOf(1), sm.getVariable("a"));
        assertEquals(Integer.valueOf(2), sm.getVariable("b"));
        assertEquals(Integer.valueOf(3), sm.getVariable("c"));

        sm.enterScope();
        // Override "b" in inner scope, other variables reference outer values
        sm.setVariable("b", 20);
        assertEquals(Integer.valueOf(1), sm.getVariable("a"));
        assertEquals(Integer.valueOf(20), sm.getVariable("b"));
        assertEquals(Integer.valueOf(3), sm.getVariable("c"));
        sm.exitScope();

        // Inner override is destroyed
        assertEquals(Integer.valueOf(2), sm.getVariable("b"));
    }

    @Test
    void testOverwritingSameScope() {
        ScopeManager sm = new ScopeManager();
        sm.setVariable("x", 100);
        assertEquals(Integer.valueOf(100), sm.getVariable("x"));
        sm.setVariable("x", 200);
        assertEquals(Integer.valueOf(200), sm.getVariable("x")); // Overwritten value in same scope is referenced
    }

    @Test
    void testBoundaryVariableNames() {
        ScopeManager sm = new ScopeManager();
        sm.setVariable("", 0);
        sm.setVariable("!@#$%^&*()", 999);
        assertEquals(Integer.valueOf(0), sm.getVariable(""));
        assertEquals(Integer.valueOf(999), sm.getVariable("!@#$%^&*()"));

        sm.enterScope();
        // Confirm outer values are visible without override in inner scope
        assertEquals(Integer.valueOf(0), sm.getVariable(""));
        sm.setVariable("", 1);
        assertEquals(Integer.valueOf(1), sm.getVariable(""));
        sm.exitScope();
        assertEquals(Integer.valueOf(0), sm.getVariable(""));
    }

    @Test
    void testBoundaryExtremeValues() {
        ScopeManager sm = new ScopeManager();
        int minVal = Integer.MIN_VALUE;
        int maxVal = Integer.MAX_VALUE;
        sm.setVariable("min", minVal);
        sm.setVariable("max", maxVal);
        assertEquals(Integer.valueOf(minVal), sm.getVariable("min"));
        assertEquals(Integer.valueOf(maxVal), sm.getVariable("max"));

        sm.enterScope();
        int newMin = -1000000000;
        int newMax = 1000000000;
        sm.setVariable("min", newMin);
        sm.setVariable("max", newMax);
        assertEquals(Integer.valueOf(newMin), sm.getVariable("min"));
        assertEquals(Integer.valueOf(newMax), sm.getVariable("max"));
        sm.exitScope();

        // After exiting scope, return to original values
        assertEquals(Integer.valueOf(minVal), sm.getVariable("min"));
        assertEquals(Integer.valueOf(maxVal), sm.getVariable("max"));
    }

    @Test
    void testReenterScopePreservesGlobalVariables() {
        // Set variable "x" to 10 in global scope
        ScopeManager sm = new ScopeManager();
        sm.setVariable("x", 10);
        assertEquals(Integer.valueOf(10), sm.getVariable("x"));

        // Enter inner scope and override "x" to 20
        sm.enterScope();
        sm.setVariable("x", 20);
        assertEquals(Integer.valueOf(20), sm.getVariable("x"));

        // Exit inner scope should restore global scope "x" value (10)
        sm.exitScope();
        assertEquals(Integer.valueOf(10), sm.getVariable("x"));

        // Re-enter inner scope should still be able to reference global scope "x"
        sm.enterScope();
        // At this point, global value 10 should be referenced before being overridden in inner scope
        assertEquals(Integer.valueOf(10), sm.getVariable("x"));

        // Override "x" to 30 in inner scope, then exit scope again
        sm.setVariable("x", 30);
        assertEquals(Integer.valueOf(30), sm.getVariable("x"));
        sm.exitScope();
        // After exiting scope, global "x" (10) should be referenced again
        assertEquals(Integer.valueOf(10), sm.getVariable("x"));
    }

    @Test
    void testZeroValue() {
        ScopeManager sm = new ScopeManager();
        sm.setVariable("zero", 0);
        assertEquals(Integer.valueOf(0), sm.getVariable("zero"));

        sm.enterScope();
        assertEquals(Integer.valueOf(0), sm.getVariable("zero"));
        sm.setVariable("zero", -1);
        assertEquals(Integer.valueOf(-1), sm.getVariable("zero"));
        sm.exitScope();
        assertEquals(Integer.valueOf(0), sm.getVariable("zero"));
    }

    @Test
    void testLongVariableName() {
        ScopeManager sm = new ScopeManager();
        String longName = "a".repeat(1000);
        sm.setVariable(longName, 42);
        assertEquals(Integer.valueOf(42), sm.getVariable(longName));
    }

    @Test
    void testManyVariablesInSingleScope() {
        ScopeManager sm = new ScopeManager();
        for (int i = 0; i < 1000; i++) {
            sm.setVariable("var" + i, i);
        }

        for (int i = 0; i < 1000; i++) {
            assertEquals(Integer.valueOf(i), sm.getVariable("var" + i));
        }
    }
}
