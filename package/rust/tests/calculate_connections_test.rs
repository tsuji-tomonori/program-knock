use program_knock::calculate_connections::*;

#[test]
fn test_calculate_connections_sample_1() {
    let logs = vec![
        Log { time: 0, n_connect: 3, n_disconnect: 0 },
        Log { time: 1, n_connect: 2, n_disconnect: 0 },
        Log { time: 4, n_connect: 5, n_disconnect: 2 },
        Log { time: 5, n_connect: 3, n_disconnect: 5 },
    ];
    let result = calculate_connections(5, 1, &logs);
    assert_eq!(result, vec![3, 5, 5, 5, 8, 6]);
}

#[test]
fn test_calculate_connections_sample_2() {
    let logs = vec![];
    let result = calculate_connections(3, 1, &logs);
    assert_eq!(result, vec![0, 0, 0, 0]);
}

#[test]
fn test_calculate_connections_sample_3() {
    let logs = vec![
        Log { time: 1, n_connect: 4, n_disconnect: 0 },
        Log { time: 3, n_connect: 1, n_disconnect: 1 },
        Log { time: 6, n_connect: 3, n_disconnect: 2 },
    ];
    let result = calculate_connections(6, 2, &logs);
    assert_eq!(result, vec![0, 4, 4, 5]);
}

#[test]
fn test_calculate_connections_minimum_time() {
    let logs = vec![
        Log { time: 0, n_connect: 5, n_disconnect: 0 },
    ];
    let result = calculate_connections(0, 1, &logs);
    assert_eq!(result, vec![5]);
}

#[test]
fn test_calculate_connections_maximum_period() {
    let logs = vec![
        Log { time: 0, n_connect: 3, n_disconnect: 0 },
        Log { time: 5, n_connect: 2, n_disconnect: 1 },
    ];
    let result = calculate_connections(10, 10, &logs);
    assert_eq!(result, vec![3, 4]);
}

#[test]
fn test_calculate_connections_drops_to_zero() {
    let logs = vec![
        Log { time: 0, n_connect: 5, n_disconnect: 0 },
        Log { time: 2, n_connect: 0, n_disconnect: 5 },
        Log { time: 4, n_connect: 3, n_disconnect: 0 },
    ];
    let result = calculate_connections(4, 1, &logs);
    assert_eq!(result, vec![5, 5, 0, 0, 3]);
}

#[test]
fn test_calculate_connections_simultaneous_changes() {
    let logs = vec![
        Log { time: 1, n_connect: 5, n_disconnect: 3 },
        Log { time: 3, n_connect: 2, n_disconnect: 4 },
    ];
    let result = calculate_connections(3, 1, &logs);
    assert_eq!(result, vec![0, 2, 2, 0]);
}

#[test]
fn test_calculate_connections_boundary_logs() {
    let logs = vec![
        Log { time: 10, n_connect: 5, n_disconnect: 2 },
    ];
    let result = calculate_connections(10, 5, &logs);
    assert_eq!(result, vec![0, 0, 3]);
}

#[test]
fn test_calculate_connections_non_continuous_logs() {
    let logs = vec![
        Log { time: 3, n_connect: 5, n_disconnect: 0 },
        Log { time: 7, n_connect: 3, n_disconnect: 1 },
        Log { time: 15, n_connect: 2, n_disconnect: 3 },
    ];
    let result = calculate_connections(20, 4, &logs);
    assert_eq!(result, vec![0, 5, 7, 7, 6, 6]);
}

#[test]
fn test_calculate_connections_large_changes() {
    let logs = vec![
        Log { time: 0, n_connect: 100, n_disconnect: 0 },
        Log { time: 2, n_connect: 50, n_disconnect: 80 },
        Log { time: 4, n_connect: 30, n_disconnect: 50 },
    ];
    let result = calculate_connections(5, 1, &logs);
    assert_eq!(result, vec![100, 100, 70, 70, 50, 50]);
}

#[test]
fn test_calculate_connections_small_period() {
    let logs = vec![
        Log { time: 1, n_connect: 3, n_disconnect: 0 },
        Log { time: 3, n_connect: 2, n_disconnect: 1 },
        Log { time: 6, n_connect: 4, n_disconnect: 2 },
        Log { time: 8, n_connect: 1, n_disconnect: 3 },
    ];
    let result = calculate_connections(8, 1, &logs);
    assert_eq!(result, vec![0, 3, 3, 4, 4, 4, 6, 6, 4]);
}

#[test]
fn test_calculate_connections_large_dataset() {
    let mut logs = vec![];
    for i in 0..500 {
        if i % 10 == 0 {
            logs.push(Log {
                time: i * 2,
                n_connect: (i % 7) + 1,
                n_disconnect: i % 5,
            });
        }
    }
    let result = calculate_connections(1000, 50, &logs);
    assert_eq!(result.len(), 21);
}

#[test]
fn test_calculate_connections_state_persistence() {
    let logs = vec![
        Log { time: 1, n_connect: 1, n_disconnect: 0 },
    ];
    let result = calculate_connections(10, 5, &logs);
    assert_eq!(result, vec![0, 1, 1]);
}

#[test]
fn test_calculate_connections_time_tracking() {
    let logs = vec![
        Log { time: 1, n_connect: 1, n_disconnect: 0 },
        Log { time: 10, n_connect: 1, n_disconnect: 0 },
        Log { time: 15, n_connect: 0, n_disconnect: 1 },
    ];
    let result = calculate_connections(20, 5, &logs);
    assert_eq!(result, vec![0, 1, 2, 1, 1]);
}
