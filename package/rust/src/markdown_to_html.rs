pub fn markdown_to_html(md: &str) -> String {
    let lines: Vec<&str> = md.lines().collect();
    let mut html = String::new();
    let mut in_paragraph = false;
    
    for line in lines {
        let trimmed = line.trim();
        
        if trimmed.is_empty() {
            if in_paragraph {
                html.push_str("</p>\n");
                in_paragraph = false;
            }
            continue;
        }
        
        if trimmed.starts_with('#') {
            if in_paragraph {
                html.push_str("</p>\n");
                in_paragraph = false;
            }
            
            let level = trimmed.chars().take_while(|&c| c == '#').count();
            let content_string = trimmed.chars().skip(level).collect::<String>();
            let content = content_string.trim();
            html.push_str(&format!("<h{}>{}</h{}>\n", level, process_inline(content), level));
        } else {
            if !in_paragraph {
                html.push_str("<p>");
                in_paragraph = true;
            } else {
                html.push(' ');
            }
            html.push_str(&process_inline(trimmed));
        }
    }
    
    if in_paragraph {
        html.push_str("</p>\n");
    }
    
    html.trim_end().to_string()
}

fn process_inline(text: &str) -> String {
    let mut result = text.to_string();
    
    result = process_code(&result);
    result = process_bold(&result);
    result = process_italic(&result);
    
    result
}

fn process_code(text: &str) -> String {
    let mut result = String::new();
    let chars = text.chars();
    let mut in_code = false;
    
    for ch in chars {
        if ch == '`' {
            if in_code {
                result.push_str("</code>");
                in_code = false;
            } else {
                result.push_str("<code>");
                in_code = true;
            }
        } else {
            result.push(ch);
        }
    }
    
    result
}

fn process_bold(text: &str) -> String {
    let mut result = String::new();
    let mut i = 0;
    let chars: Vec<char> = text.chars().collect();
    
    while i < chars.len() {
        if i + 1 < chars.len() && chars[i] == '*' && chars[i + 1] == '*' {
            let start = i + 2;
            let mut end = None;
            
            for j in (start + 1)..chars.len() {
                if j + 1 < chars.len() && chars[j] == '*' && chars[j + 1] == '*' {
                    end = Some(j);
                    break;
                }
            }
            
            if let Some(end_pos) = end {
                result.push_str("<strong>");
                result.push_str(&chars[start..end_pos].iter().collect::<String>());
                result.push_str("</strong>");
                i = end_pos + 2;
            } else {
                result.push(chars[i]);
                i += 1;
            }
        } else {
            result.push(chars[i]);
            i += 1;
        }
    }
    
    result
}

fn process_italic(text: &str) -> String {
    let mut result = String::new();
    let mut i = 0;
    let chars: Vec<char> = text.chars().collect();
    
    while i < chars.len() {
        if chars[i] == '*' && (i == 0 || chars[i - 1] != '*') && 
           (i + 1 >= chars.len() || chars[i + 1] != '*') {
            let start = i + 1;
            let mut end = None;
            
            for j in (start + 1)..chars.len() {
                if chars[j] == '*' && (j + 1 >= chars.len() || chars[j + 1] != '*') &&
                   (j == 0 || chars[j - 1] != '*') {
                    end = Some(j);
                    break;
                }
            }
            
            if let Some(end_pos) = end {
                result.push_str("<em>");
                result.push_str(&chars[start..end_pos].iter().collect::<String>());
                result.push_str("</em>");
                i = end_pos + 1;
            } else {
                result.push(chars[i]);
                i += 1;
            }
        } else {
            result.push(chars[i]);
            i += 1;
        }
    }
    
    result
}

#[cfg(test)]
mod tests {
    use super::*;

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
}