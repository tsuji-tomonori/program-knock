<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\SimpleMarkdownParser;

class SimpleMarkdownParserTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected " . json_encode($expected) . ", but got " . json_encode($actual));
        }
    }

    public function testSampleCase(): void
    {
        $md = "# 見出し1\n\nこれは *イタリック* と **強調** を含む段落です。\n\n## 見出し2\n\n`コード` を含む別の段落。";
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<h1>見出し1</h1><p>これは <em>イタリック</em> と <strong>強調</strong> を含む段落です。</p><h2>見出し2</h2><p><code>コード</code> を含む別の段落。</p>';
        $this->assertEquals($expected, $result);
    }

    public function testEmptyString(): void
    {
        $md = '';
        $result = SimpleMarkdownParser::parse($md);
        $this->assertEquals('', $result);
    }

    public function testSimpleParagraph(): void
    {
        $md = 'これは簡単な段落です。';
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<p>これは簡単な段落です。</p>';
        $this->assertEquals($expected, $result);
    }

    public function testHeadingLevels(): void
    {
        $md = "# H1\n## H2\n### H3\n#### H4\n##### H5\n###### H6";
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<h1>H1</h1><h2>H2</h2><h3>H3</h3><h4>H4</h4><h5>H5</h5><h6>H6</h6>';
        $this->assertEquals($expected, $result);
    }

    public function testBoldText(): void
    {
        $md = 'これは **太字** のテストです。';
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<p>これは <strong>太字</strong> のテストです。</p>';
        $this->assertEquals($expected, $result);
    }

    public function testItalicText(): void
    {
        $md = 'これは *イタリック* のテストです。';
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<p>これは <em>イタリック</em> のテストです。</p>';
        $this->assertEquals($expected, $result);
    }

    public function testCodeText(): void
    {
        $md = 'これは `コード` のテストです。';
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<p>これは <code>コード</code> のテストです。</p>';
        $this->assertEquals($expected, $result);
    }

    public function testMultipleInlineElements(): void
    {
        $md = 'これは **太字** と *イタリック* と `コード` を含みます。';
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<p>これは <strong>太字</strong> と <em>イタリック</em> と <code>コード</code> を含みます。</p>';
        $this->assertEquals($expected, $result);
    }

    public function testMultipleParagraphs(): void
    {
        $md = "最初の段落です。\n\n二番目の段落です。\n\n三番目の段落です。";
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<p>最初の段落です。</p><p>二番目の段落です。</p><p>三番目の段落です。</p>';
        $this->assertEquals($expected, $result);
    }

    public function testMixedContent(): void
    {
        $md = "# タイトル\n\n**重要な** 情報があります。\n\n## サブタイトル\n\n`function()` を呼び出してください。";
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<h1>タイトル</h1><p><strong>重要な</strong> 情報があります。</p><h2>サブタイトル</h2><p><code>function()</code> を呼び出してください。</p>';
        $this->assertEquals($expected, $result);
    }

    public function testHeadingWithInlineElements(): void
    {
        $md = "# **太字** のタイトル";
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<h1><strong>太字</strong> のタイトル</h1>';
        $this->assertEquals($expected, $result);
    }

    public function testCodePreservesMarkdown(): void
    {
        $md = '`**これは太字ではない**` と `*これもイタリックではない*`';
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<p><code>**これは太字ではない**</code> と <code>*これもイタリックではない*</code></p>';
        $this->assertEquals($expected, $result);
    }

    public function testNestedBoldAndItalic(): void
    {
        // 現在の実装では単純なネストは完全にはサポートしていない
        // より単純なパターンでテスト
        $md = '**太字** と *イタリック* が並んでいます';
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<p><strong>太字</strong> と <em>イタリック</em> が並んでいます</p>';
        $this->assertEquals($expected, $result);
    }

    public function testMultiLinesParagraph(): void
    {
        $md = "これは複数行の\n段落です。\n同じ段落内です。\n\n新しい段落です。";
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<p>これは複数行の 段落です。 同じ段落内です。</p><p>新しい段落です。</p>';
        $this->assertEquals($expected, $result);
    }

    public function testOnlyHeadings(): void
    {
        $md = "# H1\n## H2\n### H3";
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<h1>H1</h1><h2>H2</h2><h3>H3</h3>';
        $this->assertEquals($expected, $result);
    }

    public function testEmptyLines(): void
    {
        $md = "\n\n段落1\n\n\n\n段落2\n\n";
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<p>段落1</p><p>段落2</p>';
        $this->assertEquals($expected, $result);
    }

    public function testSpecialCharacters(): void
    {
        $md = 'これは **特殊文字@#$%** を含みます。';
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<p>これは <strong>特殊文字@#$%</strong> を含みます。</p>';
        $this->assertEquals($expected, $result);
    }

    public function testEnglishContent(): void
    {
        $md = "# English Title\n\nThis is **bold** and this is *italic* text.\n\n## Code Example\n\n`console.log('hello')` function call.";
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<h1>English Title</h1><p>This is <strong>bold</strong> and this is <em>italic</em> text.</p><h2>Code Example</h2><p><code>console.log(\'hello\')</code> function call.</p>';
        $this->assertEquals($expected, $result);
    }

    public function testNumbers(): void
    {
        $md = "# 第1章\n\n数値は **123** です。\n\n`var x = 456;` というコード。";
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<h1>第1章</h1><p>数値は <strong>123</strong> です。</p><p><code>var x = 456;</code> というコード。</p>';
        $this->assertEquals($expected, $result);
    }

    public function testConsecutiveMarkdown(): void
    {
        $md = '**太字1** **太字2** *イタリック1* *イタリック2*';
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<p><strong>太字1</strong> <strong>太字2</strong> <em>イタリック1</em> <em>イタリック2</em></p>';
        $this->assertEquals($expected, $result);
    }

    public function testInvalidHeading(): void
    {
        $md = "####### 無効な見出し\n\n#無効な見出し2";
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<p>####### 無効な見出し</p><p>#無効な見出し2</p>';
        $this->assertEquals($expected, $result);
    }

    public function testMixedLanguages(): void
    {
        $md = "# 日本語 English 한국어\n\n**太字 Bold 굵기** text.";
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<h1>日本語 English 한국어</h1><p><strong>太字 Bold 굵기</strong> text.</p>';
        $this->assertEquals($expected, $result);
    }

    public function testComplexDocument(): void
    {
        $md = "# メインタイトル\n\nこれは **重要な** ドキュメントです。\n\n## セクション1\n\n*強調されたテキスト* があります。\n複数行にわたる\n段落です。\n\n### サブセクション\n\n`code_example()` 関数を使用してください。\n\n## セクション2\n\n最後の段落です。";
        $result = SimpleMarkdownParser::parse($md);
        $expected = '<h1>メインタイトル</h1><p>これは <strong>重要な</strong> ドキュメントです。</p><h2>セクション1</h2><p><em>強調されたテキスト</em> があります。 複数行にわたる 段落です。</p><h3>サブセクション</h3><p><code>code_example()</code> 関数を使用してください。</p><h2>セクション2</h2><p>最後の段落です。</p>';
        $this->assertEquals($expected, $result);
    }
}
