def is_fake_chinese(text: str) -> str:
    """
    Check if a string contains only Chinese characters (Kanji).

    Args:
        text: Input string to check

    Returns:
        "正" if all characters are Chinese/Kanji, "誤" otherwise
    """
    if not text:
        return "誤"

    for char in text:
        # Check if character is a CJK ideograph (Chinese/Japanese/Korean unified ideographs)
        if not ("\u4e00" <= char <= "\u9fff"):
            return "誤"

    return "正"
