package com.programknock;

import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class RoomReservationTest {

    @Test
    void testBasic() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 10, 20))); // 予約成功
        assertFalse(system.requestReservation(new RoomReservation.Reservation(1, 15, 25))); // 時間が重複しているため拒否
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 20, 30))); // 予約成功
        assertTrue(system.requestReservation(new RoomReservation.Reservation(2, 10, 20))); // 別の会議室なので予約成功
        assertFalse(system.requestReservation(new RoomReservation.Reservation(2, 15, 25))); // 会議室2の予約が重複
    }

    @Test
    void testEdgeCases() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 0, 500))); // 長時間予約成功
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 500, 1000))); // 予約時間が連続するが重ならない
        assertFalse(system.requestReservation(new RoomReservation.Reservation(1, 499, 501))); // 予約時間が重なるため拒否
    }

    @Test
    void testEmptySystem() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 10, 20)));
    }

    @Test
    void testAdjacentReservations() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 10, 20)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 20, 30))); // Adjacent, no overlap
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 0, 10))); // Adjacent, no overlap
    }

    @Test
    void testExactOverlap() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 10, 20)));
        assertFalse(system.requestReservation(new RoomReservation.Reservation(1, 10, 20))); // Exact same time
    }

    @Test
    void testPartialOverlapStart() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 10, 20)));
        assertFalse(system.requestReservation(new RoomReservation.Reservation(1, 5, 15))); // Overlaps at start
    }

    @Test
    void testPartialOverlapEnd() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 10, 20)));
        assertFalse(system.requestReservation(new RoomReservation.Reservation(1, 15, 25))); // Overlaps at end
    }

    @Test
    void testContainedReservation() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 10, 30)));
        assertFalse(system.requestReservation(new RoomReservation.Reservation(1, 15, 25))); // Contained within existing
    }

    @Test
    void testContainingReservation() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 15, 25)));
        assertFalse(system.requestReservation(new RoomReservation.Reservation(1, 10, 30))); // Contains existing
    }

    @Test
    void testMultipleRooms() {
        RoomReservation system = new RoomReservation();
        // Same time slots for different rooms should work
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 10, 20)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(2, 10, 20)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(3, 10, 20)));

        // But overlapping in same room should fail
        assertFalse(system.requestReservation(new RoomReservation.Reservation(1, 15, 25)));
    }

    @Test
    void testNonOverlappingSequence() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 10, 20)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 30, 40)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 50, 60)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 0, 5)));
    }

    @Test
    void testGapFilling() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 10, 20)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 30, 40)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 20, 30))); // Fill the gap
    }

    @Test
    void testBoundaryValues() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 0, 1)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 999, 1000)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(100, 500, 501)));
    }

    @Test
    void testTimeSlotEquality() {
        RoomReservation.TimeSlot slot1 = new RoomReservation.TimeSlot(10, 20);
        RoomReservation.TimeSlot slot2 = new RoomReservation.TimeSlot(10, 20);
        RoomReservation.TimeSlot slot3 = new RoomReservation.TimeSlot(11, 21);

        assertEquals(slot1, slot2);
        assertNotEquals(slot1, slot3);
        assertEquals(slot1.hashCode(), slot2.hashCode());
    }

    @Test
    void testReservationEquality() {
        RoomReservation.Reservation res1 = new RoomReservation.Reservation(1, 10, 20);
        RoomReservation.Reservation res2 = new RoomReservation.Reservation(1, 10, 20);
        RoomReservation.Reservation res3 = new RoomReservation.Reservation(2, 10, 20);

        assertEquals(res1, res2);
        assertNotEquals(res1, res3);
        assertEquals(res1.hashCode(), res2.hashCode());
    }

    @Test
    void testLargeRoomIds() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(100, 10, 20)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(99, 10, 20)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 10, 20)));
    }

    @Test
    void testManyReservationsInSameRoom() {
        RoomReservation system = new RoomReservation();
        // Create many non-overlapping reservations
        for (int i = 0; i < 50; i++) {
            int start = i * 10;
            int end = start + 5;
            assertTrue(system.requestReservation(new RoomReservation.Reservation(1, start, end)));
        }

        // Try to add overlapping reservation
        assertFalse(system.requestReservation(new RoomReservation.Reservation(1, 23, 27)));
    }

    @Test
    void testSingleTimeUnitReservations() {
        RoomReservation system = new RoomReservation();
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 0, 1)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 1, 2)));
        assertTrue(system.requestReservation(new RoomReservation.Reservation(1, 2, 3)));
        assertFalse(system.requestReservation(new RoomReservation.Reservation(1, 0, 2))); // Overlaps with first two
    }

    @Test
    void testPerformanceWithManyRooms() {
        RoomReservation system = new RoomReservation();
        // Test with many different rooms
        for (int roomId = 1; roomId <= 100; roomId++) {
            for (int i = 0; i < 5; i++) {
                int start = i * 10;
                int end = start + 5;
                assertTrue(system.requestReservation(new RoomReservation.Reservation(roomId, start, end)));
            }
        }
    }
}
