package com.programknock;

import java.util.*;
import java.util.regex.*;

public class MarkdownToHtml {

    public static String markdownToHtml(String md) {
        // Split input text into lines
        String[] lines = md.split("\n", -1);
        List<List<String>> blocks = new ArrayList<>();
        List<String> currentBlock = new ArrayList<>();

        // Split into blocks separated by empty lines (ignore empty blocks)
        for (String line : lines) {
            if (line.trim().isEmpty()) {
                if (!currentBlock.isEmpty()) {
                    blocks.add(new ArrayList<>(currentBlock));
                    currentBlock.clear();
                }
            } else {
                currentBlock.add(line);
            }
        }
        if (!currentBlock.isEmpty()) {
            blocks.add(currentBlock);
        }

        List<String> htmlLines = new ArrayList<>();
        for (List<String> block : blocks) {
            // If block has only one line and starts with '#', treat as heading
            if (block.size() == 1) {
                String line = block.get(0);
                Pattern headingPattern = Pattern.compile("^(#{1,6})\\s+(.*)$");
                Matcher matcher = headingPattern.matcher(line);
                if (matcher.matches()) {
                    int level = matcher.group(1).length();
                    String content = matcher.group(2);
                    content = replaceInline(content);
                    htmlLines.add(String.format("<h%d>%s</h%d>", level, content, level));
                    continue;
                }
            }

            // Multiple line blocks or non-heading single lines are treated as paragraphs
            String paragraph = String.join(" ", block.stream()
                .map(String::trim)
                .toArray(String[]::new));
            paragraph = replaceInline(paragraph);
            htmlLines.add(String.format("<p>%s</p>", paragraph));
        }

        return String.join("\n", htmlLines);
    }

    private static String replaceInline(String text) {
        // Use regex to find inline code blocks and preserve them
        Pattern codePattern = Pattern.compile("`([^`]+)`");
        Matcher codeMatcher = codePattern.matcher(text);

        List<String> parts = new ArrayList<>();
        List<Boolean> isCode = new ArrayList<>();
        int lastEnd = 0;

        // Split text into code and non-code parts
        while (codeMatcher.find()) {
            // Add non-code part before the match
            if (codeMatcher.start() > lastEnd) {
                parts.add(text.substring(lastEnd, codeMatcher.start()));
                isCode.add(false);
            }
            // Add code part
            parts.add(codeMatcher.group(0));
            isCode.add(true);
            lastEnd = codeMatcher.end();
        }
        // Add remaining non-code part
        if (lastEnd < text.length()) {
            parts.add(text.substring(lastEnd));
            isCode.add(false);
        }

        // If no code found, add the whole text as non-code
        if (parts.isEmpty()) {
            parts.add(text);
            isCode.add(false);
        }

        // Process each part
        for (int i = 0; i < parts.size(); i++) {
            String part = parts.get(i);
            if (isCode.get(i)) {
                // Convert inline code to <code> tags
                String codeContent = part.substring(1, part.length() - 1);
                parts.set(i, String.format("<code>%s</code>", codeContent));
            } else {
                // Apply strong and italic conversion only to non-code parts
                // **...** -> <strong>...</strong>
                part = part.replaceAll("\\*\\*([^*]+)\\*\\*", "<strong>$1</strong>");
                // *...* -> <em>...</em>
                part = part.replaceAll("\\*([^*]+)\\*", "<em>$1</em>");
                parts.set(i, part);
            }
        }

        return String.join("", parts);
    }
}
