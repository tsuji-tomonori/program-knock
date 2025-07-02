from collections import Counter


def hit_and_blow(answer: list[int], guess: list[int]) -> tuple[int, int]:
    """
    Hit & Blow の判定を行う関数

    Args:
        answer (List[int]): 正解の数値のリスト
        guess (List[int]): プレイヤーの推測リスト (同じ長さ)

    Returns:
        Tuple[int, int]: (Hit数, Blow数)
    """
    # Count hits (same position and same value)
    hits = sum(1 for i in range(len(answer)) if answer[i] == guess[i])

    # Count total matches (regardless of position)
    answer_counter = Counter(answer)
    guess_counter = Counter(guess)

    # Total matches is the sum of minimum counts for each number
    total_matches = sum(min(answer_counter[num], guess_counter[num]) for num in answer_counter)

    # Blows = total matches - hits
    blows = total_matches - hits

    return (hits, blows)
