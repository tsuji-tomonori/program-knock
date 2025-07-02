package com.programknock;

import java.util.List;

public class FindClosestValue {

    public static int findClosestValue(List<Integer> arr, int target) {
        if (arr.size() == 1) {
            return arr.get(0);
        }

        int left = 0;
        int right = arr.size() - 1;

        while (left < right) {
            int mid = (left + right) / 2;

            if (arr.get(mid) == target) {
                return arr.get(mid);
            } else if (arr.get(mid) < target) {
                left = mid + 1;
            } else {
                right = mid;
            }
        }

        int candidate = arr.get(left);

        if (left > 0) {
            int prevVal = arr.get(left - 1);
            int distCandidate = Math.abs(candidate - target);
            int distPrevVal = Math.abs(prevVal - target);

            if (distPrevVal < distCandidate ||
                (distPrevVal == distCandidate && prevVal < candidate)) {
                candidate = prevVal;
            }
        }

        return candidate;
    }
}
