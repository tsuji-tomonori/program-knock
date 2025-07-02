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

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_is_fake_chinese_pure_kanji() {
        let text = "漢字";
        let result = is_fake_chinese(text);
        assert_eq!(result, "正");
    }

    #[test]
    fn test_is_fake_chinese_katakana_mixed() {
        let text = "漢字カタカナ";
        let result = is_fake_chinese(text);
        assert_eq!(result, "誤");
    }

    #[test]
    fn test_is_fake_chinese_numbers_mixed() {
        let text = "漢字123";
        let result = is_fake_chinese(text);
        assert_eq!(result, "誤");
    }

    #[test]
    fn test_is_fake_chinese_single_kanji() {
        let text = "字";
        let result = is_fake_chinese(text);
        assert_eq!(result, "正");
    }

    #[test]
    fn test_is_fake_chinese_hiragana_mixed() {
        let text = "漢字ひらがな";
        let result = is_fake_chinese(text);
        assert_eq!(result, "誤");
    }

    #[test]
    fn test_is_fake_chinese_various_character_types() {
        let text = "漢字ABC";
        let result = is_fake_chinese(text);
        assert_eq!(result, "誤");
    }

    #[test]
    fn test_is_fake_chinese_empty_string() {
        let text = "";
        let result = is_fake_chinese(text);
        assert_eq!(result, "誤");
    }

    #[test]
    fn test_is_fake_chinese_maximum_length() {
        let text = "漢".repeat(100);
        let result = is_fake_chinese(&text);
        assert_eq!(result, "正");
    }

    #[test]
    fn test_is_fake_chinese_complex_kanji() {
        let text = "龍鳳麒麟";
        let result = is_fake_chinese(text);
        assert_eq!(result, "正");
    }

    #[test]
    fn test_is_fake_chinese_punctuation_mixed() {
        let text = "漢字。";
        let result = is_fake_chinese(text);
        assert_eq!(result, "誤");
    }

    #[test]
    fn test_is_fake_chinese_spaces_mixed() {
        let text = "漢 字";
        let result = is_fake_chinese(text);
        assert_eq!(result, "誤");
    }

    #[test]
    fn test_is_fake_chinese_traditional_chinese() {
        let text = "繁體字";
        let result = is_fake_chinese(text);
        assert_eq!(result, "正");
    }
}