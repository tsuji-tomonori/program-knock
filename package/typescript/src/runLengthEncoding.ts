/**
 * 文字列 s をランレングス符号化する関数
 *
 * @param s - 符号化する文字列（英小文字のみ、1文字以上）
 * @returns 連続する文字とその回数のタプルからなるリスト
 */
export function runLengthEncoding(s: string): Array<[string, number]> {
  if (!s) {
    return [];
  }

  const result: Array<[string, number]> = [];
  let currentChar = s[0];
  let count = 1;

  for (let i = 1; i < s.length; i++) {
    if (s[i] === currentChar) {
      count++;
    } else {
      result.push([currentChar, count]);
      currentChar = s[i];
      count = 1;
    }
  }

  // Add the last group
  result.push([currentChar, count]);

  return result;
}
