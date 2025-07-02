package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.*;

import static org.junit.jupiter.api.Assertions.*;

class SushiSeatingTest {

    @Test
    void testSample1() {
        List<String> commands = Arrays.asList(
            "arrive Alice",
            "arrive Bob",
            "seat 1",
            "arrive Charlie",
            "seat 2",
            "arrive Dave",
            "arrive Eve",
            "seat 3"
        );
        List<String> expected = Arrays.asList("Alice", "Bob", "Charlie", "Dave", "Eve");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testSample2() {
        List<String> commands = Arrays.asList(
            "arrive Tom",
            "arrive Jerry",
            "arrive Spike",
            "seat 2",
            "arrive Butch",
            "seat 2"
        );
        List<String> expected = Arrays.asList("Tom", "Jerry", "Spike", "Butch");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testSample3() {
        List<String> commands = Arrays.asList("arrive Anna", "arrive Elsa", "seat 5");
        List<String> expected = Arrays.asList("Anna", "Elsa");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testEmptyCommands() {
        List<String> commands = new ArrayList<>();
        List<String> expected = new ArrayList<>();
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testOnlyArrivals() {
        List<String> commands = Arrays.asList("arrive Alice", "arrive Bob", "arrive Charlie");
        List<String> expected = new ArrayList<>();
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testOnlySeating() {
        List<String> commands = Arrays.asList("seat 1", "seat 2", "seat 5");
        List<String> expected = new ArrayList<>();
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testSeatZero() {
        List<String> commands = Arrays.asList("arrive Alice", "seat 0", "seat 1");
        List<String> expected = Arrays.asList("Alice");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testDuplicateArrivalsIgnored() {
        List<String> commands = Arrays.asList(
            "arrive Alice",
            "arrive Alice", // Should be ignored
            "arrive Bob",
            "seat 2"
        );
        List<String> expected = Arrays.asList("Alice", "Bob");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testMultipleSeatCommands() {
        List<String> commands = Arrays.asList(
            "arrive A",
            "arrive B",
            "arrive C",
            "arrive D",
            "seat 1",
            "seat 1",
            "seat 1",
            "seat 1"
        );
        List<String> expected = Arrays.asList("A", "B", "C", "D");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testMixedOperations() {
        List<String> commands = Arrays.asList(
            "arrive John",
            "seat 1",
            "arrive Jane",
            "arrive Jack",
            "seat 1",
            "arrive Jill",
            "seat 2"
        );
        List<String> expected = Arrays.asList("John", "Jane", "Jack", "Jill");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testLargeSeatNumber() {
        List<String> commands = Arrays.asList(
            "arrive A",
            "arrive B",
            "seat 100" // Much larger than queue size
        );
        List<String> expected = Arrays.asList("A", "B");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testFifoOrderMaintained() {
        List<String> commands = Arrays.asList(
            "arrive First",
            "arrive Second",
            "arrive Third",
            "arrive Fourth",
            "seat 2",
            "arrive Fifth",
            "seat 3"
        );
        List<String> expected = Arrays.asList("First", "Second", "Third", "Fourth", "Fifth");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testLargePerformance() {
        // Large performance test
        // 1000 customers arrive
        List<String> commands = new ArrayList<>();
        for (int i = 0; i < 1000; i++) {
            commands.add(String.format("arrive Customer%04d", i));
        }

        // Seat 100 customers at a time, 10 times
        for (int i = 0; i < 10; i++) {
            commands.add("seat 100");
        }

        List<String> result = SushiSeating.sushiSeating(commands);

        // Verify all customers are seated in correct order
        List<String> expected = new ArrayList<>();
        for (int i = 0; i < 1000; i++) {
            expected.add(String.format("Customer%04d", i));
        }
        assertEquals(expected, result);
    }

    @Test
    void testNegativeSeatNumbers() {
        // Test behavior with negative seat numbers
        List<String> commands = Arrays.asList(
            "arrive Alice",
            "arrive Bob",
            "seat -1", // Negative value
            "seat 0",  // Zero
            "seat 1"   // Positive value
        );
        List<String> expected = Arrays.asList("Alice");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testVeryLongCustomerNames() {
        // Test with very long customer names
        String longName = "A".repeat(100); // 100 character name
        List<String> commands = Arrays.asList(
            "arrive " + longName,
            "arrive Bob",
            "seat 2"
        );
        List<String> expected = Arrays.asList(longName, "Bob");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testSpecialCharactersInNames() {
        // Test with special characters in customer names
        List<String> commands = Arrays.asList(
            "arrive Alice-123",
            "arrive Bob_456",
            "arrive Charlie.789",
            "seat 3"
        );
        List<String> expected = Arrays.asList("Alice-123", "Bob_456", "Charlie.789");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testSingleCustomer() {
        List<String> commands = Arrays.asList(
            "arrive Solo",
            "seat 1"
        );
        List<String> expected = Arrays.asList("Solo");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testAlternatingArrivalsAndSeating() {
        List<String> commands = Arrays.asList(
            "arrive A",
            "seat 1",
            "arrive B",
            "seat 1",
            "arrive C",
            "seat 1"
        );
        List<String> expected = Arrays.asList("A", "B", "C");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }

    @Test
    void testMultipleSeatsWithSameCustomers() {
        List<String> commands = Arrays.asList(
            "arrive X",
            "arrive Y",
            "arrive Z",
            "seat 1",
            "seat 1",
            "seat 1",
            "seat 5" // No more customers to seat
        );
        List<String> expected = Arrays.asList("X", "Y", "Z");
        assertEquals(expected, SushiSeating.sushiSeating(commands));
    }
}
