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

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_hit_and_blow_basic() {
        let answer = vec![1, 2, 3, 4];
        let guess = vec![1, 3, 2, 5];
        let result = hit_and_blow(&answer, &guess);
        assert_eq!(result, (1, 2));
    }

    #[test]
    fn test_hit_and_blow_all_blow() {
        let answer = vec![1, 2, 3, 4];
        let guess = vec![4, 3, 2, 1];
        let result = hit_and_blow(&answer, &guess);
        assert_eq!(result, (0, 4));
    }

    #[test]
    fn test_hit_and_blow_all_hit() {
        let answer = vec![1, 2, 3, 4];
        let guess = vec![1, 2, 3, 4];
        let result = hit_and_blow(&answer, &guess);
        assert_eq!(result, (4, 0));
    }

    #[test]
    fn test_hit_and_blow_all_miss() {
        let answer = vec![1, 2, 3, 4];
        let guess = vec![5, 6, 7, 8];
        let result = hit_and_blow(&answer, &guess);
        assert_eq!(result, (0, 0));
    }

    #[test]
    fn test_hit_and_blow_single_element() {
        let answer = vec![1];
        let guess = vec![1];
        let result = hit_and_blow(&answer, &guess);
        assert_eq!(result, (1, 0));
    }

    #[test]
    fn test_hit_and_blow_alternating() {
        let answer = vec![1, 2, 1, 2];
        let guess = vec![2, 1, 2, 1];
        let result = hit_and_blow(&answer, &guess);
        assert_eq!(result, (0, 4));
    }

    #[test]
    fn test_hit_and_blow_boundary_values() {
        let answer = vec![0, i32::MAX, i32::MIN];
        let guess = vec![0, i32::MIN, i32::MAX];
        let result = hit_and_blow(&answer, &guess);
        assert_eq!(result, (1, 2));
    }

    #[test]
    fn test_hit_and_blow_large_arrays() {
        let answer: Vec<i32> = (0..50).collect();
        let guess: Vec<i32> = (0..50).rev().collect();
        let result = hit_and_blow(&answer, &guess);
        assert_eq!(result.0 + result.1, 50);
    }

    #[test]
    fn test_hit_and_blow_negative_values() {
        let answer = vec![-1, -2, -3];
        let guess = vec![-3, -2, -1];
        let result = hit_and_blow(&answer, &guess);
        assert_eq!(result, (1, 2));
    }

    #[test]
    fn test_hit_and_blow_complex_pattern() {
        let answer = vec![1, 1, 2, 3];
        let guess = vec![3, 2, 1, 1];
        let result = hit_and_blow(&answer, &guess);
        assert_eq!(result, (0, 4));
    }

    #[test]
    fn test_hit_and_blow_duplicates() {
        let answer = vec![1, 1, 1, 1];
        let guess = vec![1, 2, 3, 4];
        let result = hit_and_blow(&answer, &guess);
        assert_eq!(result, (1, 0));
    }

    #[test]
    fn test_hit_and_blow_partial_match() {
        let answer = vec![1, 2, 3, 4, 5];
        let guess = vec![1, 5, 4, 3, 2];
        let result = hit_and_blow(&answer, &guess);
        assert_eq!(result, (1, 4));
    }
}