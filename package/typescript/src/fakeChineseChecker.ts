/**
 * Check if a string contains only Chinese characters (Kanji).
 *
 * @param text - Input string to check
 * @returns "正" if all characters are Chinese/Kanji, "誤" otherwise
 */
export function isFakeChinese(text: string): string {
  if (!text) {
    return "誤";
  }

  for (const char of text) {
    // Check if character is a CJK ideograph (Chinese/Japanese/Korean unified ideographs)
    const codePoint = char.codePointAt(0);
    if (!codePoint || !(codePoint >= 0x4e00 && codePoint <= 0x9fff)) {
      return "誤";
    }
  }

  return "正";
}
