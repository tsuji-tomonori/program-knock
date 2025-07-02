package src

import (
	"regexp"
	"strings"
)

// MarkdownToHTML converts a markdown format string to HTML format string.
//
// Supported syntax:
//   - Headings: Lines starting with '#' are converted to <h1>~<h6> tags based on the number of '#'
//   - Strong: Text surrounded by ** is converted to <strong> tags
//   - Italic: Text surrounded by * is converted to <em> tags
//   - Code: Text surrounded by ` is converted to <code> tags (no conversion inside code)
//   - Paragraphs: Blocks separated by empty lines are converted to <p> tags
//
// Parameters:
//   - md: markdown format string
//
// Returns:
//   - HTML format string
func MarkdownToHTML(md string) string {
	// Split input text into lines
	lines := strings.Split(md, "\n")
	var blocks [][]string
	var currentBlock []string

	// Split into blocks separated by empty lines (ignore empty blocks)
	for _, line := range lines {
		if strings.TrimSpace(line) == "" {
			if len(currentBlock) > 0 {
				blocks = append(blocks, currentBlock)
				currentBlock = []string{}
			}
		} else {
			currentBlock = append(currentBlock, line)
		}
	}
	if len(currentBlock) > 0 {
		blocks = append(blocks, currentBlock)
	}

	var htmlLines []string
	headingPattern := regexp.MustCompile(`^(#{1,6})\s+(.*)$`)

	for _, block := range blocks {
		// If block has only one line and starts with '#', treat as heading
		if len(block) == 1 {
			matches := headingPattern.FindStringSubmatch(block[0])
			if matches != nil {
				level := len(matches[1])
				content := matches[2]
				content = replaceInline(content)
				htmlLines = append(htmlLines, "<h"+string(rune(level+'0'))+">"+content+"</h"+string(rune(level+'0'))+">")
				continue
			}
		}

		// Multi-line blocks or single lines that are not headings are treated as paragraphs
		var paragraphParts []string
		for _, line := range block {
			paragraphParts = append(paragraphParts, strings.TrimSpace(line))
		}
		paragraph := strings.Join(paragraphParts, " ")
		paragraph = replaceInline(paragraph)
		htmlLines = append(htmlLines, "<p>"+paragraph+"</p>")
	}

	return strings.Join(htmlLines, "\n")
}

// replaceInline converts inline markdown syntax (strong, italic, code) to HTML tags.
// Code sections are protected from other conversions.
//
// Parameters:
//   - text: text containing inline syntax
//
// Returns:
//   - text with inline syntax replaced with HTML tags
func replaceInline(text string) string {
	// Split inline code temporarily using regex
	codePattern := regexp.MustCompile("`[^`]+`")
	parts := codePattern.Split(text, -1)
	codeParts := codePattern.FindAllString(text, -1)

	// Apply strong and italic conversion to non-code parts only
	strongPattern := regexp.MustCompile(`\*\*([^*]+)\*\*`)
	italicPattern := regexp.MustCompile(`\*([^*]+)\*`)

	for i, part := range parts {
		// **...** -> <strong>...</strong>
		part = strongPattern.ReplaceAllString(part, "<strong>$1</strong>")
		// *...* -> <em>...</em>
		part = italicPattern.ReplaceAllString(part, "<em>$1</em>")
		parts[i] = part
	}

	// Reassemble with code parts converted to <code> tags
	var result strings.Builder
	codeIndex := 0
	for i, part := range parts {
		result.WriteString(part)
		if i < len(parts)-1 && codeIndex < len(codeParts) {
			// Convert `code` to <code>code</code>
			codeContent := codeParts[codeIndex]
			if strings.HasPrefix(codeContent, "`") && strings.HasSuffix(codeContent, "`") {
				innerCode := codeContent[1 : len(codeContent)-1]
				result.WriteString("<code>" + innerCode + "</code>")
			}
			codeIndex++
		}
	}

	return result.String()
}
