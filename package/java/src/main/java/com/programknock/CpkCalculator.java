package com.programknock;

import java.util.List;

public class CpkCalculator {

    public static class CpkInput {
        public final double usl;
        public final double lsl;
        public final List<Double> data;

        public CpkInput(double usl, double lsl, List<Double> data) {
            this.usl = usl;
            this.lsl = lsl;
            this.data = data;
        }
    }

    public static double calcCpk(double usl, double lsl, List<Double> data) {
        if (data.size() < 2) {
            throw new ArithmeticException("Need at least 2 data points to calculate standard deviation");
        }

        double mean = data.stream().mapToDouble(Double::doubleValue).average().orElse(0.0);

        double variance = data.stream()
            .mapToDouble(x -> Math.pow(x - mean, 2))
            .sum() / (data.size() - 1);
        double stdDev = Math.sqrt(variance);

        double m = (usl + lsl) / 2.0;
        double r = usl - lsl;

        double k = Math.abs(mean - m) / (r / 2.0);

        double cp = (usl - lsl) / (6 * stdDev);

        double cpk = (1 - k) * cp;

        return Math.round(cpk * 1000.0) / 1000.0;
    }
}
