import { RoomReservation } from '../src/roomReservation';

describe('RoomReservation', () => {
  test('non-overlapping reservations', () => {
    const system = new RoomReservation();

    expect(system.requestReservation({ roomId: 1, startTime: 10, endTime: 20 })).toBe(true);
    expect(system.requestReservation({ roomId: 1, startTime: 25, endTime: 30 })).toBe(true);
    expect(system.requestReservation({ roomId: 1, startTime: 5, endTime: 9 })).toBe(true);
  });

  test('overlapping reservations', () => {
    const system = new RoomReservation();

    expect(system.requestReservation({ roomId: 1, startTime: 10, endTime: 20 })).toBe(true);
    expect(system.requestReservation({ roomId: 1, startTime: 15, endTime: 25 })).toBe(false);
    expect(system.requestReservation({ roomId: 1, startTime: 5, endTime: 15 })).toBe(false);
    expect(system.requestReservation({ roomId: 1, startTime: 12, endTime: 18 })).toBe(false);
  });

  test('adjacent reservations', () => {
    const system = new RoomReservation();

    expect(system.requestReservation({ roomId: 1, startTime: 10, endTime: 20 })).toBe(true);
    expect(system.requestReservation({ roomId: 1, startTime: 20, endTime: 30 })).toBe(true);
    expect(system.requestReservation({ roomId: 1, startTime: 5, endTime: 10 })).toBe(true);
  });

  test('different rooms', () => {
    const system = new RoomReservation();

    expect(system.requestReservation({ roomId: 1, startTime: 10, endTime: 20 })).toBe(true);
    expect(system.requestReservation({ roomId: 2, startTime: 15, endTime: 25 })).toBe(true);
  });
});
