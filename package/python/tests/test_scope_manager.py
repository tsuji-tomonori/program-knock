from src.scope_manager import ScopeManager


# サンプルテストケース1
def test_sample1():
    sm = ScopeManager()
    sm.set_variable("x", 10)
    assert sm.get_variable("x") == 10  # グローバルスコープで設定された "x" は 10

    sm.enter_scope()
    assert sm.get_variable("x") == 10  # 内側のスコープでも外側の "x" は参照可能

    sm.set_variable("x", 20)
    assert sm.get_variable("x") == 20  # 内側のスコープで上書きされた "x" は 20

    sm.exit_scope()
    assert (
        sm.get_variable("x") == 10
    )  # スコープを抜けると、グローバルスコープの "x" (10) が参照される


# サンプルテストケース2
def test_sample2():
    sm = ScopeManager()
    sm.set_variable("y", 5)

    sm.enter_scope()
    sm.enter_scope()
    sm.set_variable("z", 30)
    assert sm.get_variable("z") == 30
    assert sm.get_variable("y") == 5  # 外側のスコープから "y" を取得

    sm.exit_scope()
    assert (
        sm.get_variable("z") is None
    )  # 内側のスコープが破棄されたため "z" は存在しない


# サンプルテストケース3
def test_sample3():
    sm = ScopeManager()
    assert sm.get_variable("unknown") is None  # 未設定の変数は None

    sm.enter_scope()
    assert (
        sm.get_variable("unknown") is None
    )  # 内側のスコープでも見つからない場合は None


# サンプルテストケース4
def test_sample4():
    sm = ScopeManager()
    sm.set_variable("a", 100)
    sm.set_variable("b", 200)

    sm.enter_scope()
    sm.set_variable("b", 300)  # "b" を内側のスコープで上書き
    assert sm.get_variable("a") == 100  # 外側の "a" はそのまま
    assert sm.get_variable("b") == 300  # 内側の "b" を参照

    sm.exit_scope()
    assert sm.get_variable("b") == 200  # 内側の "b" は破棄され、外側の "b" が参照される


# サンプルテストケース5
def test_sample5():
    sm = ScopeManager()
    sm.exit_scope()  # グローバルスコープでの exit_scope() は無視される
    sm.set_variable("x", 5)
    sm.exit_scope()  # これも無視される
    assert sm.get_variable("x") == 5  # "x" はグローバルスコープに残る


# 深いネスト時のテスト (境界値テストの一例)
def test_deep_nesting():
    sm = ScopeManager()
    sm.set_variable("global", 1)
    # 10段のネストを作成し、各段階で固有の変数を設定
    for i in range(1, 11):
        sm.enter_scope()
        sm.set_variable(f"var{i}", i)
        # グローバル変数は常に参照可能
        assert sm.get_variable("global") == 1

    # 内側から順にスコープを抜け、各スコープの変数が破棄されることを確認
    for i in reversed(range(1, 11)):
        assert sm.get_variable(f"var{i}") == i
        sm.exit_scope()
        # そのスコープで設定された変数はもはや参照できない
        assert sm.get_variable(f"var{i}") is None

    # 最終的にグローバル変数は残っているはず
    assert sm.get_variable("global") == 1


# 複数の変数を同一スコープで扱うテスト
def test_multiple_variables():
    sm = ScopeManager()
    sm.set_variable("a", 1)
    sm.set_variable("b", 2)
    sm.set_variable("c", 3)
    assert sm.get_variable("a") == 1
    assert sm.get_variable("b") == 2
    assert sm.get_variable("c") == 3

    sm.enter_scope()
    # 内側で "b" を上書きし、他の変数は外側の値が参照される
    sm.set_variable("b", 20)
    assert sm.get_variable("a") == 1
    assert sm.get_variable("b") == 20
    assert sm.get_variable("c") == 3
    sm.exit_scope()

    # 内側の上書きが破棄される
    assert sm.get_variable("b") == 2


# 同一スコープ内での変数上書きのテスト
def test_overwriting_same_scope():
    sm = ScopeManager()
    sm.set_variable("x", 100)
    assert sm.get_variable("x") == 100
    sm.set_variable("x", 200)
    assert sm.get_variable("x") == 200  # 同一スコープ内で上書きされた値が参照される


# 変数名の境界値テスト: 空文字や特殊文字のキーを扱う
def test_boundary_variable_names():
    sm = ScopeManager()
    sm.set_variable("", 0)
    sm.set_variable("!@#$%^&*()", 999)
    assert sm.get_variable("") == 0
    assert sm.get_variable("!@#$%^&*()") == 999

    sm.enter_scope()
    # 内側で上書きせずに外側の値が見えるか確認
    assert sm.get_variable("") == 0
    sm.set_variable("", 1)
    assert sm.get_variable("") == 1
    sm.exit_scope()
    assert sm.get_variable("") == 0


# 極端な整数値を扱う境界値テスト
def test_boundary_extreme_values():
    sm = ScopeManager()
    min_val = -(2**31)
    max_val = 2**31 - 1
    sm.set_variable("min", min_val)
    sm.set_variable("max", max_val)
    assert sm.get_variable("min") == min_val
    assert sm.get_variable("max") == max_val

    sm.enter_scope()
    new_min = -(2**63)
    new_max = 2**63 - 1
    sm.set_variable("min", new_min)
    sm.set_variable("max", new_max)
    assert sm.get_variable("min") == new_min
    assert sm.get_variable("max") == new_max
    sm.exit_scope()

    # スコープを抜けると元の値に戻る
    assert sm.get_variable("min") == min_val
    assert sm.get_variable("max") == max_val


# スコープに入ったり出たりするテスト
def test_reenter_scope_preserves_global_variables():
    # グローバルスコープで変数 "x" を 10 に設定
    sm = ScopeManager()
    sm.set_variable("x", 10)
    assert sm.get_variable("x") == 10

    # 内側のスコープに入り "x" を 20 に上書きする
    sm.enter_scope()
    sm.set_variable("x", 20)
    assert sm.get_variable("x") == 20

    # 内側のスコープから出ると、グローバルスコープの "x" の値 (10) が復元されるはず
    sm.exit_scope()
    assert sm.get_variable("x") == 10

    # 再度内側のスコープに入った場合も、グローバルスコープの "x" が参照できる
    sm.enter_scope()
    # この時点では、内側のスコープで上書きされる前のグローバル値 10 が参照される
    assert sm.get_variable("x") == 10

    # 内側で "x" を 30 に上書きし、その後再度スコープを抜けるテスト
    sm.set_variable("x", 30)
    assert sm.get_variable("x") == 30
    sm.exit_scope()
    # スコープを抜けたので、再びグローバルの "x" (10) が参照される
    assert sm.get_variable("x") == 10
