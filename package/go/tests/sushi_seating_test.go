package tests

import (
	"fmt"
	"strings"
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestSushiSeatingSample1(t *testing.T) {
	commands := []string{
		"arrive Alice",
		"arrive Bob",
		"seat 1",
		"arrive Charlie",
		"seat 2",
		"arrive Dave",
		"arrive Eve",
		"seat 3",
	}
	expected := []string{"Alice", "Bob", "Charlie", "Dave", "Eve"}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingSample2(t *testing.T) {
	commands := []string{
		"arrive Tom",
		"arrive Jerry",
		"arrive Spike",
		"seat 2",
		"arrive Butch",
		"seat 2",
	}
	expected := []string{"Tom", "Jerry", "Spike", "Butch"}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingSample3(t *testing.T) {
	commands := []string{"arrive Anna", "arrive Elsa", "seat 5"}
	expected := []string{"Anna", "Elsa"}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingEmptyCommands(t *testing.T) {
	commands := []string{}
	expected := []string{}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingOnlyArrivals(t *testing.T) {
	commands := []string{"arrive Alice", "arrive Bob", "arrive Charlie"}
	expected := []string{}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingOnlySeating(t *testing.T) {
	commands := []string{"seat 1", "seat 2", "seat 5"}
	expected := []string{}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingSeatZero(t *testing.T) {
	commands := []string{"arrive Alice", "seat 0", "seat 1"}
	expected := []string{"Alice"}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingDuplicateArrivalsIgnored(t *testing.T) {
	commands := []string{
		"arrive Alice",
		"arrive Alice", // Should be ignored
		"arrive Bob",
		"seat 2",
	}
	expected := []string{"Alice", "Bob"}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingMultipleSeatCommands(t *testing.T) {
	commands := []string{
		"arrive A",
		"arrive B",
		"arrive C",
		"arrive D",
		"seat 1",
		"seat 1",
		"seat 1",
		"seat 1",
	}
	expected := []string{"A", "B", "C", "D"}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingMixedOperations(t *testing.T) {
	commands := []string{
		"arrive John",
		"seat 1",
		"arrive Jane",
		"arrive Jack",
		"seat 1",
		"arrive Jill",
		"seat 2",
	}
	expected := []string{"John", "Jane", "Jack", "Jill"}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingLargeSeatNumber(t *testing.T) {
	commands := []string{
		"arrive A",
		"arrive B",
		"seat 100", // Much larger than queue size
	}
	expected := []string{"A", "B"}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingFIFOOrderMaintained(t *testing.T) {
	commands := []string{
		"arrive First",
		"arrive Second",
		"arrive Third",
		"arrive Fourth",
		"seat 2",
		"arrive Fifth",
		"seat 3",
	}
	expected := []string{"First", "Second", "Third", "Fourth", "Fifth"}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingLargePerformance(t *testing.T) {
	// Large scale performance test.
	// Test 1000 customers and multiple seat guidance.
	var commands []string
	// 1000 customers arrive
	for i := 0; i < 1000; i++ {
		commands = append(commands, fmt.Sprintf("arrive Customer%04d", i))
	}

	// Seat 100 customers at a time, 10 times
	for i := 0; i < 10; i++ {
		commands = append(commands, "seat 100")
	}

	result := src.SushiSeating(commands)

	// Confirm that all customers are seated in the correct order
	var expected []string
	for i := 0; i < 1000; i++ {
		expected = append(expected, fmt.Sprintf("Customer%04d", i))
	}
	assert.Equal(t, expected, result)
}

func TestSushiSeatingNegativeSeatNumbers(t *testing.T) {
	// Test behavior with negative seat numbers.
	commands := []string{
		"arrive Alice",
		"arrive Bob",
		"seat -1", // Negative value
		"seat 0",  // Zero
		"seat 1",  // Positive value
	}
	expected := []string{"Alice"}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingVeryLongCustomerNames(t *testing.T) {
	// Test with very long customer names.
	longName := strings.Repeat("A", 100) // 100-character name
	commands := []string{
		"arrive " + longName,
		"arrive Bob",
		"seat 2",
	}
	expected := []string{longName, "Bob"}
	assert.Equal(t, expected, src.SushiSeating(commands))
}

func TestSushiSeatingSpecialCharactersInNames(t *testing.T) {
	// Test with customer names containing special characters.
	commands := []string{
		"arrive Alice-123",
		"arrive Bob_456",
		"arrive Charlie.789",
		"seat 3",
	}
	expected := []string{"Alice-123", "Bob_456", "Charlie.789"}
	assert.Equal(t, expected, src.SushiSeating(commands))
}
