package com.programknock;

import java.util.*;

public class RemoveDuplicateCustomers {

    public static List<Integer> removeDuplicateCustomers(List<Integer> customerIds) {
        Set<Integer> seen = new HashSet<>();
        List<Integer> uniqueIds = new ArrayList<>();

        for (Integer customerId : customerIds) {
            if (!seen.contains(customerId)) {
                seen.add(customerId);
                uniqueIds.add(customerId);
            }
        }

        return uniqueIds;
    }
}
