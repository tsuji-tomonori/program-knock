pub fn hit_and_blow(answer: &[i32], guess: &[i32]) -> (i32, i32) {
    let mut hits = 0;
    let mut answer_counts = std::collections::HashMap::new();
    let mut guess_counts = std::collections::HashMap::new();

    for i in 0..answer.len().min(guess.len()) {
        if answer[i] == guess[i] {
            hits += 1;
        } else {
            *answer_counts.entry(answer[i]).or_insert(0) += 1;
            *guess_counts.entry(guess[i]).or_insert(0) += 1;
        }
    }

    let mut common_count = 0;
    for (&value, &answer_count) in &answer_counts {
        if let Some(&guess_count) = guess_counts.get(&value) {
            common_count += answer_count.min(guess_count);
        }
    }

    let blows = common_count;

    (hits, blows)
}
