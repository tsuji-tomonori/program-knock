from typing import NamedTuple, List, Dict


class Reservation(NamedTuple):
    room_id: int  # 会議室ID (1 <= room_id <= 100)
    start_time: int  # 予約開始時間 (0 <= start_time < end_time <= 1000)
    end_time: int  # 予約終了時間 (start_time < end_time <= 1000)


class RoomReservation:
    def __init__(self):
        """会議室予約システムの初期化"""
        # Dict to store reservations for each room
        # Key: room_id, Value: List of (start_time, end_time) tuples
        self.reservations: Dict[int, List[tuple[int, int]]] = {}

    def request_reservation(self, reservation: Reservation) -> bool:
        """
        会議室の予約をリクエストする。

        予約が承認される場合は True を返し、拒否される場合は False を返す。

        Args:
            reservation (Reservation): 予約リクエストの情報。

        Returns:
            bool: 予約が受理された場合 True、拒否された場合 False。
        """
        room_id = reservation.room_id
        start_time = reservation.start_time
        end_time = reservation.end_time

        # Initialize room if not exists
        if room_id not in self.reservations:
            self.reservations[room_id] = []

        # Check for overlapping reservations
        for existing_start, existing_end in self.reservations[room_id]:
            # Check if the new reservation overlaps with existing ones
            # Overlap occurs if: new_start < existing_end and new_end > existing_start
            if start_time < existing_end and end_time > existing_start:
                return False

        # No overlap found, add the reservation
        self.reservations[room_id].append((start_time, end_time))
        return True
