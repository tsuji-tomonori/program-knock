import functools


@functools.lru_cache(maxsize=None)
def levenshtein_distance(s: str, t: str) -> int:
    """
    2つの文字列 s, t のレーベンシュタイン距離（編集距離）を再帰とメモ化を用いて求める関数。
    """
    # ベースケース: どちらかの文字列が空の場合、残りの文字数が距離となる
    if not s:
        return len(t)
    if not t:
        return len(s)

    # 末尾の文字が同じ場合は、その部分はコストなしで再帰
    if s[-1] == t[-1]:
        return levenshtein_distance(s[:-1], t[:-1])
    else:
        # 末尾の文字が異なる場合は、削除、挿入、置換の各操作を試し、その中で最小のものに1を加える
        return (
            1
            + min(
                levenshtein_distance(s[:-1], t),  # 削除
                levenshtein_distance(s, t[:-1]),  # 挿入
                levenshtein_distance(s[:-1], t[:-1]),  # 置換
            )
        )


def suggest_aws_service(wrong_service: str) -> str:
    """
    誤った AWS サービス名を受け取り、最も類似する正しいサービス名をサジェストする。

    Args:
        wrong_service (str): 誤入力されたサービス名

    Returns:
        str: 最も類似する AWS サービス名
    """
    # サポートする AWS サービス一覧
    services = [
        "ec2",
        "s3",
        "lambda",
        "dynamodb",
        "rds",
        "cloudfront",
        "iam",
        "route53",
    ]

    # 最小のレーベンシュタイン距離と候補のサービス名を初期化
    min_distance = float("inf")
    suggestion = services[0]

    # 各サービスと誤入力文字列間の距離を計算し、最も近いものを選択
    for service in services:
        distance = levenshtein_distance(wrong_service, service)
        if distance < min_distance:
            min_distance = distance
            suggestion = service

    return suggestion
