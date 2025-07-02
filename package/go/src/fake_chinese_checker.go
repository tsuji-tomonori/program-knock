package src

// IsFakeChinese checks if a string contains only Chinese characters (Kanji)
func IsFakeChinese(text string) string {
	if text == "" {
		return "誤"
	}

	for _, char := range text {
		// Check if character is a CJK ideograph (Chinese/Japanese/Korean unified ideographs)
		// Unicode range U+4E00 to U+9FFF
		if char < '\u4e00' || char > '\u9fff' {
			return "誤"
		}
	}

	return "正"
}
