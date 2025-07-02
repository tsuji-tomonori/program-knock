package com.programknock;

import java.util.*;
import java.util.stream.Collectors;

public class CountWordFrequencies {

    public static Map<String, Integer> countWordFrequencies(String text) {
        if (text == null || text.trim().isEmpty()) {
            return new LinkedHashMap<>();
        }

        String[] words = text.split("\\s+");
        Map<String, Integer> frequencyMap = new HashMap<>();

        for (String word : words) {
            if (!word.isEmpty()) {
                frequencyMap.put(word, frequencyMap.getOrDefault(word, 0) + 1);
            }
        }

        return frequencyMap.entrySet().stream()
            .sorted((e1, e2) -> {
                int freqCompare = Integer.compare(e2.getValue(), e1.getValue());
                if (freqCompare != 0) {
                    return freqCompare;
                }
                return e1.getKey().compareTo(e2.getKey());
            })
            .collect(Collectors.toMap(
                Map.Entry::getKey,
                Map.Entry::getValue,
                (e1, e2) -> e1,
                LinkedHashMap::new
            ));
    }
}
