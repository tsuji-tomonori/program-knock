pub mod calc_cpk;
pub mod age_statistics;
pub mod langtons_ant;
pub mod life_game;
pub mod calculate_connections;
pub mod kmeans_clustering;
pub mod count_words;
pub mod lru_cache;
pub mod aggregate_sales;
pub mod server_log_analysis;
pub mod remove_duplicates;
pub mod deduplicate_products;
pub mod scope_manager;
pub mod restaurant_seating;
pub mod markdown_to_html;
pub mod fake_chinese_checker;
pub mod year_end_holiday;
pub mod room_reservation;
pub mod run_length_encoding;
pub mod hit_and_blow;
pub mod find_closest_value;
pub mod count_in_range;
pub mod suggest_aws_service;
pub mod flood_fill;

pub use calc_cpk::calc_cpk;
pub use age_statistics::age_statistics;
pub use langtons_ant::langtons_ant;
pub use life_game::next_generation;
pub use calculate_connections::calculate_connections;
pub use kmeans_clustering::kmeans_clustering;
pub use count_words::count_words;
pub use lru_cache::LRUCache;
pub use aggregate_sales::aggregate_sales;
pub use server_log_analysis::{filter_successful_requests, count_requests_by_ip};
pub use remove_duplicates::remove_duplicates;
pub use deduplicate_products::deduplicate_products;
pub use scope_manager::ScopeManager;
pub use restaurant_seating::restaurant_seating;
pub use markdown_to_html::markdown_to_html;
pub use fake_chinese_checker::is_fake_chinese;
pub use year_end_holiday::year_end_holiday;
pub use room_reservation::RoomReservation;
pub use run_length_encoding::run_length_encoding;
pub use hit_and_blow::hit_and_blow;
pub use find_closest_value::find_closest_value;
pub use count_in_range::count_in_range;
pub use suggest_aws_service::suggest_aws_service;
pub use flood_fill::flood_fill;
