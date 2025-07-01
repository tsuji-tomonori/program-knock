from src.markdown_to_html import markdown_to_html


def test_sample():
    md = """# 見出し1

これは *イタリック* と **強調** を含む段落です。

## 見出し2

`コード` を含む別の段落。"""
    expected = (
        "<h1>見出し1</h1>\n"
        "<p>これは <em>イタリック</em> と <strong>強調</strong> を含む段落です。</p>\n"
        "<h2>見出し2</h2>\n"
        "<p><code>コード</code> を含む別の段落。</p>"
    )
    assert markdown_to_html(md) == expected


def test_heading():
    md = "### Heading Level 3"
    expected = "<h3>Heading Level 3</h3>"
    assert markdown_to_html(md) == expected


def test_plain_paragraph():
    md = "This is a simple paragraph without any markdown formatting."
    expected = "<p>This is a simple paragraph without any markdown formatting.</p>"
    assert markdown_to_html(md) == expected


def test_strong_emphasis():
    md = "This is **bold** text."
    expected = "<p>This is <strong>bold</strong> text.</p>"
    assert markdown_to_html(md) == expected


def test_italic():
    md = "This is *italic* text."
    expected = "<p>This is <em>italic</em> text.</p>"
    assert markdown_to_html(md) == expected


def test_code_snippet():
    md = "Here is some `code`."
    expected = "<p>Here is some <code>code</code>.</p>"
    assert markdown_to_html(md) == expected


def test_mixed_inline_formatting():
    md = "A *italic* word, a **bold** word, and a `code` snippet together."
    expected = "<p>A <em>italic</em> word, a <strong>bold</strong> word, and a <code>code</code> snippet together.</p>"
    assert markdown_to_html(md) == expected


def test_multiple_paragraphs():
    md = "Paragraph one.\n\nParagraph two.\n\nParagraph three."
    expected = "<p>Paragraph one.</p>\n<p>Paragraph two.</p>\n<p>Paragraph three.</p>"
    assert markdown_to_html(md) == expected


def test_minimum_input():
    # 境界値テスト: 1文字のみの入力
    md = "a"
    expected = "<p>a</p>"
    assert markdown_to_html(md) == expected


def test_maximum_input():
    # 境界値テスト: 最大長(10000文字)の入力
    md = "a" * 10000
    expected = "<p>" + ("a" * 10000) + "</p>"
    assert markdown_to_html(md) == expected


def test_extra_blank_lines():
    # 連続する空行が混在する場合、空の段落は出力せずに有効なブロックだけを段落化すること
    md = "Line one.\n\n\nLine two."
    expected = "<p>Line one.</p>\n<p>Line two.</p>"
    assert markdown_to_html(md) == expected


def test_all_headings():
    """
    h1～h6すべての見出しを含むテストケース。
    """
    md = (
        "# Heading 1\n\n"
        "## Heading 2\n\n"
        "### Heading 3\n\n"
        "#### Heading 4\n\n"
        "##### Heading 5\n\n"
        "###### Heading 6"
    )
    expected = (
        "<h1>Heading 1</h1>\n"
        "<h2>Heading 2</h2>\n"
        "<h3>Heading 3</h3>\n"
        "<h4>Heading 4</h4>\n"
        "<h5>Heading 5</h5>\n"
        "<h6>Heading 6</h6>"
    )
    assert markdown_to_html(md) == expected


def test_complex_multiple_occurrences():
    """
    見出し、強調、イタリック、コード、段落がそれぞれ3回以上出現するテストケース。
    ブロックごとに空行で区切られ、見出しは1行のみのブロックとして処理されることを確認する。
    """
    md = (
        "# Heading One with **strong1** and *italic1* and `code1`.\n\n"
        "This is the first paragraph with **strong2**, *italic2*, and `code2`.\n\n"
        "## Heading Two with **strong3** and *italic3* and `code3`.\n\n"
        "This is the second paragraph without inline formatting.\n\n"
        "### Heading Three\n\n"
        "This is the third paragraph with **strong4**, *italic4*, and `code4`."
    )
    expected = (
        "<h1>Heading One with <strong>strong1</strong> and <em>italic1</em> and <code>code1</code>.</h1>\n"
        "<p>This is the first paragraph with <strong>strong2</strong>, <em>italic2</em>, and <code>code2</code>.</p>\n"
        "<h2>Heading Two with <strong>strong3</strong> and <em>italic3</em> and <code>code3</code>.</h2>\n"
        "<p>This is the second paragraph without inline formatting.</p>\n"
        "<h3>Heading Three</h3>\n"
        "<p>This is the third paragraph with <strong>strong4</strong>, <em>italic4</em>, and <code>code4</code>.</p>"
    )
    assert markdown_to_html(md) == expected


def test_inline_code_with_formatting_inside_heading_and_paragraph():
    """
    見出しと段落内に含まれるインラインコード部分のテストケース。
    コード内に **（強調）、*（イタリック）、#（ハッシュ）などの記号が含まれている場合、
    これらの記号が変換されずにそのまま <code> タグ内に残ることを確認する。
    """
    md = (
        "# Heading with code `**bold** and *italic* and # hash` example\n\n"
        "Paragraph with code `example **bold** and *italic* and # hash` test."
    )
    expected = (
        "<h1>Heading with code <code>**bold** and *italic* and # hash</code> example</h1>\n"
        "<p>Paragraph with code <code>example **bold** and *italic* and # hash</code> test.</p>"
    )
    assert markdown_to_html(md) == expected
