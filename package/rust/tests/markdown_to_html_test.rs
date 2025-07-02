use program_knock::markdown_to_html::*;

#[test]
fn test_markdown_to_html_headers() {
    let md = "# Header 1\n## Header 2\n### Header 3";
    let result = markdown_to_html(md);
    let expected = "<h1>Header 1</h1>\n<h2>Header 2</h2>\n<h3>Header 3</h3>";
    assert_eq!(result, expected);
}

#[test]
fn test_markdown_to_html_inline_elements() {
    let md = "This is **bold** and *italic* and `code`.";
    let result = markdown_to_html(md);
    let expected = "<p>This is <strong>bold</strong> and <em>italic</em> and <code>code</code>.</p>";
    assert_eq!(result, expected);
}

#[test]
fn test_markdown_to_html_mixed_content() {
    let md = "# Title\n\nThis is a paragraph with **bold** text.\n\nAnother paragraph.";
    let result = markdown_to_html(md);
    let expected = "<h1>Title</h1>\n<p>This is a paragraph with <strong>bold</strong> text.</p>\n<p>Another paragraph.</p>";
    assert_eq!(result, expected);
}

#[test]
fn test_markdown_to_html_empty_string() {
    let md = "";
    let result = markdown_to_html(md);
    assert_eq!(result, "");
}

#[test]
fn test_markdown_to_html_single_paragraph() {
    let md = "Just a simple paragraph.";
    let result = markdown_to_html(md);
    let expected = "<p>Just a simple paragraph.</p>";
    assert_eq!(result, expected);
}

#[test]
fn test_markdown_to_html_multiple_empty_lines() {
    let md = "Paragraph 1\n\n\n\nParagraph 2";
    let result = markdown_to_html(md);
    let expected = "<p>Paragraph 1</p>\n<p>Paragraph 2</p>";
    assert_eq!(result, expected);
}

#[test]
fn test_markdown_to_html_maximum_length() {
    let md = "a".repeat(10000);
    let result = markdown_to_html(&md);
    assert!(result.starts_with("<p>"));
    assert!(result.ends_with("</p>"));
}

#[test]
fn test_markdown_to_html_special_characters() {
    let md = "Text with <>&\" special characters.";
    let result = markdown_to_html(md);
    let expected = "<p>Text with <>&\" special characters.</p>";
    assert_eq!(result, expected);
}

#[test]
fn test_markdown_to_html_complex_combinations() {
    let md = "# Title\n\nParagraph with **bold *italic* text** and `code`.\n\n## Subtitle\n\nAnother paragraph.";
    let result = markdown_to_html(md);
    assert!(result.contains("<h1>Title</h1>"));
    assert!(result.contains("<strong>bold <em>italic</em> text</strong>"));
    assert!(result.contains("<code>code</code>"));
    assert!(result.contains("<h2>Subtitle</h2>"));
}

#[test]
fn test_markdown_to_html_nested_elements() {
    let md = "Text with **bold and `code` together** here.";
    let result = markdown_to_html(md);
    assert!(result.contains("<strong>bold and <code>code</code> together</strong>"));
}
