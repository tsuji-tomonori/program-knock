package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.*;

import static org.junit.jupiter.api.Assertions.*;

class ServerLogAnalysisTest {

    @Test
    void testFilterSuccessfulRequestsSample() {
        List<ServerLogAnalysis.LogEntry> logs = Arrays.asList(
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /index.html", 200),
            new ServerLogAnalysis.LogEntry("192.168.1.2", "POST /login", 404),
            new ServerLogAnalysis.LogEntry("192.168.1.3", "GET /about.html", 200),
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /contact.html", 500)
        );

        List<ServerLogAnalysis.LogEntry> expected = Arrays.asList(
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /index.html", 200),
            new ServerLogAnalysis.LogEntry("192.168.1.3", "GET /about.html", 200)
        );

        assertEquals(expected, ServerLogAnalysis.filterSuccessfulRequests(logs));
    }

    @Test
    void testCountRequestsByIpSample() {
        List<ServerLogAnalysis.LogEntry> logs = Arrays.asList(
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /index.html", 200),
            new ServerLogAnalysis.LogEntry("192.168.1.2", "POST /login", 404),
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /about.html", 200),
            new ServerLogAnalysis.LogEntry("192.168.1.3", "GET /contact.html", 500),
            new ServerLogAnalysis.LogEntry("192.168.1.1", "POST /submit", 200)
        );

        Map<String, Integer> expected = Map.of(
            "192.168.1.1", 3,
            "192.168.1.2", 1,
            "192.168.1.3", 1
        );

        assertEquals(expected, ServerLogAnalysis.countRequestsByIp(logs));
    }

    @Test
    void testFilterEmptyList() {
        assertEquals(new ArrayList<>(), ServerLogAnalysis.filterSuccessfulRequests(new ArrayList<>()));
    }

    @Test
    void testCountEmptyList() {
        assertEquals(new HashMap<>(), ServerLogAnalysis.countRequestsByIp(new ArrayList<>()));
    }

    @Test
    void testFilterNoSuccessfulRequests() {
        List<ServerLogAnalysis.LogEntry> logs = Arrays.asList(
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /index.html", 404),
            new ServerLogAnalysis.LogEntry("192.168.1.2", "POST /login", 500),
            new ServerLogAnalysis.LogEntry("192.168.1.3", "GET /about.html", 403)
        );

        assertEquals(new ArrayList<>(), ServerLogAnalysis.filterSuccessfulRequests(logs));
    }

    @Test
    void testFilterAllSuccessfulRequests() {
        List<ServerLogAnalysis.LogEntry> logs = Arrays.asList(
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /index.html", 200),
            new ServerLogAnalysis.LogEntry("192.168.1.2", "POST /login", 200),
            new ServerLogAnalysis.LogEntry("192.168.1.3", "GET /about.html", 200)
        );

        assertEquals(logs, ServerLogAnalysis.filterSuccessfulRequests(logs));
    }

    @Test
    void testCountSingleIp() {
        List<ServerLogAnalysis.LogEntry> logs = Arrays.asList(
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /index.html", 200),
            new ServerLogAnalysis.LogEntry("192.168.1.1", "POST /login", 404),
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /about.html", 200)
        );

        Map<String, Integer> expected = Map.of("192.168.1.1", 3);
        assertEquals(expected, ServerLogAnalysis.countRequestsByIp(logs));
    }

    @Test
    void testCountMultipleIps() {
        List<ServerLogAnalysis.LogEntry> logs = Arrays.asList(
            new ServerLogAnalysis.LogEntry("10.0.0.1", "GET /", 200),
            new ServerLogAnalysis.LogEntry("10.0.0.2", "GET /", 200),
            new ServerLogAnalysis.LogEntry("10.0.0.3", "GET /", 200),
            new ServerLogAnalysis.LogEntry("10.0.0.1", "POST /", 200),
            new ServerLogAnalysis.LogEntry("10.0.0.2", "PUT /", 200)
        );

        Map<String, Integer> expected = Map.of(
            "10.0.0.1", 2,
            "10.0.0.2", 2,
            "10.0.0.3", 1
        );

        assertEquals(expected, ServerLogAnalysis.countRequestsByIp(logs));
    }

    @Test
    void testMixedStatusCodes() {
        List<ServerLogAnalysis.LogEntry> logs = Arrays.asList(
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /index.html", 200),
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /page1.html", 201),
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /page2.html", 200),
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /page3.html", 404),
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /page4.html", 500)
        );

        List<ServerLogAnalysis.LogEntry> expectedFiltered = Arrays.asList(
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /index.html", 200),
            new ServerLogAnalysis.LogEntry("192.168.1.1", "GET /page2.html", 200)
        );

        assertEquals(expectedFiltered, ServerLogAnalysis.filterSuccessfulRequests(logs));

        Map<String, Integer> expectedCount = Map.of("192.168.1.1", 5);
        assertEquals(expectedCount, ServerLogAnalysis.countRequestsByIp(logs));
    }

    @Test
    void testLargeVolume() {
        List<ServerLogAnalysis.LogEntry> logs = new ArrayList<>();
        for (int i = 0; i < 1000; i++) {
            String ip = "192.168.1." + (i % 10);
            int status = (i % 3 == 0) ? 200 : 404;
            logs.add(new ServerLogAnalysis.LogEntry(ip, "GET /page" + i, status));
        }

        List<ServerLogAnalysis.LogEntry> filtered = ServerLogAnalysis.filterSuccessfulRequests(logs);
        assertTrue(filtered.size() > 0);

        for (ServerLogAnalysis.LogEntry entry : filtered) {
            assertEquals(200, entry.statusCode);
        }

        Map<String, Integer> counts = ServerLogAnalysis.countRequestsByIp(logs);
        assertEquals(10, counts.size());

        int totalCounts = counts.values().stream().mapToInt(Integer::intValue).sum();
        assertEquals(1000, totalCounts);
    }
}
