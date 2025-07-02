import { ScopeManager } from './scopeManager';

describe('ScopeManager', () => {
  test('sample1', () => {
    const sm = new ScopeManager();
    sm.setVariable("x", 10);
    expect(sm.getVariable("x")).toBe(10); // グローバルスコープで設定された "x" は 10

    sm.enterScope();
    expect(sm.getVariable("x")).toBe(10); // 内側のスコープでも外側の "x" は参照可能

    sm.setVariable("x", 20);
    expect(sm.getVariable("x")).toBe(20); // 内側のスコープで上書きされた "x" は 20

    sm.exitScope();
    expect(sm.getVariable("x")).toBe(10); // スコープを抜けると、グローバルスコープの "x" (10) が参照される
  });

  test('sample2', () => {
    const sm = new ScopeManager();
    sm.setVariable("y", 5);

    sm.enterScope();
    sm.enterScope();
    sm.setVariable("z", 30);
    expect(sm.getVariable("z")).toBe(30);
    expect(sm.getVariable("y")).toBe(5); // 外側のスコープから "y" を取得

    sm.exitScope();
    expect(sm.getVariable("z")).toBe(null); // 内側のスコープが破棄されたため "z" は存在しない
  });

  test('sample3', () => {
    const sm = new ScopeManager();
    expect(sm.getVariable("unknown")).toBe(null); // 未設定の変数は null

    sm.enterScope();
    expect(sm.getVariable("unknown")).toBe(null); // 内側のスコープでも見つからない場合は null
  });

  test('sample4', () => {
    const sm = new ScopeManager();
    sm.setVariable("a", 100);
    sm.setVariable("b", 200);

    sm.enterScope();
    sm.setVariable("b", 300); // "b" を内側のスコープで上書き
    expect(sm.getVariable("a")).toBe(100); // 外側の "a" はそのまま
    expect(sm.getVariable("b")).toBe(300); // 内側の "b" を参照

    sm.exitScope();
    expect(sm.getVariable("b")).toBe(200); // 内側の "b" は破棄され、外側の "b" が参照される
  });

  test('sample5', () => {
    const sm = new ScopeManager();
    sm.exitScope(); // グローバルスコープでの exitScope() は無視される
    sm.setVariable("x", 5);
    sm.exitScope(); // これも無視される
    expect(sm.getVariable("x")).toBe(5); // "x" はグローバルスコープに残る
  });

  test('deep_nesting', () => {
    // 深いネスト時のテスト (境界値テストの一例)
    const sm = new ScopeManager();
    sm.setVariable("global", 1);
    // 10段のネストを作成し、各段階で固有の変数を設定
    for (let i = 1; i <= 10; i++) {
      sm.enterScope();
      sm.setVariable(`var${i}`, i);
      // グローバル変数は常に参照可能
      expect(sm.getVariable("global")).toBe(1);
    }

    // 内側から順にスコープを抜け、各スコープの変数が破棄されることを確認
    for (let i = 10; i >= 1; i--) {
      expect(sm.getVariable(`var${i}`)).toBe(i);
      sm.exitScope();
      // そのスコープで設定された変数はもはや参照できない
      expect(sm.getVariable(`var${i}`)).toBe(null);
    }

    // 最終的にグローバル変数は残っているはず
    expect(sm.getVariable("global")).toBe(1);
  });

  test('multiple_variables', () => {
    // 複数の変数を同一スコープで扱うテスト
    const sm = new ScopeManager();
    sm.setVariable("a", 1);
    sm.setVariable("b", 2);
    sm.setVariable("c", 3);
    expect(sm.getVariable("a")).toBe(1);
    expect(sm.getVariable("b")).toBe(2);
    expect(sm.getVariable("c")).toBe(3);

    sm.enterScope();
    // 内側で "b" を上書きし、他の変数は外側の値が参照される
    sm.setVariable("b", 20);
    expect(sm.getVariable("a")).toBe(1);
    expect(sm.getVariable("b")).toBe(20);
    expect(sm.getVariable("c")).toBe(3);
    sm.exitScope();

    // 内側の上書きが破棄される
    expect(sm.getVariable("b")).toBe(2);
  });

  test('overwriting_same_scope', () => {
    // 同一スコープ内での変数上書きのテスト
    const sm = new ScopeManager();
    sm.setVariable("x", 100);
    expect(sm.getVariable("x")).toBe(100);
    sm.setVariable("x", 200);
    expect(sm.getVariable("x")).toBe(200); // 同一スコープ内で上書きされた値が参照される
  });

  test('boundary_variable_names', () => {
    // 変数名の境界値テスト: 空文字や特殊文字のキーを扱う
    const sm = new ScopeManager();
    sm.setVariable("", 0);
    sm.setVariable("!@#$%^&*()", 999);
    expect(sm.getVariable("")).toBe(0);
    expect(sm.getVariable("!@#$%^&*()")).toBe(999);

    sm.enterScope();
    // 内側で上書きせずに外側の値が見えるか確認
    expect(sm.getVariable("")).toBe(0);
    sm.setVariable("", 1);
    expect(sm.getVariable("")).toBe(1);
    sm.exitScope();
    expect(sm.getVariable("")).toBe(0);
  });

  test('boundary_extreme_values', () => {
    // 極端な整数値を扱う境界値テスト
    const sm = new ScopeManager();
    const minVal = -(2 ** 31);
    const maxVal = 2 ** 31 - 1;
    sm.setVariable("min", minVal);
    sm.setVariable("max", maxVal);
    expect(sm.getVariable("min")).toBe(minVal);
    expect(sm.getVariable("max")).toBe(maxVal);

    sm.enterScope();
    const newMin = Number.MIN_SAFE_INTEGER;
    const newMax = Number.MAX_SAFE_INTEGER;
    sm.setVariable("min", newMin);
    sm.setVariable("max", newMax);
    expect(sm.getVariable("min")).toBe(newMin);
    expect(sm.getVariable("max")).toBe(newMax);
    sm.exitScope();

    // スコープを抜けると元の値に戻る
    expect(sm.getVariable("min")).toBe(minVal);
    expect(sm.getVariable("max")).toBe(maxVal);
  });

  test('reenter_scope_preserves_global_variables', () => {
    // スコープに入ったり出たりするテスト
    // グローバルスコープで変数 "x" を 10 に設定
    const sm = new ScopeManager();
    sm.setVariable("x", 10);
    expect(sm.getVariable("x")).toBe(10);

    // 内側のスコープに入り "x" を 20 に上書きする
    sm.enterScope();
    sm.setVariable("x", 20);
    expect(sm.getVariable("x")).toBe(20);

    // 内側のスコープから出ると、グローバルスコープの "x" の値 (10) が復元されるはず
    sm.exitScope();
    expect(sm.getVariable("x")).toBe(10);

    // 再度内側のスコープに入った場合も、グローバルスコープの "x" が参照できる
    sm.enterScope();
    // この時点では、内側のスコープで上書きされる前のグローバル値 10 が参照される
    expect(sm.getVariable("x")).toBe(10);

    // 内側で "x" を 30 に上書きし、その後再度スコープを抜けるテスト
    sm.setVariable("x", 30);
    expect(sm.getVariable("x")).toBe(30);
    sm.exitScope();
    // スコープを抜けたので、再びグローバルの "x" (10) が参照される
    expect(sm.getVariable("x")).toBe(10);
  });
});
