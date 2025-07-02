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
