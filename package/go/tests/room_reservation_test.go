package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestRoomReservationBasic(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{1, 10, 20}))  // Reservation successful
	assert.False(t, system.RequestReservation(src.Reservation{1, 15, 25})) // Rejected due to time overlap
	assert.True(t, system.RequestReservation(src.Reservation{1, 20, 30}))  // Reservation successful
	assert.True(t, system.RequestReservation(src.Reservation{2, 10, 20}))  // Different room, reservation successful
	assert.False(t, system.RequestReservation(src.Reservation{2, 15, 25})) // Room 2 reservation overlaps
}

func TestRoomReservationEdgeCases(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{1, 0, 500}))    // Long reservation successful
	assert.True(t, system.RequestReservation(src.Reservation{1, 500, 1000})) // Consecutive but non-overlapping reservation times
	assert.False(t, system.RequestReservation(src.Reservation{1, 499, 501})) // Rejected due to overlapping reservation times
}

func TestRoomReservationEmptySystem(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{1, 10, 20}))
}

func TestRoomReservationAdjacentReservations(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{1, 10, 20}))
	assert.True(t, system.RequestReservation(src.Reservation{1, 20, 30})) // Adjacent, no overlap
	assert.True(t, system.RequestReservation(src.Reservation{1, 0, 10}))  // Adjacent, no overlap
}

func TestRoomReservationExactOverlap(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{1, 10, 20}))
	assert.False(t, system.RequestReservation(src.Reservation{1, 10, 20})) // Exact same time
}

func TestRoomReservationPartialOverlapStart(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{1, 10, 20}))
	assert.False(t, system.RequestReservation(src.Reservation{1, 5, 15})) // Overlaps at start
}

func TestRoomReservationPartialOverlapEnd(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{1, 10, 20}))
	assert.False(t, system.RequestReservation(src.Reservation{1, 15, 25})) // Overlaps at end
}

func TestRoomReservationContainedReservation(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{1, 10, 30}))
	assert.False(t, system.RequestReservation(src.Reservation{1, 15, 25})) // Contained within existing
}

func TestRoomReservationContainingReservation(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{1, 15, 25}))
	assert.False(t, system.RequestReservation(src.Reservation{1, 10, 30})) // Contains existing
}

func TestRoomReservationMultipleRooms(t *testing.T) {
	system := src.NewRoomReservation()
	// Same time slots for different rooms should work
	assert.True(t, system.RequestReservation(src.Reservation{1, 10, 20}))
	assert.True(t, system.RequestReservation(src.Reservation{2, 10, 20}))
	assert.True(t, system.RequestReservation(src.Reservation{3, 10, 20}))

	// But overlapping in same room should fail
	assert.False(t, system.RequestReservation(src.Reservation{1, 15, 25}))
}

func TestRoomReservationNonOverlappingSequence(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{1, 10, 20}))
	assert.True(t, system.RequestReservation(src.Reservation{1, 30, 40}))
	assert.True(t, system.RequestReservation(src.Reservation{1, 50, 60}))
	assert.True(t, system.RequestReservation(src.Reservation{1, 0, 5}))
}

func TestRoomReservationGapFilling(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{1, 10, 20}))
	assert.True(t, system.RequestReservation(src.Reservation{1, 30, 40}))
	assert.True(t, system.RequestReservation(src.Reservation{1, 20, 30})) // Fill the gap
}

func TestRoomReservationBoundaryValues(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{1, 0, 1}))
	assert.True(t, system.RequestReservation(src.Reservation{1, 999, 1000}))
	assert.True(t, system.RequestReservation(src.Reservation{100, 500, 501}))
}
