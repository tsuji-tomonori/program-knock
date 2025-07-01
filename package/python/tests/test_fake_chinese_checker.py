from src.fake_chinese_checker import is_fake_chinese


class TestFakeChineseChecker:
    def test_sample_1(self):
        text = "漢字"
        assert is_fake_chinese(text) == "正"

    def test_sample_2(self):
        text = "漢字テスト"
        assert is_fake_chinese(text) == "誤"

    def test_sample_3(self):
        text = "中国語"
        assert is_fake_chinese(text) == "正"

    def test_sample_4(self):
        text = "123"
        assert is_fake_chinese(text) == "誤"

    def test_sample_5(self):
        text = "漢123字"
        assert is_fake_chinese(text) == "誤"

    def test_empty_string(self):
        text = ""
        assert is_fake_chinese(text) == "誤"

    def test_single_kanji(self):
        text = "漢"
        assert is_fake_chinese(text) == "正"

    def test_hiragana_only(self):
        text = "ひらがな"
        assert is_fake_chinese(text) == "誤"

    def test_katakana_only(self):
        text = "カタカナ"
        assert is_fake_chinese(text) == "誤"

    def test_mixed_hiragana_kanji(self):
        text = "漢字です"
        assert is_fake_chinese(text) == "誤"

    def test_english_only(self):
        text = "Hello"
        assert is_fake_chinese(text) == "誤"

    def test_mixed_english_kanji(self):
        text = "漢字Hello"
        assert is_fake_chinese(text) == "誤"

    def test_numbers_only(self):
        text = "12345"
        assert is_fake_chinese(text) == "誤"

    def test_special_characters(self):
        text = "漢字!@#"
        assert is_fake_chinese(text) == "誤"

    def test_spaces(self):
        text = "漢 字"
        assert is_fake_chinese(text) == "誤"

    def test_common_chinese_characters(self):
        text = "学習機会"
        assert is_fake_chinese(text) == "正"

    def test_traditional_chinese(self):
        text = "學習機會"
        assert is_fake_chinese(text) == "正"

    def test_japanese_specific_kanji(self):
        text = "日本語"
        assert is_fake_chinese(text) == "正"

    def test_long_chinese_text(self):
        text = "我们是中国人民共和国的公民"
        assert is_fake_chinese(text) == "正"

    def test_mixed_punctuation(self):
        text = "漢字、"
        assert is_fake_chinese(text) == "誤"
