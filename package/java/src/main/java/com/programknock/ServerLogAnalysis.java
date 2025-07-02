package com.programknock;

import java.util.*;
import java.util.stream.Collectors;

public class ServerLogAnalysis {

    public static class LogEntry {
        public final String ipAddress;
        public final String request;
        public final int statusCode;

        public LogEntry(String ipAddress, String request, int statusCode) {
            this.ipAddress = ipAddress;
            this.request = request;
            this.statusCode = statusCode;
        }

        @Override
        public boolean equals(Object obj) {
            if (this == obj) return true;
            if (obj == null || getClass() != obj.getClass()) return false;
            LogEntry logEntry = (LogEntry) obj;
            return statusCode == logEntry.statusCode &&
                   Objects.equals(ipAddress, logEntry.ipAddress) &&
                   Objects.equals(request, logEntry.request);
        }

        @Override
        public int hashCode() {
            return Objects.hash(ipAddress, request, statusCode);
        }
    }

    public static List<LogEntry> filterSuccessfulRequests(List<LogEntry> logs) {
        return logs.stream()
            .filter(log -> log.statusCode == 200)
            .collect(Collectors.toList());
    }

    public static Map<String, Integer> countRequestsByIp(List<LogEntry> logs) {
        Map<String, Integer> ipCounts = new HashMap<>();
        for (LogEntry log : logs) {
            ipCounts.merge(log.ipAddress, 1, Integer::sum);
        }
        return ipCounts;
    }
}
