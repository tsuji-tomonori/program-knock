pub fn is_fake_chinese(text: &str) -> String {
    if text.is_empty() {
        return "誤".to_string();
    }

    for ch in text.chars() {
        if !is_chinese_character(ch) {
            return "誤".to_string();
        }
    }

    "正".to_string()
}

fn is_chinese_character(ch: char) -> bool {
    let code = ch as u32;

    (0x4E00..=0x9FFF).contains(&code) ||  // CJK Unified Ideographs
    (0x3400..=0x4DBF).contains(&code) ||  // CJK Extension A
    (0x20000..=0x2A6DF).contains(&code) || // CJK Extension B
    (0x2A700..=0x2B73F).contains(&code) || // CJK Extension C
    (0x2B740..=0x2B81F).contains(&code) || // CJK Extension D
    (0x2B820..=0x2CEAF).contains(&code) || // CJK Extension E
    (0xF900..=0xFAFF).contains(&code) ||   // CJK Compatibility Ideographs
    (0x2F800..=0x2FA1F).contains(&code)    // CJK Compatibility Supplement
}
