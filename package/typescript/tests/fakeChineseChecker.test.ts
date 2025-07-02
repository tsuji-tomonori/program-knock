import { isFakeChinese } from '../src/fakeChineseChecker';

describe('isFakeChinese', () => {
  test('sample 1', () => {
    const text = "漢字";
    expect(isFakeChinese(text)).toBe("正");
  });

  test('sample 2', () => {
    const text = "漢字テスト";
    expect(isFakeChinese(text)).toBe("誤");
  });

  test('sample 3', () => {
    const text = "中国語";
    expect(isFakeChinese(text)).toBe("正");
  });

  test('sample 4', () => {
    const text = "123";
    expect(isFakeChinese(text)).toBe("誤");
  });

  test('sample 5', () => {
    const text = "漢123字";
    expect(isFakeChinese(text)).toBe("誤");
  });

  test('empty string', () => {
    const text = "";
    expect(isFakeChinese(text)).toBe("誤");
  });

  test('single kanji', () => {
    const text = "漢";
    expect(isFakeChinese(text)).toBe("正");
  });

  test('hiragana only', () => {
    const text = "ひらがな";
    expect(isFakeChinese(text)).toBe("誤");
  });

  test('katakana only', () => {
    const text = "カタカナ";
    expect(isFakeChinese(text)).toBe("誤");
  });

  test('mixed hiragana kanji', () => {
    const text = "漢字です";
    expect(isFakeChinese(text)).toBe("誤");
  });

  test('english only', () => {
    const text = "Hello";
    expect(isFakeChinese(text)).toBe("誤");
  });

  test('mixed english kanji', () => {
    const text = "漢字Hello";
    expect(isFakeChinese(text)).toBe("誤");
  });

  test('numbers only', () => {
    const text = "12345";
    expect(isFakeChinese(text)).toBe("誤");
  });

  test('special characters', () => {
    const text = "漢字!@#";
    expect(isFakeChinese(text)).toBe("誤");
  });

  test('spaces', () => {
    const text = "漢 字";
    expect(isFakeChinese(text)).toBe("誤");
  });

  test('common chinese characters', () => {
    const text = "学習機会";
    expect(isFakeChinese(text)).toBe("正");
  });

  test('traditional chinese', () => {
    const text = "學習機會";
    expect(isFakeChinese(text)).toBe("正");
  });

  test('japanese specific kanji', () => {
    const text = "日本語";
    expect(isFakeChinese(text)).toBe("正");
  });

  test('long chinese text', () => {
    const text = "我们是中国人民共和国的公民";
    expect(isFakeChinese(text)).toBe("正");
  });

  test('mixed punctuation', () => {
    const text = "漢字、";
    expect(isFakeChinese(text)).toBe("誤");
  });
});
