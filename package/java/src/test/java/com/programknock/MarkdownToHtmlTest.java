package com.programknock;

import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class MarkdownToHtmlTest {

    @Test
    void testSample() {
        String md = "# 見出し1\n\n" +
                    "これは *イタリック* と **強調** を含む段落です。\n\n" +
                    "## 見出し2\n\n" +
                    "`コード` を含む別の段落。";
        String expected = "<h1>見出し1</h1>\n" +
                          "<p>これは <em>イタリック</em> と <strong>強調</strong> を含む段落です。</p>\n" +
                          "<h2>見出し2</h2>\n" +
                          "<p><code>コード</code> を含む別の段落。</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testHeading() {
        String md = "### Heading Level 3";
        String expected = "<h3>Heading Level 3</h3>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testPlainParagraph() {
        String md = "This is a simple paragraph without any markdown formatting.";
        String expected = "<p>This is a simple paragraph without any markdown formatting.</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testStrongEmphasis() {
        String md = "This is **bold** text.";
        String expected = "<p>This is <strong>bold</strong> text.</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testItalic() {
        String md = "This is *italic* text.";
        String expected = "<p>This is <em>italic</em> text.</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testCodeSnippet() {
        String md = "Here is some `code`.";
        String expected = "<p>Here is some <code>code</code>.</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testMixedInlineFormatting() {
        String md = "A *italic* word, a **bold** word, and a `code` snippet together.";
        String expected = "<p>A <em>italic</em> word, a <strong>bold</strong> word, and a <code>code</code> snippet together.</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testMultipleParagraphs() {
        String md = "Paragraph one.\n\nParagraph two.\n\nParagraph three.";
        String expected = "<p>Paragraph one.</p>\n<p>Paragraph two.</p>\n<p>Paragraph three.</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testMinimumInput() {
        // Boundary value test: single character input
        String md = "a";
        String expected = "<p>a</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testMaximumInput() {
        // Boundary value test: maximum length (10000 characters) input
        String md = "a".repeat(10000);
        String expected = "<p>" + "a".repeat(10000) + "</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testExtraBlankLines() {
        // When consecutive empty lines are mixed, only valid blocks should be paragraphed without outputting empty paragraphs
        String md = "Line one.\n\n\nLine two.";
        String expected = "<p>Line one.</p>\n<p>Line two.</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testAllHeadings() {
        // Test case including all headings h1~h6
        String md = "# Heading 1\n\n" +
                    "## Heading 2\n\n" +
                    "### Heading 3\n\n" +
                    "#### Heading 4\n\n" +
                    "##### Heading 5\n\n" +
                    "###### Heading 6";
        String expected = "<h1>Heading 1</h1>\n" +
                          "<h2>Heading 2</h2>\n" +
                          "<h3>Heading 3</h3>\n" +
                          "<h4>Heading 4</h4>\n" +
                          "<h5>Heading 5</h5>\n" +
                          "<h6>Heading 6</h6>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testComplexMultipleOccurrences() {
        // Test case where headings, strong, italic, code, and paragraphs each appear 3+ times
        // Confirm that blocks are separated by empty lines and headings are processed as single-line blocks
        String md = "# Heading One with **strong1** and *italic1* and `code1`.\n\n" +
                    "This is the first paragraph with **strong2**, *italic2*, and `code2`.\n\n" +
                    "## Heading Two with **strong3** and *italic3* and `code3`.\n\n" +
                    "This is the second paragraph without inline formatting.\n\n" +
                    "### Heading Three\n\n" +
                    "This is the third paragraph with **strong4**, *italic4*, and `code4`.";
        String expected = "<h1>Heading One with <strong>strong1</strong> and <em>italic1</em> and <code>code1</code>.</h1>\n" +
                          "<p>This is the first paragraph with <strong>strong2</strong>, <em>italic2</em>, and <code>code2</code>.</p>\n" +
                          "<h2>Heading Two with <strong>strong3</strong> and <em>italic3</em> and <code>code3</code>.</h2>\n" +
                          "<p>This is the second paragraph without inline formatting.</p>\n" +
                          "<h3>Heading Three</h3>\n" +
                          "<p>This is the third paragraph with <strong>strong4</strong>, <em>italic4</em>, and <code>code4</code>.</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testInlineCodeWithFormattingInsideHeadingAndParagraph() {
        // Test case for inline code parts included in headings and paragraphs
        // When code contains symbols like ** (strong), * (italic), # (hash),
        // confirm these symbols remain unchanged inside <code> tags without conversion
        String md = "# Heading with code `**bold** and *italic* and # hash` example\n\n" +
                    "Paragraph with code `example **bold** and *italic* and # hash` test.";
        String expected = "<h1>Heading with code <code>**bold** and *italic* and # hash</code> example</h1>\n" +
                          "<p>Paragraph with code <code>example **bold** and *italic* and # hash</code> test.</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testEmptyInput() {
        String md = "";
        String expected = "";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testOnlyEmptyLines() {
        String md = "\n\n\n";
        String expected = "";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testMultipleStrongInSameLine() {
        String md = "This has **first** and **second** bold words.";
        String expected = "<p>This has <strong>first</strong> and <strong>second</strong> bold words.</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testMultipleItalicInSameLine() {
        String md = "This has *first* and *second* italic words.";
        String expected = "<p>This has <em>first</em> and <em>second</em> italic words.</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testMultipleCodeInSameLine() {
        String md = "Use `first` and `second` code snippets.";
        String expected = "<p>Use <code>first</code> and <code>second</code> code snippets.</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testHeadingWithSpaces() {
        String md = "##   Heading with extra spaces   ";
        String expected = "<h2>Heading with extra spaces   </h2>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testInvalidHeadingTooManyHashes() {
        String md = "####### Too many hashes";
        String expected = "<p>####### Too many hashes</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testHeadingWithoutSpace() {
        String md = "#NoSpace";
        String expected = "<p>#NoSpace</p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testSpecialCharactersInCode() {
        String md = "Code with special chars: `<>&\"'`";
        String expected = "<p>Code with special chars: <code><>&\"'</code></p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }

    @Test
    void testNestedFormattingProtectedInCode() {
        String md = "Protected formatting: `**bold** and *italic*`";
        String expected = "<p>Protected formatting: <code>**bold** and *italic*</code></p>";
        assertEquals(expected, MarkdownToHtml.markdownToHtml(md));
    }
}
