package com.programknock;

import java.util.List;

public class AgeStatistics {

    public static class AgeAnalysisResult {
        public final int maxAge;
        public final int minAge;
        public final double avgAge;
        public final int countBelowAvg;

        public AgeAnalysisResult(int maxAge, int minAge, double avgAge, int countBelowAvg) {
            this.maxAge = maxAge;
            this.minAge = minAge;
            this.avgAge = avgAge;
            this.countBelowAvg = countBelowAvg;
        }

        @Override
        public boolean equals(Object obj) {
            if (this == obj) return true;
            if (obj == null || getClass() != obj.getClass()) return false;
            AgeAnalysisResult that = (AgeAnalysisResult) obj;
            return maxAge == that.maxAge &&
                   minAge == that.minAge &&
                   Double.compare(that.avgAge, avgAge) == 0 &&
                   countBelowAvg == that.countBelowAvg;
        }
    }

    public static AgeAnalysisResult analyzeAgeDistribution(List<Integer> ages) {
        if (ages.isEmpty()) {
            throw new IllegalArgumentException("Ages list cannot be empty");
        }

        int maxAge = ages.stream().mapToInt(Integer::intValue).max().orElse(0);
        int minAge = ages.stream().mapToInt(Integer::intValue).min().orElse(0);

        double sum = ages.stream().mapToInt(Integer::intValue).sum();
        double avgAge = Math.round((sum / ages.size()) * 100.0) / 100.0;

        int countBelowAvg = (int) ages.stream()
            .mapToInt(Integer::intValue)
            .filter(age -> age <= avgAge)
            .count();

        return new AgeAnalysisResult(maxAge, minAge, avgAge, countBelowAvg);
    }
}
