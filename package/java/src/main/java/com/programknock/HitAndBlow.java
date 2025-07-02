package com.programknock;

import java.util.*;

public class HitAndBlow {
    
    public static class Result {
        public final int hits;
        public final int blows;
        
        public Result(int hits, int blows) {
            this.hits = hits;
            this.blows = blows;
        }
        
        @Override
        public boolean equals(Object obj) {
            if (this == obj) return true;
            if (obj == null || getClass() != obj.getClass()) return false;
            Result result = (Result) obj;
            return hits == result.hits && blows == result.blows;
        }
        
        @Override
        public int hashCode() {
            return Objects.hash(hits, blows);
        }
        
        @Override
        public String toString() {
            return "Result{hits=" + hits + ", blows=" + blows + "}";
        }
    }
    
    public static Result hitAndBlow(List<Integer> answer, List<Integer> guess) {
        int hits = 0;
        for (int i = 0; i < answer.size(); i++) {
            if (answer.get(i).equals(guess.get(i))) {
                hits++;
            }
        }
        
        Map<Integer, Integer> answerCounter = new HashMap<>();
        Map<Integer, Integer> guessCounter = new HashMap<>();
        
        for (int num : answer) {
            answerCounter.put(num, answerCounter.getOrDefault(num, 0) + 1);
        }
        
        for (int num : guess) {
            guessCounter.put(num, guessCounter.getOrDefault(num, 0) + 1);
        }
        
        int totalMatches = 0;
        for (Integer num : answerCounter.keySet()) {
            if (guessCounter.containsKey(num)) {
                totalMatches += Math.min(answerCounter.get(num), guessCounter.get(num));
            }
        }
        
        int blows = totalMatches - hits;
        
        return new Result(hits, blows);
    }
}