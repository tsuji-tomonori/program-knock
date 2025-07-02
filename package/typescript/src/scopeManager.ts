export class ScopeManager {
  private scopes: Array<Map<string, number>>;

  constructor() {
    // 最初からグローバルスコープを用意する。
    this.scopes = [new Map()];
  }

  /**
   * 新しいスコープを作成し、現在のスコープを一段深くする。
   */
  enterScope(): void {
    // 新たなMapをスタックに追加することで、内側のスコープを表現する。
    this.scopes.push(new Map());
  }

  /**
   * 現在のスコープを破棄し、1 つ上のスコープに戻る。
   * 最上位のグローバルスコープを削除しようとした場合は何もしない。
   */
  exitScope(): void {
    // グローバルスコープが唯一の場合は何もしない。
    if (this.scopes.length > 1) {
      this.scopes.pop();
    }
  }

  /**
   * 現在のスコープに、変数名 name をキーとして value を保存する。
   */
  setVariable(name: string, value: number): void {
    // 現在の（最も内側の）スコープに変数を設定する。
    this.scopes[this.scopes.length - 1].set(name, value);
  }

  /**
   * 現在のスコープから順に変数 name を探し、見つかった場合はその値を返す。
   * どのスコープにも存在しない場合は null を返す。
   */
  getVariable(name: string): number | null {
    // 内側のスコープから順に探し、最初に見つかった変数を返す（レキシカルスコープの動作）。
    for (let i = this.scopes.length - 1; i >= 0; i--) {
      if (this.scopes[i].has(name)) {
        return this.scopes[i].get(name)!;
      }
    }
    return null;
  }
}
