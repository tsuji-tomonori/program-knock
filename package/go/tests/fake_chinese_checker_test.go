package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestIsFakeChinese(t *testing.T) {
	t.Run("Sample1", func(t *testing.T) {
		text := "漢字"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "正", result)
	})

	t.Run("Sample2", func(t *testing.T) {
		text := "漢字テスト"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("Sample3", func(t *testing.T) {
		text := "中国語"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "正", result)
	})

	t.Run("Sample4", func(t *testing.T) {
		text := "123"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("Sample5", func(t *testing.T) {
		text := "漢123字"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("EmptyString", func(t *testing.T) {
		text := ""
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("SingleKanji", func(t *testing.T) {
		text := "漢"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "正", result)
	})

	t.Run("HiraganaOnly", func(t *testing.T) {
		text := "ひらがな"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("KatakanaOnly", func(t *testing.T) {
		text := "カタカナ"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("MixedHiraganaKanji", func(t *testing.T) {
		text := "漢字です"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("EnglishOnly", func(t *testing.T) {
		text := "Hello"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("MixedEnglishKanji", func(t *testing.T) {
		text := "漢字Hello"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("NumbersOnly", func(t *testing.T) {
		text := "12345"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("SpecialCharacters", func(t *testing.T) {
		text := "漢字!@#"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("Spaces", func(t *testing.T) {
		text := "漢 字"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("CommonChineseCharacters", func(t *testing.T) {
		text := "学習機会"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "正", result)
	})

	t.Run("TraditionalChinese", func(t *testing.T) {
		text := "學習機會"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "正", result)
	})

	t.Run("JapaneseSpecificKanji", func(t *testing.T) {
		text := "日本語"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "正", result)
	})

	t.Run("LongChineseText", func(t *testing.T) {
		text := "我们是中国人民共和国的公民"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "正", result)
	})

	t.Run("MixedPunctuation", func(t *testing.T) {
		text := "漢字、"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("EdgeCaseCharacters", func(t *testing.T) {
		// Test characters right at the boundary
		// U+4E00 (first CJK character)
		assert.Equal(t, "正", src.IsFakeChinese("一"))
		// U+9FFF (last CJK character)
		assert.Equal(t, "正", src.IsFakeChinese("鿿"))
		// U+4DFF (just before CJK range)
		assert.Equal(t, "誤", src.IsFakeChinese(string(rune(0x4DFF))))
		// U+A000 (just after CJK range)
		assert.Equal(t, "誤", src.IsFakeChinese(string(rune(0xA000))))
	})

	t.Run("OnlyWhitespace", func(t *testing.T) {
		text := "   "
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})

	t.Run("TabsAndNewlines", func(t *testing.T) {
		text := "漢\t字\n"
		result := src.IsFakeChinese(text)
		assert.Equal(t, "誤", result)
	})
}
