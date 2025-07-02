from src.server_log_analysis import count_requests_by_ip, filter_successful_requests


class TestServerLogAnalysis:
    def test_filter_successful_requests_sample(self):
        logs = [
            ("192.168.0.1", "GET /index.html", 200),
            ("192.168.0.2", "POST /login", 401),
            ("192.168.0.1", "GET /about.html", 200),
            ("192.168.0.3", "GET /contact.html", 404),
        ]
        expected = [
            ("192.168.0.1", "GET /index.html", 200),
            ("192.168.0.1", "GET /about.html", 200),
        ]
        assert filter_successful_requests(logs) == expected

    def test_filter_successful_requests_empty(self):
        logs = []
        assert filter_successful_requests(logs) == []

    def test_filter_successful_requests_none_successful(self):
        logs = [
            ("192.168.0.1", "GET /missing", 404),
            ("192.168.0.2", "POST /login", 401),
            ("192.168.0.3", "GET /error", 500),
        ]
        assert filter_successful_requests(logs) == []

    def test_filter_successful_requests_all_successful(self):
        logs = [
            ("192.168.0.1", "GET /index.html", 200),
            ("192.168.0.2", "GET /about.html", 200),
            ("192.168.0.3", "POST /submit", 200),
        ]
        assert filter_successful_requests(logs) == logs

    def test_count_requests_by_ip_sample(self):
        logs = [
            ("192.168.0.1", "GET /index.html", 200),
            ("192.168.0.2", "POST /login", 401),
            ("192.168.0.1", "GET /about.html", 200),
            ("192.168.0.3", "GET /contact.html", 404),
            ("192.168.0.1", "POST /submit", 500),
            ("192.168.0.2", "GET /dashboard", 200),
        ]
        expected = {"192.168.0.1": 3, "192.168.0.2": 2, "192.168.0.3": 1}
        assert count_requests_by_ip(logs) == expected

    def test_count_requests_by_ip_empty(self):
        logs = []
        assert count_requests_by_ip(logs) == {}

    def test_count_requests_by_ip_single_ip(self):
        logs = [
            ("192.168.0.1", "GET /page1", 200),
            ("192.168.0.1", "GET /page2", 404),
            ("192.168.0.1", "POST /submit", 500),
        ]
        expected = {"192.168.0.1": 3}
        assert count_requests_by_ip(logs) == expected

    def test_count_requests_by_ip_single_request(self):
        logs = [("192.168.0.1", "GET /index.html", 200)]
        expected = {"192.168.0.1": 1}
        assert count_requests_by_ip(logs) == expected

    def test_filter_and_count_integration(self):
        logs = [
            ("192.168.0.1", "GET /index.html", 200),
            ("192.168.0.2", "POST /login", 401),
            ("192.168.0.1", "GET /about.html", 200),
            ("192.168.0.3", "GET /contact.html", 404),
            ("192.168.0.1", "POST /submit", 500),
            ("192.168.0.2", "GET /dashboard", 200),
        ]

        # Test filtering first
        successful = filter_successful_requests(logs)
        expected_successful = [
            ("192.168.0.1", "GET /index.html", 200),
            ("192.168.0.1", "GET /about.html", 200),
            ("192.168.0.2", "GET /dashboard", 200),
        ]
        assert successful == expected_successful

        # Test counting on successful requests
        successful_counts = count_requests_by_ip(successful)
        expected_counts = {"192.168.0.1": 2, "192.168.0.2": 1}
        assert successful_counts == expected_counts

    def test_various_status_codes(self):
        logs = [
            ("10.0.0.1", "GET /", 200),
            ("10.0.0.1", "GET /missing", 404),
            ("10.0.0.2", "POST /login", 401),
            ("10.0.0.2", "GET /admin", 403),
            ("10.0.0.3", "POST /upload", 500),
            ("10.0.0.3", "GET /api", 200),
        ]

        successful = filter_successful_requests(logs)
        assert len(successful) == 2
        assert all(log[2] == 200 for log in successful)

        counts = count_requests_by_ip(logs)
        expected_counts = {"10.0.0.1": 2, "10.0.0.2": 2, "10.0.0.3": 2}
        assert counts == expected_counts

    def test_large_dataset(self):
        # Create a larger dataset for performance testing
        logs = []
        for i in range(1000):
            ip = f"192.168.0.{i % 10}"
            request = f"GET /page{i}"
            status = 200 if i % 3 == 0 else 404
            logs.append((ip, request, status))

        successful = filter_successful_requests(logs)
        assert len(successful) == 334  # 1000 / 3 + 1 (0-indexed)

        counts = count_requests_by_ip(logs)
        assert len(counts) == 10  # 10 unique IPs
        assert all(count == 100 for count in counts.values())
