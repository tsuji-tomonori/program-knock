#[derive(Debug, Clone)]
pub struct Log {
    pub time: i32,
    pub n_connect: i32,
    pub n_disconnect: i32,
}

pub fn calculate_connections(end_time: i32, period: i32, logs: &[Log]) -> Vec<i32> {
    let num_periods = (end_time / period) + 1;
    let mut result = vec![0; num_periods as usize];

    // Sort logs by time
    let mut sorted_logs = logs.to_vec();
    sorted_logs.sort_by_key(|log| log.time);

    let mut current_connections = 0;
    let mut log_index = 0;

    // Process each time unit
    for time in 0..=end_time {
        // Apply any logs at this time
        while log_index < sorted_logs.len() && sorted_logs[log_index].time == time {
            let log = &sorted_logs[log_index];
            current_connections += log.n_connect;
            current_connections -= log.n_disconnect;
            log_index += 1;
        }

        // Record connections at period intervals
        if time % period == 0 {
            let period_index = (time / period) as usize;
            result[period_index] = current_connections;
        }
    }

    result
}
