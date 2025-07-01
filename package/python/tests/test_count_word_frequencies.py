from src.count_word_frequencies import count_word_frequencies


def test_basic():
    text = "apple banana apple orange banana apple"
    assert count_word_frequencies(text) == {"apple": 3, "banana": 2, "orange": 1}


def test_single_word():
    text = "python"
    assert count_word_frequencies(text) == {"python": 1}


def test_tied_frequencies():
    text = "dog cat bird cat dog bird"
    assert count_word_frequencies(text) == {"bird": 2, "cat": 2, "dog": 2}


def test_empty_string():
    text = ""
    assert count_word_frequencies(text) == {}


def test_repeated_single_word():
    # 100回 "test" が繰り返される
    text = " ".join(["test"] * 100)
    assert count_word_frequencies(text) == {"test": 100}


def test_words_of_various_lengths():
    text = "a bb ccc a bb"
    # 出現回数
    # a   -> 2
    # bb  -> 2
    # ccc -> 1
    # 順番は出現回数が多い順、同数ならキーの辞書順
    # a と bb は同数なので "a" < "bb"
    assert count_word_frequencies(text) == {"a": 2, "bb": 2, "ccc": 1}


def test_alphabetical_order_for_same_frequency():
    # 同じ回数の場合のアルファベット順確認
    text = "cat dog cat dog fish bird fish bird"
    # 出現回数
    # cat  -> 2
    # dog  -> 2
    # fish -> 2
    # bird -> 2
    # 全て 2 なのでキーを辞書順に並べる
    # bird < cat < dog < fish
    assert count_word_frequencies(text) == {"bird": 2, "cat": 2, "dog": 2, "fish": 2}


def test_varying_frequencies():
    text = "apple apple banana apple banana orange lemon"
    # 出現回数
    # apple  -> 3
    # banana -> 2
    # orange -> 1
    # lemon  -> 1
    # 同じ回数の orange と lemon は辞書順で lemon が先
    # よって apple(3), banana(2), lemon(1), orange(1)
    assert count_word_frequencies(text) == {
        "apple": 3,
        "banana": 2,
        "lemon": 1,
        "orange": 1,
    }


def test_one_letter_words():
    text = "a b c a b a"
    # 出現回数
    # a -> 3
    # b -> 2
    # c -> 1
    assert count_word_frequencies(text) == {"a": 3, "b": 2, "c": 1}


def test_many_words_case():
    # 長めのテキスト（複数の単語を混在）
    text = " ".join(
        ["apple"] * 10
        + ["banana"] * 5
        + ["cherry"] * 5
        + ["banana"] * 3
        + ["apple"] * 2
        + ["cherry"] * 2
    )
    # 出現回数
    # apple  -> 10 + 2 = 12
    # banana -> 5 + 3 = 8
    # cherry -> 5 + 2 = 7
    # 順番は apple(12), banana(8), cherry(7)
    assert count_word_frequencies(text) == {"apple": 12, "banana": 8, "cherry": 7}
