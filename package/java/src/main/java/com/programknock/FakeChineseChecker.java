package com.programknock;

public class FakeChineseChecker {

    public static String isFakeChinese(String text) {
        if (text == null || text.isEmpty()) {
            return "誤";
        }

        for (char c : text.toCharArray()) {
            if (c < '\u4e00' || c > '\u9fff') {
                return "誤";
            }
        }

        return "正";
    }
}
