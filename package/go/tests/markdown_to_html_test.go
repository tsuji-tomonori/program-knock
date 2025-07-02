package tests

import (
	"strings"
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestMarkdownToHTMLSample(t *testing.T) {
	md := `# 見出し1

これは *イタリック* と **強調** を含む段落です。

## 見出し2

` + "`コード`" + ` を含む別の段落。`
	expected := "<h1>見出し1</h1>\n" +
		"<p>これは <em>イタリック</em> と <strong>強調</strong> を含む段落です。</p>\n" +
		"<h2>見出し2</h2>\n" +
		"<p><code>コード</code> を含む別の段落。</p>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}

func TestMarkdownToHTMLHeading(t *testing.T) {
	md := "### Heading Level 3"
	expected := "<h3>Heading Level 3</h3>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}

func TestMarkdownToHTMLPlainParagraph(t *testing.T) {
	md := "This is a simple paragraph without any markdown formatting."
	expected := "<p>This is a simple paragraph without any markdown formatting.</p>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}

func TestMarkdownToHTMLStrongEmphasis(t *testing.T) {
	md := "This is **bold** text."
	expected := "<p>This is <strong>bold</strong> text.</p>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}

func TestMarkdownToHTMLItalic(t *testing.T) {
	md := "This is *italic* text."
	expected := "<p>This is <em>italic</em> text.</p>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}

func TestMarkdownToHTMLCodeSnippet(t *testing.T) {
	md := "Here is some `code`."
	expected := "<p>Here is some <code>code</code>.</p>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}

func TestMarkdownToHTMLMixedInlineFormatting(t *testing.T) {
	md := "A *italic* word, a **bold** word, and a `code` snippet together."
	expected := "<p>A <em>italic</em> word, a <strong>bold</strong> word, and a <code>code</code> snippet together.</p>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}

func TestMarkdownToHTMLMultipleParagraphs(t *testing.T) {
	md := "Paragraph one.\n\nParagraph two.\n\nParagraph three."
	expected := "<p>Paragraph one.</p>\n<p>Paragraph two.</p>\n<p>Paragraph three.</p>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}

func TestMarkdownToHTMLMinimumInput(t *testing.T) {
	// Boundary test: input with only 1 character
	md := "a"
	expected := "<p>a</p>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}

func TestMarkdownToHTMLMaximumInput(t *testing.T) {
	// Boundary test: maximum length (10000 characters) input
	md := strings.Repeat("a", 10000)
	expected := "<p>" + strings.Repeat("a", 10000) + "</p>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}

func TestMarkdownToHTMLExtraBlankLines(t *testing.T) {
	// When consecutive blank lines are mixed, only valid blocks should be converted to paragraphs without outputting empty paragraphs
	md := "Line one.\n\n\nLine two."
	expected := "<p>Line one.</p>\n<p>Line two.</p>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}

func TestMarkdownToHTMLAllHeadings(t *testing.T) {
	md := "# Heading 1\n\n" +
		"## Heading 2\n\n" +
		"### Heading 3\n\n" +
		"#### Heading 4\n\n" +
		"##### Heading 5\n\n" +
		"###### Heading 6"
	expected := "<h1>Heading 1</h1>\n" +
		"<h2>Heading 2</h2>\n" +
		"<h3>Heading 3</h3>\n" +
		"<h4>Heading 4</h4>\n" +
		"<h5>Heading 5</h5>\n" +
		"<h6>Heading 6</h6>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}

func TestMarkdownToHTMLComplexMultipleOccurrences(t *testing.T) {
	md := "# Heading One with **strong1** and *italic1* and `code1`.\n\n" +
		"This is the first paragraph with **strong2**, *italic2*, and `code2`.\n\n" +
		"## Heading Two with **strong3** and *italic3* and `code3`.\n\n" +
		"This is the second paragraph without inline formatting.\n\n" +
		"### Heading Three\n\n" +
		"This is the third paragraph with **strong4**, *italic4*, and `code4`."
	expected := "<h1>Heading One with <strong>strong1</strong> and <em>italic1</em> and <code>code1</code>.</h1>\n" +
		"<p>This is the first paragraph with <strong>strong2</strong>, <em>italic2</em>, and <code>code2</code>.</p>\n" +
		"<h2>Heading Two with <strong>strong3</strong> and <em>italic3</em> and <code>code3</code>.</h2>\n" +
		"<p>This is the second paragraph without inline formatting.</p>\n" +
		"<h3>Heading Three</h3>\n" +
		"<p>This is the third paragraph with <strong>strong4</strong>, <em>italic4</em>, and <code>code4</code>.</p>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}

func TestMarkdownToHTMLInlineCodeWithFormattingInsideHeadingAndParagraph(t *testing.T) {
	md := "# Heading with code `**bold** and *italic* and # hash` example\n\n" +
		"Paragraph with code `example **bold** and *italic* and # hash` test."
	expected := "<h1>Heading with code <code>**bold** and *italic* and # hash</code> example</h1>\n" +
		"<p>Paragraph with code <code>example **bold** and *italic* and # hash</code> test.</p>"
	assert.Equal(t, expected, src.MarkdownToHTML(md))
}
