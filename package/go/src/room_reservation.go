package src

// Reservation represents a meeting room reservation request
type Reservation struct {
	RoomID    int // Meeting room ID (1 <= room_id <= 100)
	StartTime int // Reservation start time (0 <= start_time < end_time <= 1000)
	EndTime   int // Reservation end time (start_time < end_time <= 1000)
}

// TimeSlot represents a time slot with start and end times
type TimeSlot struct {
	Start int
	End   int
}

// RoomReservation manages meeting room reservation system
type RoomReservation struct {
	// Map to store reservations for each room
	// Key: room_id, Value: slice of TimeSlot
	reservations map[int][]TimeSlot
}

// NewRoomReservation creates a new room reservation system
func NewRoomReservation() *RoomReservation {
	return &RoomReservation{
		reservations: make(map[int][]TimeSlot),
	}
}

// RequestReservation processes a meeting room reservation request.
//
// Returns true if the reservation is approved, false if rejected.
//
// Parameters:
//   - reservation: reservation request information
//
// Returns:
//   - true if reservation is accepted, false if rejected
func (rr *RoomReservation) RequestReservation(reservation Reservation) bool {
	roomID := reservation.RoomID
	startTime := reservation.StartTime
	endTime := reservation.EndTime

	// Initialize room if not exists
	if _, exists := rr.reservations[roomID]; !exists {
		rr.reservations[roomID] = []TimeSlot{}
	}

	// Check for overlapping reservations
	for _, existing := range rr.reservations[roomID] {
		// Check if the new reservation overlaps with existing ones
		// Overlap occurs if: new_start < existing_end and new_end > existing_start
		if startTime < existing.End && endTime > existing.Start {
			return false
		}
	}

	// No overlap found, add the reservation
	rr.reservations[roomID] = append(rr.reservations[roomID], TimeSlot{
		Start: startTime,
		End:   endTime,
	})
	return true
}
