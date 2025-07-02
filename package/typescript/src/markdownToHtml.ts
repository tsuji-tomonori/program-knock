/**
 * マークダウン形式の文字列をHTML形式の文字列に変換する。
 *
 * サポートする記法:
 *   見出し: 行頭の '#' の個数に応じた <h1>〜<h6> タグで囲む
 *   強調: **で囲まれた部分を <strong> タグで囲む
 *   イタリック: *で囲まれた部分を <em> タグで囲む
 *   コード: `で囲まれた部分を <code> タグで囲む（コード内は変換を行わない）
 *   段落: 空行で区切られたブロックを <p> タグで囲む
 *
 * @param md - マークダウン形式の文字列
 * @returns HTML形式の文字列
 */
export function markdownToHtml(md: string): string {
  // 入力テキストを行ごとに分割
  const lines = md.split('\n');
  const blocks: string[][] = [];
  let currentBlock: string[] = [];

  // 空行を境にブロックに分割する（空ブロックは無視）
  for (const line of lines) {
    if (line.trim() === '') {
      if (currentBlock.length > 0) {
        blocks.push(currentBlock);
        currentBlock = [];
      }
    } else {
      currentBlock.push(line);
    }
  }
  if (currentBlock.length > 0) {
    blocks.push(currentBlock);
  }

  const htmlLines: string[] = [];
  for (const block of blocks) {
    // ブロックが1行のみかつ行頭が '#' で始まる場合、見出しと判断する
    if (block.length === 1) {
      const match = block[0].match(/^(#{1,6})\s+(.*)$/);
      if (match) {
        const level = match[1].length;
        const content = match[2];
        const processedContent = replaceInline(content);
        htmlLines.push(`<h${level}>${processedContent}</h${level}>`);
        continue;
      }
    }

    // 複数行のブロックまたは見出しでない1行は段落として扱う
    const paragraph = block.map(line => line.trim()).join(' ');
    const processedParagraph = replaceInline(paragraph);
    htmlLines.push(`<p>${processedParagraph}</p>`);
  }

  return htmlLines.join('\n');
}

/**
 * テキスト中のインラインマークダウン記法（強調、イタリック、コード）をHTMLタグに変換する。
 * コードで囲まれた部分は保護し、他の変換の影響を受けないようにします。
 *
 * @param text - インライン記法を含むテキスト
 * @returns インライン記法をHTMLタグに置換したテキスト
 */
function replaceInline(text: string): string {
  // inline code を一時的に分離するために split を使用
  const parts = text.split(/(`[^`]+`)/);

  // parts のうち、インラインコードでない部分だけに強調やイタリックの変換を適用
  for (let i = 0; i < parts.length; i++) {
    const part = parts[i];
    if (!(part.startsWith('`') && part.endsWith('`'))) {
      // **...** -> <strong>...</strong>
      let processed = part.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
      // *...* -> <em>...</em>
      processed = processed.replace(/\*([^*]+)\*/g, '<em>$1</em>');
      parts[i] = processed;
    }
  }

  // インラインコード部分を <code> タグに変換（中身はそのまま）
  for (let i = 0; i < parts.length; i++) {
    const part = parts[i];
    if (part.startsWith('`') && part.endsWith('`')) {
      const codeContent = part.slice(1, -1);
      parts[i] = `<code>${codeContent}</code>`;
    }
  }

  return parts.join('');
}
