<?php

declare(strict_types=1);

namespace ProgramKnock;

class SimpleMarkdownParser
{
    /**
     * 簡易マークダウンパーサー
     *
     * @param string $md マークダウン形式の文字列
     * @return string HTML形式の文字列
     */
    public static function parse(string $md): string
    {
        if (empty($md)) {
            return '';
        }

        $lines = explode("\n", $md);
        $html = [];
        $currentParagraph = [];

        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            // 空行の場合、現在の段落を終了
            if ($trimmedLine === '') {
                if (!empty($currentParagraph)) {
                    $paragraphText = implode(' ', $currentParagraph);
                    $html[] = '<p>' . self::parseInlineElements($paragraphText) . '</p>';
                    $currentParagraph = [];
                }
                continue;
            }

            // 見出しの処理
            if (preg_match('/^(#{1,6})\s+(.+)$/', $trimmedLine, $matches)) {
                // 現在の段落がある場合は先に出力
                if (!empty($currentParagraph)) {
                    $paragraphText = implode(' ', $currentParagraph);
                    $html[] = '<p>' . self::parseInlineElements($paragraphText) . '</p>';
                    $currentParagraph = [];
                }

                $level = strlen($matches[1]);
                $text = $matches[2];
                $html[] = "<h{$level}>" . self::parseInlineElements($text) . "</h{$level}>";
            } else {
                // 通常の段落テキスト
                $currentParagraph[] = $trimmedLine;
            }
        }

        // 最後の段落の処理
        if (!empty($currentParagraph)) {
            $paragraphText = implode(' ', $currentParagraph);
            $html[] = '<p>' . self::parseInlineElements($paragraphText) . '</p>';
        }

        return implode('', $html);
    }

    /**
     * インライン要素を解析（太字、イタリック、コード）
     *
     * @param string $text 解析対象のテキスト
     * @return string 解析済みのHTML
     */
    private static function parseInlineElements(string $text): string
    {
        // コードブロックを最初に処理して保護する
        $codeBlocks = [];
        $codeIndex = 0;
        $text = preg_replace_callback('/`([^`]+)`/', function($matches) use (&$codeBlocks, &$codeIndex) {
            $placeholder = "__CODE_BLOCK_{$codeIndex}__";
            $codeBlocks[$placeholder] = '<code>' . $matches[1] . '</code>';
            $codeIndex++;
            return $placeholder;
        }, $text);

        // 太字（**text**）の処理 - より厳密なパターン
        $text = preg_replace('/\*\*([^*]+?)\*\*/', '<strong>$1</strong>', $text);

        // イタリック（*text*）の処理 - 太字のパターンと重複しないように
        $text = preg_replace('/(?<!\*)\*([^*]+?)\*(?!\*)/', '<em>$1</em>', $text);

        // コードブロックを元に戻す
        foreach ($codeBlocks as $placeholder => $codeHtml) {
            $text = str_replace($placeholder, $codeHtml, $text);
        }

        return $text;
    }
}
