import { markdownToHtml } from '../src/markdownToHtml';

describe('MarkdownToHtml', () => {
  test('sample', () => {
    const md = `# 見出し1

これは *イタリック* と **強調** を含む段落です。

## 見出し2

\`コード\` を含む別の段落。`;
    const expected =
      "<h1>見出し1</h1>\n" +
      "<p>これは <em>イタリック</em> と <strong>強調</strong> を含む段落です。</p>\n" +
      "<h2>見出し2</h2>\n" +
      "<p><code>コード</code> を含む別の段落。</p>";
    expect(markdownToHtml(md)).toBe(expected);
  });

  test('heading', () => {
    const md = "### Heading Level 3";
    const expected = "<h3>Heading Level 3</h3>";
    expect(markdownToHtml(md)).toBe(expected);
  });

  test('plain_paragraph', () => {
    const md = "This is a simple paragraph without any markdown formatting.";
    const expected = "<p>This is a simple paragraph without any markdown formatting.</p>";
    expect(markdownToHtml(md)).toBe(expected);
  });

  test('strong_emphasis', () => {
    const md = "This is **bold** text.";
    const expected = "<p>This is <strong>bold</strong> text.</p>";
    expect(markdownToHtml(md)).toBe(expected);
  });

  test('italic', () => {
    const md = "This is *italic* text.";
    const expected = "<p>This is <em>italic</em> text.</p>";
    expect(markdownToHtml(md)).toBe(expected);
  });

  test('code_snippet', () => {
    const md = "Here is some `code`.";
    const expected = "<p>Here is some <code>code</code>.</p>";
    expect(markdownToHtml(md)).toBe(expected);
  });

  test('mixed_inline_formatting', () => {
    const md = "A *italic* word, a **bold** word, and a `code` snippet together.";
    const expected = "<p>A <em>italic</em> word, a <strong>bold</strong> word, and a <code>code</code> snippet together.</p>";
    expect(markdownToHtml(md)).toBe(expected);
  });

  test('multiple_paragraphs', () => {
    const md = "Paragraph one.\n\nParagraph two.\n\nParagraph three.";
    const expected = "<p>Paragraph one.</p>\n<p>Paragraph two.</p>\n<p>Paragraph three.</p>";
    expect(markdownToHtml(md)).toBe(expected);
  });

  test('minimum_input', () => {
    // 境界値テスト: 1文字のみの入力
    const md = "a";
    const expected = "<p>a</p>";
    expect(markdownToHtml(md)).toBe(expected);
  });

  test('maximum_input', () => {
    // 境界値テスト: 最大長(10000文字)の入力
    const md = "a".repeat(10000);
    const expected = "<p>" + "a".repeat(10000) + "</p>";
    expect(markdownToHtml(md)).toBe(expected);
  });

  test('extra_blank_lines', () => {
    // 連続する空行が混在する場合、空の段落は出力せずに有効なブロックだけを段落化すること
    const md = "Line one.\n\n\nLine two.";
    const expected = "<p>Line one.</p>\n<p>Line two.</p>";
    expect(markdownToHtml(md)).toBe(expected);
  });

  test('all_headings', () => {
    // h1～h6すべての見出しを含むテストケース
    const md =
      "# Heading 1\n\n" +
      "## Heading 2\n\n" +
      "### Heading 3\n\n" +
      "#### Heading 4\n\n" +
      "##### Heading 5\n\n" +
      "###### Heading 6";
    const expected =
      "<h1>Heading 1</h1>\n" +
      "<h2>Heading 2</h2>\n" +
      "<h3>Heading 3</h3>\n" +
      "<h4>Heading 4</h4>\n" +
      "<h5>Heading 5</h5>\n" +
      "<h6>Heading 6</h6>";
    expect(markdownToHtml(md)).toBe(expected);
  });

  test('complex_multiple_occurrences', () => {
    // 見出し、強調、イタリック、コード、段落がそれぞれ3回以上出現するテストケース
    const md =
      "# Heading One with **strong1** and *italic1* and `code1`.\n\n" +
      "This is the first paragraph with **strong2**, *italic2*, and `code2`.\n\n" +
      "## Heading Two with **strong3** and *italic3* and `code3`.\n\n" +
      "This is the second paragraph without inline formatting.\n\n" +
      "### Heading Three\n\n" +
      "This is the third paragraph with **strong4**, *italic4*, and `code4`.";
    const expected =
      "<h1>Heading One with <strong>strong1</strong> and <em>italic1</em> and <code>code1</code>.</h1>\n" +
      "<p>This is the first paragraph with <strong>strong2</strong>, <em>italic2</em>, and <code>code2</code>.</p>\n" +
      "<h2>Heading Two with <strong>strong3</strong> and <em>italic3</em> and <code>code3</code>.</h2>\n" +
      "<p>This is the second paragraph without inline formatting.</p>\n" +
      "<h3>Heading Three</h3>\n" +
      "<p>This is the third paragraph with <strong>strong4</strong>, <em>italic4</em>, and <code>code4</code>.</p>";
    expect(markdownToHtml(md)).toBe(expected);
  });

  test('inline_code_with_formatting_inside_heading_and_paragraph', () => {
    // 見出しと段落内に含まれるインラインコード部分のテストケース
    const md =
      "# Heading with code `**bold** and *italic* and # hash` example\n\n" +
      "Paragraph with code `example **bold** and *italic* and # hash` test.";
    const expected =
      "<h1>Heading with code <code>**bold** and *italic* and # hash</code> example</h1>\n" +
      "<p>Paragraph with code <code>example **bold** and *italic* and # hash</code> test.</p>";
    expect(markdownToHtml(md)).toBe(expected);
  });
});
