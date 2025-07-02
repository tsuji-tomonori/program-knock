use std::collections::HashMap;

#[derive(Debug, Clone)]
pub struct Reservation {
    pub room_id: String,
    pub start_time: i32,
    pub end_time: i32,
}

pub struct RoomReservation {
    reservations: HashMap<String, Vec<(i32, i32)>>,
}

impl Default for RoomReservation {
    fn default() -> Self {
        Self::new()
    }
}

impl RoomReservation {
    pub fn new() -> Self {
        RoomReservation {
            reservations: HashMap::new(),
        }
    }

    pub fn request_reservation(&mut self, reservation: Reservation) -> bool {
        let room_reservations = self.reservations.entry(reservation.room_id.clone()).or_default();

        for &(start, end) in room_reservations.iter() {
            if !(reservation.end_time <= start || reservation.start_time >= end) {
                return false;
            }
        }

        room_reservations.push((reservation.start_time, reservation.end_time));
        room_reservations.sort();
        true
    }
}
