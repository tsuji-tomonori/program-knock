package com.programknock;

import java.util.*;

public class CountConnections {

    public static class Log {
        public final int time;
        public final int nConnect;
        public final int nDisconnect;

        public Log(int time, int nConnect, int nDisconnect) {
            this.time = time;
            this.nConnect = nConnect;
            this.nDisconnect = nDisconnect;
        }

        @Override
        public boolean equals(Object obj) {
            if (this == obj) return true;
            if (obj == null || getClass() != obj.getClass()) return false;
            Log log = (Log) obj;
            return time == log.time && nConnect == log.nConnect && nDisconnect == log.nDisconnect;
        }

        @Override
        public int hashCode() {
            return Objects.hash(time, nConnect, nDisconnect);
        }

        @Override
        public String toString() {
            return "Log{time=" + time + ", nConnect=" + nConnect + ", nDisconnect=" + nDisconnect + "}";
        }
    }

    public static class Param {
        public final int endTime;
        public final int period;
        public final List<Log> logs;

        public Param(int endTime, int period, List<Log> logs) {
            this.endTime = endTime;
            this.period = period;
            this.logs = logs;
        }

        @Override
        public boolean equals(Object obj) {
            if (this == obj) return true;
            if (obj == null || getClass() != obj.getClass()) return false;
            Param param = (Param) obj;
            return endTime == param.endTime && period == param.period &&
                   Objects.equals(logs, param.logs);
        }

        @Override
        public int hashCode() {
            return Objects.hash(endTime, period, logs);
        }

        @Override
        public String toString() {
            return "Param{endTime=" + endTime + ", period=" + period + ", logs=" + logs + "}";
        }
    }

    public static List<Integer> countConnections(Param param) {
        int[] connect = new int[param.endTime + 1];
        int[] disconnect = new int[param.endTime + 1];

        for (Log log : param.logs) {
            connect[log.time] = log.nConnect;
            disconnect[log.time] = log.nDisconnect;
        }

        List<Integer> result = new ArrayList<>();
        for (int t = 0; t <= param.endTime; t += param.period) {
            int totalConnect = 0;
            int totalDisconnect = 0;

            for (int i = 0; i <= t; i++) {
                totalConnect += connect[i];
                totalDisconnect += disconnect[i];
            }

            result.add(totalConnect - totalDisconnect);
        }

        return result;
    }
}
