import re


def markdown_to_html(md: str) -> str:
    """
    マークダウン形式の文字列をHTML形式の文字列に変換する。

    サポートする記法:
        見出し: 行頭の '#' の個数に応じた <h1>〜<h6> タグで囲む
        強調: **で囲まれた部分を <strong> タグで囲む
        イタリック: *で囲まれた部分を <em> タグで囲む
        コード: `で囲まれた部分を <code> タグで囲む（コード内は変換を行わない）
        段落: 空行で区切られたブロックを <p> タグで囲む

    Args:
        md (str): マークダウン形式の文字列

    Returns:
        str: HTML形式の文字列
    """
    # 入力テキストを行ごとに分割
    lines = md.splitlines()
    blocks = []
    current_block: list[str] = []

    # 空行を境にブロックに分割する（空ブロックは無視）
    for line in lines:
        if line.strip() == "":
            if current_block:
                blocks.append(current_block)
                current_block = []
        else:
            current_block.append(line)
    if current_block:
        blocks.append(current_block)

    html_lines = []
    for block in blocks:
        # ブロックが1行のみかつ行頭が '#' で始まる場合、見出しと判断する
        if len(block) == 1:
            m = re.match(r"^(#{1,6})\s+(.*)$", block[0])
            if m:
                level = len(m.group(1))
                content = m.group(2)
                content = _replace_inline(content)
                html_lines.append(f"<h{level}>{content}</h{level}>")
                continue

        # 複数行のブロックまたは見出しでない1行は段落として扱う
        paragraph = " ".join(line.strip() for line in block)
        paragraph = _replace_inline(paragraph)
        html_lines.append(f"<p>{paragraph}</p>")

    return "\n".join(html_lines)


def _replace_inline(text: str) -> str:
    """
    テキスト中のインラインマークダウン記法（強調、イタリック、コード）をHTMLタグに変換する。
    コードで囲まれた部分は保護し、他の変換の影響を受けないようにします。

    Args:
        text (str): インライン記法を含むテキスト

    Returns:
        str: インライン記法をHTMLタグに置換したテキスト
    """
    # inline code を一時的に分離するために re.split を使用
    parts = re.split(r"(`[^`]+`)", text)
    # parts のうち、インラインコードでない部分だけに強調やイタリックの変換を適用
    for i, part in enumerate(parts):
        if not (part.startswith("`") and part.endswith("`")):
            # **...** -> <strong>...</strong>
            part = re.sub(r"\*\*([^*]+)\*\*", r"<strong>\1</strong>", part)
            # *...* -> <em>...</em>
            part = re.sub(r"\*([^*]+)\*", r"<em>\1</em>", part)
            parts[i] = part
    # インラインコード部分を <code> タグに変換（中身はそのまま）
    for i, part in enumerate(parts):
        if part.startswith("`") and part.endswith("`"):
            code_content = part[1:-1]
            parts[i] = f"<code>{code_content}</code>"
    return "".join(parts)
