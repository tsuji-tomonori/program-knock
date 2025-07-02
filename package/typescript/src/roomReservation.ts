interface Reservation {
  roomId: number; // 会議室ID (1 <= roomId <= 100)
  startTime: number; // 予約開始時間 (0 <= startTime < endTime <= 1000)
  endTime: number; // 予約終了時間 (startTime < endTime <= 1000)
}

export class RoomReservation {
  private reservations: Map<number, Array<[number, number]>>;

  /**
   * 会議室予約システムの初期化
   */
  constructor() {
    // Map to store reservations for each room
    // Key: roomId, Value: Array of [startTime, endTime] tuples
    this.reservations = new Map();
  }

  /**
   * 会議室の予約をリクエストする。
   *
   * 予約が承認される場合は true を返し、拒否される場合は false を返す。
   *
   * @param reservation - 予約リクエストの情報
   * @returns 予約が受理された場合 true、拒否された場合 false
   */
  requestReservation(reservation: Reservation): boolean {
    const { roomId, startTime, endTime } = reservation;

    // Initialize room if not exists
    if (!this.reservations.has(roomId)) {
      this.reservations.set(roomId, []);
    }

    const roomReservations = this.reservations.get(roomId)!;

    // Check for overlapping reservations
    for (const [existingStart, existingEnd] of roomReservations) {
      // Check if the new reservation overlaps with existing ones
      // Overlap occurs if: newStart < existingEnd and newEnd > existingStart
      if (startTime < existingEnd && endTime > existingStart) {
        return false;
      }
    }

    // No overlap found, add the reservation
    roomReservations.push([startTime, endTime]);
    return true;
  }
}

export type { Reservation };
