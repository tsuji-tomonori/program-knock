package com.programknock;

import java.util.*;

public class RoomReservation {

    public static class Reservation {
        public final int roomId;
        public final int startTime;
        public final int endTime;

        public Reservation(int roomId, int startTime, int endTime) {
            this.roomId = roomId;
            this.startTime = startTime;
            this.endTime = endTime;
        }

        @Override
        public boolean equals(Object obj) {
            if (this == obj) return true;
            if (obj == null || getClass() != obj.getClass()) return false;
            Reservation that = (Reservation) obj;
            return roomId == that.roomId &&
                   startTime == that.startTime &&
                   endTime == that.endTime;
        }

        @Override
        public int hashCode() {
            return Objects.hash(roomId, startTime, endTime);
        }

        @Override
        public String toString() {
            return String.format("Reservation{roomId=%d, startTime=%d, endTime=%d}",
                               roomId, startTime, endTime);
        }
    }

    public static class TimeSlot {
        public final int startTime;
        public final int endTime;

        public TimeSlot(int startTime, int endTime) {
            this.startTime = startTime;
            this.endTime = endTime;
        }

        @Override
        public boolean equals(Object obj) {
            if (this == obj) return true;
            if (obj == null || getClass() != obj.getClass()) return false;
            TimeSlot timeSlot = (TimeSlot) obj;
            return startTime == timeSlot.startTime && endTime == timeSlot.endTime;
        }

        @Override
        public int hashCode() {
            return Objects.hash(startTime, endTime);
        }

        @Override
        public String toString() {
            return String.format("TimeSlot{startTime=%d, endTime=%d}", startTime, endTime);
        }
    }

    private Map<Integer, List<TimeSlot>> reservations;

    public RoomReservation() {
        this.reservations = new HashMap<>();
    }

    public boolean requestReservation(Reservation reservation) {
        int roomId = reservation.roomId;
        int startTime = reservation.startTime;
        int endTime = reservation.endTime;

        // Initialize room if not exists
        if (!reservations.containsKey(roomId)) {
            reservations.put(roomId, new ArrayList<>());
        }

        // Check for overlapping reservations
        for (TimeSlot existing : reservations.get(roomId)) {
            // Check if the new reservation overlaps with existing ones
            // Overlap occurs if: new_start < existing_end and new_end > existing_start
            if (startTime < existing.endTime && endTime > existing.startTime) {
                return false;
            }
        }

        // No overlap found, add the reservation
        reservations.get(roomId).add(new TimeSlot(startTime, endTime));
        return true;
    }
}
