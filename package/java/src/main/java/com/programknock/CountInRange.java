package com.programknock;

import java.util.*;

public class CountInRange {

    public static int countInRange(List<Integer> arr, int left, int right) {
        int leftIndex = binarySearchLeft(arr, left);
        int rightIndex = binarySearchRight(arr, right);
        return rightIndex - leftIndex;
    }

    private static int binarySearchLeft(List<Integer> arr, int target) {
        int left = 0;
        int right = arr.size();

        while (left < right) {
            int mid = left + (right - left) / 2;
            if (arr.get(mid) < target) {
                left = mid + 1;
            } else {
                right = mid;
            }
        }

        return left;
    }

    private static int binarySearchRight(List<Integer> arr, int target) {
        int left = 0;
        int right = arr.size();

        while (left < right) {
            int mid = left + (right - left) / 2;
            if (arr.get(mid) <= target) {
                left = mid + 1;
            } else {
                right = mid;
            }
        }

        return left;
    }
}
