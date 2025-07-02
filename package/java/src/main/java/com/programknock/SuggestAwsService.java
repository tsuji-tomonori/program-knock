package com.programknock;

import java.util.*;

public class SuggestAwsService {

    private static final List<String> AWS_SERVICES = Arrays.asList(
        "ec2", "s3", "lambda", "dynamodb", "rds", "cloudfront", "iam", "route53"
    );

    // Memoization cache for levenshtein distance calculations
    private static final Map<String, Integer> memo = new HashMap<>();

    private static String getMemoKey(String s, String t) {
        return s + "|" + t;
    }

    public static int levenshteinDistance(String s, String t) {
        String key = getMemoKey(s, t);
        if (memo.containsKey(key)) {
            return memo.get(key);
        }

        int result = computeLevenshteinDistance(s, t);
        memo.put(key, result);
        return result;
    }

    private static int computeLevenshteinDistance(String s, String t) {
        // Base case: if either string is empty, the distance is the length of the other
        if (s.isEmpty()) {
            return t.length();
        }
        if (t.isEmpty()) {
            return s.length();
        }

        // If the last characters are the same, recurse without them
        if (s.charAt(s.length() - 1) == t.charAt(t.length() - 1)) {
            return levenshteinDistance(s.substring(0, s.length() - 1), t.substring(0, t.length() - 1));
        } else {
            // If the last characters are different, try deletion, insertion, and substitution
            int deletion = levenshteinDistance(s.substring(0, s.length() - 1), t);
            int insertion = levenshteinDistance(s, t.substring(0, t.length() - 1));
            int substitution = levenshteinDistance(s.substring(0, s.length() - 1), t.substring(0, t.length() - 1));

            return 1 + Math.min(Math.min(deletion, insertion), substitution);
        }
    }

    public static String suggestAwsService(String wrongService) {
        // Initialize with the first service and infinite distance
        int minDistance = Integer.MAX_VALUE;
        String suggestion = AWS_SERVICES.get(0);

        // Find the service with minimum Levenshtein distance
        for (String service : AWS_SERVICES) {
            int distance = levenshteinDistance(wrongService, service);
            if (distance < minDistance) {
                minDistance = distance;
                suggestion = service;
            }
        }

        return suggestion;
    }

    // Clear memoization cache for testing
    public static void clearCache() {
        memo.clear();
    }
}
