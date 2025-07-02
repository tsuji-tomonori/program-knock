package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestRoomReservationBasic(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 10, EndTime: 20}))  // Reservation successful
	assert.False(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 15, EndTime: 25})) // Rejected due to time overlap
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 20, EndTime: 30}))  // Reservation successful
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 2, StartTime: 10, EndTime: 20}))  // Different room, reservation successful
	assert.False(t, system.RequestReservation(src.Reservation{RoomID: 2, StartTime: 15, EndTime: 25})) // Room 2 reservation overlaps
}

func TestRoomReservationEdgeCases(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 0, EndTime: 500}))    // Long reservation successful
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 500, EndTime: 1000})) // Consecutive but non-overlapping reservation times
	assert.False(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 499, EndTime: 501})) // Rejected due to overlapping reservation times
}

func TestRoomReservationEmptySystem(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 10, EndTime: 20}))
}

func TestRoomReservationAdjacentReservations(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 10, EndTime: 20}))
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 20, EndTime: 30})) // Adjacent, no overlap
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 0, EndTime: 10}))  // Adjacent, no overlap
}

func TestRoomReservationExactOverlap(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 10, EndTime: 20}))
	assert.False(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 10, EndTime: 20})) // Exact same time
}

func TestRoomReservationPartialOverlapStart(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 10, EndTime: 20}))
	assert.False(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 5, EndTime: 15})) // Overlaps at start
}

func TestRoomReservationPartialOverlapEnd(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 10, EndTime: 20}))
	assert.False(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 15, EndTime: 25})) // Overlaps at end
}

func TestRoomReservationContainedReservation(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 10, EndTime: 30}))
	assert.False(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 15, EndTime: 25})) // Contained within existing
}

func TestRoomReservationContainingReservation(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 15, EndTime: 25}))
	assert.False(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 10, EndTime: 30})) // Contains existing
}

func TestRoomReservationMultipleRooms(t *testing.T) {
	system := src.NewRoomReservation()
	// Same time slots for different rooms should work
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 10, EndTime: 20}))
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 2, StartTime: 10, EndTime: 20}))
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 3, StartTime: 10, EndTime: 20}))

	// But overlapping in same room should fail
	assert.False(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 15, EndTime: 25}))
}

func TestRoomReservationNonOverlappingSequence(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 10, EndTime: 20}))
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 30, EndTime: 40}))
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 50, EndTime: 60}))
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 0, EndTime: 5}))
}

func TestRoomReservationGapFilling(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 10, EndTime: 20}))
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 30, EndTime: 40}))
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 20, EndTime: 30})) // Fill the gap
}

func TestRoomReservationBoundaryValues(t *testing.T) {
	system := src.NewRoomReservation()
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 0, EndTime: 1}))
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 1, StartTime: 999, EndTime: 1000}))
	assert.True(t, system.RequestReservation(src.Reservation{RoomID: 100, StartTime: 500, EndTime: 501}))
}
