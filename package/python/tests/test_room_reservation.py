from src.room_reservation import RoomReservation, Reservation


class TestRoomReservation:
    def test_basic(self):
        system = RoomReservation()
        assert system.request_reservation(Reservation(1, 10, 20)) is True  # 予約成功
        assert (
            system.request_reservation(Reservation(1, 15, 25)) is False
        )  # 時間が重複しているため拒否
        assert system.request_reservation(Reservation(1, 20, 30)) is True  # 予約成功
        assert (
            system.request_reservation(Reservation(2, 10, 20)) is True
        )  # 別の会議室なので予約成功
        assert (
            system.request_reservation(Reservation(2, 15, 25)) is False
        )  # 会議室2の予約が重複

    def test_edge_cases(self):
        system = RoomReservation()
        assert (
            system.request_reservation(Reservation(1, 0, 500)) is True
        )  # 長時間予約成功
        assert (
            system.request_reservation(Reservation(1, 500, 1000)) is True
        )  # 予約時間が連続するが重ならない
        assert (
            system.request_reservation(Reservation(1, 499, 501)) is False
        )  # 予約時間が重なるため拒否

    def test_empty_system(self):
        system = RoomReservation()
        assert system.request_reservation(Reservation(1, 10, 20)) is True

    def test_adjacent_reservations(self):
        system = RoomReservation()
        assert system.request_reservation(Reservation(1, 10, 20)) is True
        assert (
            system.request_reservation(Reservation(1, 20, 30)) is True
        )  # Adjacent, no overlap
        assert (
            system.request_reservation(Reservation(1, 0, 10)) is True
        )  # Adjacent, no overlap

    def test_exact_overlap(self):
        system = RoomReservation()
        assert system.request_reservation(Reservation(1, 10, 20)) is True
        assert (
            system.request_reservation(Reservation(1, 10, 20)) is False
        )  # Exact same time

    def test_partial_overlap_start(self):
        system = RoomReservation()
        assert system.request_reservation(Reservation(1, 10, 20)) is True
        assert (
            system.request_reservation(Reservation(1, 5, 15)) is False
        )  # Overlaps at start

    def test_partial_overlap_end(self):
        system = RoomReservation()
        assert system.request_reservation(Reservation(1, 10, 20)) is True
        assert (
            system.request_reservation(Reservation(1, 15, 25)) is False
        )  # Overlaps at end

    def test_contained_reservation(self):
        system = RoomReservation()
        assert system.request_reservation(Reservation(1, 10, 30)) is True
        assert (
            system.request_reservation(Reservation(1, 15, 25)) is False
        )  # Contained within existing

    def test_containing_reservation(self):
        system = RoomReservation()
        assert system.request_reservation(Reservation(1, 15, 25)) is True
        assert (
            system.request_reservation(Reservation(1, 10, 30)) is False
        )  # Contains existing

    def test_multiple_rooms(self):
        system = RoomReservation()
        # Same time slots for different rooms should work
        assert system.request_reservation(Reservation(1, 10, 20)) is True
        assert system.request_reservation(Reservation(2, 10, 20)) is True
        assert system.request_reservation(Reservation(3, 10, 20)) is True

        # But overlapping in same room should fail
        assert system.request_reservation(Reservation(1, 15, 25)) is False

    def test_non_overlapping_sequence(self):
        system = RoomReservation()
        assert system.request_reservation(Reservation(1, 10, 20)) is True
        assert system.request_reservation(Reservation(1, 30, 40)) is True
        assert system.request_reservation(Reservation(1, 50, 60)) is True
        assert system.request_reservation(Reservation(1, 0, 5)) is True

    def test_gap_filling(self):
        system = RoomReservation()
        assert system.request_reservation(Reservation(1, 10, 20)) is True
        assert system.request_reservation(Reservation(1, 30, 40)) is True
        assert (
            system.request_reservation(Reservation(1, 20, 30)) is True
        )  # Fill the gap

    def test_boundary_values(self):
        system = RoomReservation()
        assert system.request_reservation(Reservation(1, 0, 1)) is True
        assert system.request_reservation(Reservation(1, 999, 1000)) is True
        assert system.request_reservation(Reservation(100, 500, 501)) is True
