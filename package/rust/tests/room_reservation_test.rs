use program_knock::room_reservation::*;

#[test]
fn test_room_reservation_basic() {
    let mut system = RoomReservation::new();

    let reservation1 = Reservation {
        room_id: "Room A".to_string(),
        start_time: 9,
        end_time: 11,
    };
    assert!(system.request_reservation(reservation1));

    let reservation2 = Reservation {
        room_id: "Room A".to_string(),
        start_time: 11,
        end_time: 13,
    };
    assert!(system.request_reservation(reservation2));

    let reservation3 = Reservation {
        room_id: "Room A".to_string(),
        start_time: 10,
        end_time: 12,
    };
    assert!(!system.request_reservation(reservation3));
}

#[test]
fn test_room_reservation_boundary_times() {
    let mut system = RoomReservation::new();

    let reservation1 = Reservation {
        room_id: "Room A".to_string(),
        start_time: 9,
        end_time: 11,
    };
    assert!(system.request_reservation(reservation1));

    let reservation2 = Reservation {
        room_id: "Room A".to_string(),
        start_time: 11,
        end_time: 13,
    };
    assert!(system.request_reservation(reservation2));
}

#[test]
fn test_room_reservation_single_reservation() {
    let mut system = RoomReservation::new();

    let reservation = Reservation {
        room_id: "Room A".to_string(),
        start_time: 10,
        end_time: 12,
    };
    assert!(system.request_reservation(reservation));
}

#[test]
fn test_room_reservation_identical_times() {
    let mut system = RoomReservation::new();

    let reservation1 = Reservation {
        room_id: "Room A".to_string(),
        start_time: 10,
        end_time: 12,
    };
    assert!(system.request_reservation(reservation1));

    let reservation2 = Reservation {
        room_id: "Room A".to_string(),
        start_time: 10,
        end_time: 12,
    };
    assert!(!system.request_reservation(reservation2));
}

#[test]
fn test_room_reservation_overlapping() {
    let mut system = RoomReservation::new();

    let reservation1 = Reservation {
        room_id: "Room A".to_string(),
        start_time: 9,
        end_time: 12,
    };
    assert!(system.request_reservation(reservation1));

    let reservation2 = Reservation {
        room_id: "Room A".to_string(),
        start_time: 11,
        end_time: 14,
    };
    assert!(!system.request_reservation(reservation2));
}

#[test]
fn test_room_reservation_adjacent_times() {
    let mut system = RoomReservation::new();

    let reservation1 = Reservation {
        room_id: "Room A".to_string(),
        start_time: 9,
        end_time: 10,
    };
    assert!(system.request_reservation(reservation1));

    let reservation2 = Reservation {
        room_id: "Room A".to_string(),
        start_time: 10,
        end_time: 11,
    };
    assert!(system.request_reservation(reservation2));
}

#[test]
fn test_room_reservation_maximum_values() {
    let mut system = RoomReservation::new();

    let reservation = Reservation {
        room_id: "Room A".to_string(),
        start_time: i32::MAX - 1,
        end_time: i32::MAX,
    };
    assert!(system.request_reservation(reservation));
}

#[test]
fn test_room_reservation_minimum_time_width() {
    let mut system = RoomReservation::new();

    let reservation = Reservation {
        room_id: "Room A".to_string(),
        start_time: 10,
        end_time: 11,
    };
    assert!(system.request_reservation(reservation));
}

#[test]
fn test_room_reservation_complex_overlaps() {
    let mut system = RoomReservation::new();

    assert!(system.request_reservation(Reservation {
        room_id: "Room A".to_string(),
        start_time: 9,
        end_time: 11,
    }));

    assert!(system.request_reservation(Reservation {
        room_id: "Room A".to_string(),
        start_time: 13,
        end_time: 15,
    }));

    assert!(!system.request_reservation(Reservation {
        room_id: "Room A".to_string(),
        start_time: 10,
        end_time: 14,
    }));

    assert!(system.request_reservation(Reservation {
        room_id: "Room A".to_string(),
        start_time: 11,
        end_time: 13,
    }));
}

#[test]
fn test_room_reservation_performance() {
    let mut system = RoomReservation::new();

    for i in 0..1000 {
        let reservation = Reservation {
            room_id: format!("Room{}", i % 10),
            start_time: i * 2,
            end_time: i * 2 + 1,
        };
        assert!(system.request_reservation(reservation));
    }
}

#[test]
fn test_room_reservation_multi_room() {
    let mut system = RoomReservation::new();

    assert!(system.request_reservation(Reservation {
        room_id: "Room A".to_string(),
        start_time: 10,
        end_time: 12,
    }));

    assert!(system.request_reservation(Reservation {
        room_id: "Room B".to_string(),
        start_time: 10,
        end_time: 12,
    }));

    assert!(!system.request_reservation(Reservation {
        room_id: "Room A".to_string(),
        start_time: 10,
        end_time: 12,
    }));
}
