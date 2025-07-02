package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.*;

import static org.junit.jupiter.api.Assertions.*;

class CountConnectionsTest {

    @Test
    void testBasic() {
        CountConnections.Param param = new CountConnections.Param(
            5, 1,
            Arrays.asList(
                new CountConnections.Log(0, 3, 0),
                new CountConnections.Log(1, 2, 0),
                new CountConnections.Log(4, 5, 2),
                new CountConnections.Log(5, 3, 5)
            )
        );
        assertEquals(Arrays.asList(3, 5, 5, 5, 8, 6), CountConnections.countConnections(param));
    }

    @Test
    void testNoLogs() {
        CountConnections.Param param = new CountConnections.Param(3, 1, Arrays.asList());
        assertEquals(Arrays.asList(0, 0, 0, 0), CountConnections.countConnections(param));
    }

    @Test
    void testGapLogs() {
        CountConnections.Param param = new CountConnections.Param(
            6, 2,
            Arrays.asList(
                new CountConnections.Log(1, 4, 0),
                new CountConnections.Log(3, 1, 1),
                new CountConnections.Log(6, 3, 2)
            )
        );
        assertEquals(Arrays.asList(0, 4, 4, 5), CountConnections.countConnections(param));
    }

    @Test
    void testSingleLog() {
        CountConnections.Param param = new CountConnections.Param(
            5, 1,
            Arrays.asList(
                new CountConnections.Log(0, 5, 0)
            )
        );
        assertEquals(Arrays.asList(5, 5, 5, 5, 5, 5), CountConnections.countConnections(param));
    }

    @Test
    void testAllDisconnect() {
        CountConnections.Param param = new CountConnections.Param(
            4, 1,
            Arrays.asList(
                new CountConnections.Log(0, 10, 0),
                new CountConnections.Log(2, 0, 10)
            )
        );
        assertEquals(Arrays.asList(10, 10, 0, 0, 0), CountConnections.countConnections(param));
    }

    @Test
    void testPartialDisconnect() {
        CountConnections.Param param = new CountConnections.Param(
            4, 1,
            Arrays.asList(
                new CountConnections.Log(0, 10, 0),
                new CountConnections.Log(2, 0, 5)
            )
        );
        assertEquals(Arrays.asList(10, 10, 5, 5, 5), CountConnections.countConnections(param));
    }

    @Test
    void testLargeConnectAndDisconnect() {
        CountConnections.Param param = new CountConnections.Param(
            100, 10,
            Arrays.asList(
                new CountConnections.Log(10, 1000000000, 0),
                new CountConnections.Log(40, 0, 1000000000)
            )
        );
        assertEquals(Arrays.asList(0, 1000000000, 1000000000, 1000000000, 0, 0, 0, 0, 0, 0, 0),
                    CountConnections.countConnections(param));
    }

    @Test
    void testBoundaryPeriodEqualsEndTime() {
        CountConnections.Param param = new CountConnections.Param(
            10, 10,
            Arrays.asList(
                new CountConnections.Log(0, 10, 0)
            )
        );
        assertEquals(Arrays.asList(10, 10), CountConnections.countConnections(param));
    }

    @Test
    void testOverlappingConnectionsAndDisconnections() {
        CountConnections.Param param = new CountConnections.Param(
            5, 1,
            Arrays.asList(
                new CountConnections.Log(0, 5, 0),
                new CountConnections.Log(2, 3, 2),
                new CountConnections.Log(4, 0, 6)
            )
        );
        assertEquals(Arrays.asList(5, 5, 6, 6, 0, 0), CountConnections.countConnections(param));
    }

    @Test
    void testSingleTimePoint() {
        CountConnections.Param param = new CountConnections.Param(
            0, 1,
            Arrays.asList(
                new CountConnections.Log(0, 1, 0)
            )
        );
        assertEquals(Arrays.asList(1), CountConnections.countConnections(param));
    }

    @Test
    void testLargePeriod() {
        CountConnections.Param param = new CountConnections.Param(
            20, 5,
            Arrays.asList(
                new CountConnections.Log(3, 10, 0),
                new CountConnections.Log(7, 5, 2),
                new CountConnections.Log(15, 8, 3)
            )
        );
        assertEquals(Arrays.asList(0, 10, 13, 18, 18), CountConnections.countConnections(param));
    }

    @Test
    void testMultipleConnectionsAtSameTime() {
        CountConnections.Param param = new CountConnections.Param(
            3, 1,
            Arrays.asList(
                new CountConnections.Log(1, 100, 50)
            )
        );
        assertEquals(Arrays.asList(0, 50, 50, 50), CountConnections.countConnections(param));
    }

    @Test
    void testZeroConnections() {
        CountConnections.Param param = new CountConnections.Param(
            4, 2,
            Arrays.asList(
                new CountConnections.Log(0, 0, 0),
                new CountConnections.Log(2, 0, 0),
                new CountConnections.Log(4, 0, 0)
            )
        );
        assertEquals(Arrays.asList(0, 0, 0), CountConnections.countConnections(param));
    }

    @Test
    void testOutput1000Entries() {
        List<CountConnections.Log> logs = new ArrayList<>();
        for (int i = 0; i <= 1000; i++) {
            logs.add(new CountConnections.Log(i, 1, 0));
        }
        CountConnections.Param param = new CountConnections.Param(1000, 1, logs);

        List<Integer> expected = new ArrayList<>();
        for (int i = 1; i <= 1001; i++) {
            expected.add(i);
        }

        assertEquals(expected, CountConnections.countConnections(param));
    }

    @Test
    void testLargeDisconnectionsFirstThenConnections() {
        CountConnections.Param param = new CountConnections.Param(
            6, 2,
            Arrays.asList(
                new CountConnections.Log(0, 20, 0),
                new CountConnections.Log(2, 0, 15),
                new CountConnections.Log(4, 10, 0),
                new CountConnections.Log(6, 0, 5)
            )
        );
        assertEquals(Arrays.asList(20, 5, 15, 10), CountConnections.countConnections(param));
    }
}
