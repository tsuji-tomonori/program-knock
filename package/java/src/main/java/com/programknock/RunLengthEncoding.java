package com.programknock;

import java.util.*;

public class RunLengthEncoding {

    public static class CharCount {
        public final String character;
        public final int count;

        public CharCount(String character, int count) {
            this.character = character;
            this.count = count;
        }

        @Override
        public boolean equals(Object obj) {
            if (this == obj) return true;
            if (obj == null || getClass() != obj.getClass()) return false;
            CharCount that = (CharCount) obj;
            return count == that.count && Objects.equals(character, that.character);
        }

        @Override
        public int hashCode() {
            return Objects.hash(character, count);
        }

        @Override
        public String toString() {
            return "(" + character + ", " + count + ")";
        }
    }

    public static List<CharCount> runLengthEncoding(String s) {
        if (s == null || s.isEmpty()) {
            return new ArrayList<>();
        }

        List<CharCount> result = new ArrayList<>();
        char currentChar = s.charAt(0);
        int count = 1;

        for (int i = 1; i < s.length(); i++) {
            if (s.charAt(i) == currentChar) {
                count++;
            } else {
                result.add(new CharCount(String.valueOf(currentChar), count));
                currentChar = s.charAt(i);
                count = 1;
            }
        }

        result.add(new CharCount(String.valueOf(currentChar), count));
        return result;
    }
}
