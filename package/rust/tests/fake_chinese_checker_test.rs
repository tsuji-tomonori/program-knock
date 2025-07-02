use program_knock::fake_chinese_checker::*;

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
