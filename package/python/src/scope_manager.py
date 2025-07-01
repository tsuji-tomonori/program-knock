class ScopeManager:
    def __init__(self):
        # 最初からグローバルスコープを用意する。
        self.scopes = [{}]

    def enter_scope(self) -> None:
        """
        新しいスコープを作成し、現在のスコープを一段深くする。
        """
        # 新たな辞書をスタックに追加することで、内側のスコープを表現する。
        self.scopes.append({})

    def exit_scope(self) -> None:
        """
        現在のスコープを破棄し、1 つ上のスコープに戻る。
        最上位のグローバルスコープを削除しようとした場合は何もしない。
        """
        # グローバルスコープが唯一の場合は何もしない。
        if len(self.scopes) > 1:
            self.scopes.pop()

    def set_variable(self, name: str, value: int) -> None:
        """
        現在のスコープに、変数名 name をキーとして value を保存する。
        """
        # 現在の（最も内側の）スコープに変数を設定する。
        self.scopes[-1][name] = value

    def get_variable(self, name: str) -> int | None:
        """
        現在のスコープから順に変数 name を探し、見つかった場合はその値を返す。
        どのスコープにも存在しない場合は None を返す。
        """
        # 内側のスコープから順に探し、最初に見つかった変数を返す（レキシカルスコープの動作）。
        for scope in reversed(self.scopes):
            if name in scope:
                return scope[name]
        return None
