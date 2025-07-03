<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\MeetingRoomReservation;
use ProgramKnock\Reservation;

class MeetingRoomReservationTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected " . json_encode($expected) . ", but got " . json_encode($actual));
        }
    }

    private function assertCount($expected, $actual, $message = ''): void
    {
        $count = count($actual);
        if ($count !== $expected) {
            throw new \AssertionError($message ?: "Expected count $expected, but got $count");
        }
    }

    private function assertTrue($condition, $message = ''): void
    {
        if (!$condition) {
            throw new \AssertionError($message ?: "Expected true, but got false");
        }
    }

    private function assertFalse($condition, $message = ''): void
    {
        if ($condition) {
            throw new \AssertionError($message ?: "Expected false, but got true");
        }
    }

    public function testSampleCase1(): void
    {
        $requests = [
            [1, 10, 20],  // Room 1, 10-20: OK
            [1, 15, 25],  // Room 1, 15-25: NG (overlaps with 10-20)
            [1, 20, 30],  // Room 1, 20-30: OK (adjacent)
            [2, 10, 20],  // Room 2, 10-20: OK (different room)
            [2, 15, 25]   // Room 2, 15-25: NG (overlaps with 10-20)
        ];

        $result = MeetingRoomReservation::processReservations($requests);
        $expected = [true, false, true, true, false];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase2(): void
    {
        $requests = [
            [1, 0, 500],    // Room 1, 0-500: OK
            [1, 500, 1000], // Room 1, 500-1000: OK (adjacent)
            [1, 499, 501]   // Room 1, 499-501: NG (overlaps with both)
        ];

        $result = MeetingRoomReservation::processReservations($requests);
        $expected = [true, true, false];
        $this->assertEquals($expected, $result);
    }

    public function testSingleReservation(): void
    {
        $system = new MeetingRoomReservation();
        $result = $system->requestReservation(1, 10, 20);
        $this->assertTrue($result);

        $reservations = $system->getReservations(1);
        $this->assertCount(1, $reservations);
        $this->assertEquals(1, $reservations[0]->roomId);
        $this->assertEquals(10, $reservations[0]->startTime);
        $this->assertEquals(20, $reservations[0]->endTime);
    }

    public function testNoOverlap(): void
    {
        $system = new MeetingRoomReservation();

        $this->assertTrue($system->requestReservation(1, 10, 20));
        $this->assertTrue($system->requestReservation(1, 20, 30)); // Adjacent
        $this->assertTrue($system->requestReservation(1, 0, 10));  // Adjacent
        $this->assertTrue($system->requestReservation(1, 30, 40)); // No overlap

        $reservations = $system->getReservations(1);
        $this->assertCount(4, $reservations);
    }

    public function testOverlapDetection(): void
    {
        $system = new MeetingRoomReservation();

        $this->assertTrue($system->requestReservation(1, 10, 20));

        // Various overlapping patterns
        $this->assertFalse($system->requestReservation(1, 5, 15));   // Starts before, ends inside
        $this->assertFalse($system->requestReservation(1, 15, 25));  // Starts inside, ends after
        $this->assertFalse($system->requestReservation(1, 12, 18));  // Completely inside
        $this->assertFalse($system->requestReservation(1, 5, 25));   // Completely contains
        $this->assertFalse($system->requestReservation(1, 10, 20));  // Exact same time
    }

    public function testAdjacentReservations(): void
    {
        $system = new MeetingRoomReservation();

        $this->assertTrue($system->requestReservation(1, 10, 20));
        $this->assertTrue($system->requestReservation(1, 20, 30)); // End = Start is OK
        $this->assertTrue($system->requestReservation(1, 0, 10));  // End = Start is OK

        $reservations = $system->getReservations(1);
        $this->assertCount(3, $reservations);
    }

    public function testMultipleRooms(): void
    {
        $system = new MeetingRoomReservation();

        // Same time slots in different rooms should be OK
        $this->assertTrue($system->requestReservation(1, 10, 20));
        $this->assertTrue($system->requestReservation(2, 10, 20));
        $this->assertTrue($system->requestReservation(3, 10, 20));

        $this->assertCount(1, $system->getReservations(1));
        $this->assertCount(1, $system->getReservations(2));
        $this->assertCount(1, $system->getReservations(3));

        // Overlapping in same room should fail
        $this->assertFalse($system->requestReservation(1, 15, 25));
        $this->assertFalse($system->requestReservation(2, 15, 25));

        // But should still work in different room
        $this->assertTrue($system->requestReservation(4, 15, 25));
    }

    public function testEmptyRoom(): void
    {
        $system = new MeetingRoomReservation();

        // Room that has no reservations should return empty array
        $reservations = $system->getReservations(999);
        $this->assertCount(0, $reservations);
    }

    public function testBoundaryTimes(): void
    {
        $system = new MeetingRoomReservation();

        // Test edge cases with boundary times
        $this->assertTrue($system->requestReservation(1, 0, 1));
        $this->assertTrue($system->requestReservation(1, 1, 2));
        $this->assertTrue($system->requestReservation(1, 999, 1000));

        $reservations = $system->getReservations(1);
        $this->assertCount(3, $reservations);
    }

    public function testLargeTimeRange(): void
    {
        $system = new MeetingRoomReservation();

        $this->assertTrue($system->requestReservation(1, 0, 1000));

        // Any other reservation in the same room should fail
        $this->assertFalse($system->requestReservation(1, 0, 1));
        $this->assertFalse($system->requestReservation(1, 999, 1000));
        $this->assertFalse($system->requestReservation(1, 500, 600));

        // But should work in different room
        $this->assertTrue($system->requestReservation(2, 0, 1000));
    }

    public function testManyReservations(): void
    {
        $system = new MeetingRoomReservation();

        // Add many non-overlapping reservations
        for ($i = 0; $i < 100; $i++) {
            $start = $i * 10;
            $end = $start + 5;
            $this->assertTrue($system->requestReservation(1, $start, $end));
        }

        $reservations = $system->getReservations(1);
        $this->assertCount(100, $reservations);

        // Try to add overlapping reservation
        $this->assertFalse($system->requestReservation(1, 50, 55));
    }

    public function testComplexScenario(): void
    {
        $requests = [
            [1, 100, 200],  // OK
            [1, 200, 300],  // OK (adjacent)
            [1, 150, 250],  // NG (overlaps)
            [2, 150, 250],  // OK (different room)
            [1, 50, 100],   // OK (adjacent)
            [1, 300, 400],  // OK (no overlap)
            [1, 250, 350],  // NG (overlaps with 300-400)
            [3, 0, 1000],   // OK (different room)
            [1, 0, 50],     // OK (adjacent)
            [1, 400, 500]   // OK (no overlap)
        ];

        $result = MeetingRoomReservation::processReservations($requests);
        $expected = [true, true, false, true, true, true, false, true, true, true];
        $this->assertEquals($expected, $result);
    }

    public function testReservationClass(): void
    {
        $reservation = new Reservation(1, 10, 20);

        $this->assertEquals(1, $reservation->roomId);
        $this->assertEquals(10, $reservation->startTime);
        $this->assertEquals(20, $reservation->endTime);
    }

    public function testClearAll(): void
    {
        $system = new MeetingRoomReservation();

        $system->requestReservation(1, 10, 20);
        $system->requestReservation(2, 10, 20);

        $this->assertCount(1, $system->getReservations(1));
        $this->assertCount(1, $system->getReservations(2));

        $system->clearAll();

        $this->assertCount(0, $system->getReservations(1));
        $this->assertCount(0, $system->getReservations(2));
    }

    public function testPerformanceTest(): void
    {
        $requests = [];

        // Generate 1000 requests
        for ($i = 0; $i < 1000; $i++) {
            $roomId = ($i % 10) + 1; // 10 rooms
            $startTime = $i;
            $endTime = $i + 1;
            $requests[] = [$roomId, $startTime, $endTime];
        }

        $startTime = microtime(true);
        $results = MeetingRoomReservation::processReservations($requests);
        $endTime = microtime(true);

        // Performance check
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        $this->assertCount(1000, $results);

        // Verify results make sense
        $trueCount = array_sum($results);
        $this->assertEquals(1000, $trueCount); // All should be accepted (no overlaps)
    }

    public function testOverlapPerformance(): void
    {
        $requests = [];

        // Generate overlapping requests for same room
        for ($i = 0; $i < 500; $i++) {
            $requests[] = [1, 0, 100]; // All overlap with each other
        }

        $startTime = microtime(true);
        $results = MeetingRoomReservation::processReservations($requests);
        $endTime = microtime(true);

        // Performance check
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        // Only first one should be accepted
        $this->assertTrue($results[0]);
        for ($i = 1; $i < 500; $i++) {
            $this->assertFalse($results[$i]);
        }
    }

    public function testEdgeCaseZeroTime(): void
    {
        $system = new MeetingRoomReservation();

        // Single point in time (start == end-1)
        $this->assertTrue($system->requestReservation(1, 0, 1));
        $this->assertTrue($system->requestReservation(1, 1, 2));
        $this->assertFalse($system->requestReservation(1, 0, 2)); // Overlaps both
    }

    public function testMaxTimeRange(): void
    {
        $system = new MeetingRoomReservation();

        // Test with maximum time values
        $this->assertTrue($system->requestReservation(1, 0, 1000));
        $this->assertFalse($system->requestReservation(1, 500, 600));

        // Different room should work
        $this->assertTrue($system->requestReservation(2, 0, 1000));
    }

    public function testRandomPattern(): void
    {
        $system = new MeetingRoomReservation();

        // Add some random non-overlapping reservations
        $times = [
            [10, 20], [25, 35], [40, 50], [55, 65], [70, 80],
            [85, 95], [100, 110], [115, 125], [130, 140]
        ];

        foreach ($times as [$start, $end]) {
            $this->assertTrue($system->requestReservation(1, $start, $end));
        }

        // Try to add overlapping ones
        $this->assertFalse($system->requestReservation(1, 15, 30)); // Overlaps multiple
        $this->assertFalse($system->requestReservation(1, 60, 75)); // Overlaps 55-65 and 70-80

        // Try to add non-overlapping ones
        $this->assertTrue($system->requestReservation(1, 20, 25)); // Between 10-20 and 25-35
        $this->assertTrue($system->requestReservation(1, 140, 150)); // After all
    }
}
