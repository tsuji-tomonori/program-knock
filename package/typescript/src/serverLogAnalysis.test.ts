import { filterSuccessfulRequests, countRequestsByIp } from './serverLogAnalysis';

describe('ServerLogAnalysis', () => {
  describe('filterSuccessfulRequests', () => {
    test('sample', () => {
      const logs: Array<[string, string, number]> = [
        ["192.168.0.1", "GET /index.html", 200],
        ["192.168.0.2", "POST /login", 401],
        ["192.168.0.1", "GET /about.html", 200],
        ["192.168.0.3", "GET /contact.html", 404],
      ];
      const expected = [
        ["192.168.0.1", "GET /index.html", 200],
        ["192.168.0.1", "GET /about.html", 200],
      ];
      expect(filterSuccessfulRequests(logs)).toEqual(expected);
    });

    test('empty', () => {
      const logs: Array<[string, string, number]> = [];
      expect(filterSuccessfulRequests(logs)).toEqual([]);
    });

    test('none_successful', () => {
      const logs: Array<[string, string, number]> = [
        ["192.168.0.1", "GET /missing", 404],
        ["192.168.0.2", "POST /login", 401],
        ["192.168.0.3", "GET /error", 500],
      ];
      expect(filterSuccessfulRequests(logs)).toEqual([]);
    });

    test('all_successful', () => {
      const logs: Array<[string, string, number]> = [
        ["192.168.0.1", "GET /index.html", 200],
        ["192.168.0.2", "GET /about.html", 200],
        ["192.168.0.3", "POST /submit", 200],
      ];
      expect(filterSuccessfulRequests(logs)).toEqual(logs);
    });
  });

  describe('countRequestsByIp', () => {
    test('sample', () => {
      const logs: Array<[string, string, number]> = [
        ["192.168.0.1", "GET /index.html", 200],
        ["192.168.0.2", "POST /login", 401],
        ["192.168.0.1", "GET /about.html", 200],
        ["192.168.0.3", "GET /contact.html", 404],
        ["192.168.0.1", "POST /submit", 500],
        ["192.168.0.2", "GET /dashboard", 200],
      ];
      const expected = new Map([
        ["192.168.0.1", 3],
        ["192.168.0.2", 2],
        ["192.168.0.3", 1]
      ]);
      expect(countRequestsByIp(logs)).toEqual(expected);
    });

    test('empty', () => {
      const logs: Array<[string, string, number]> = [];
      expect(countRequestsByIp(logs)).toEqual(new Map());
    });

    test('single_ip', () => {
      const logs: Array<[string, string, number]> = [
        ["192.168.0.1", "GET /page1", 200],
        ["192.168.0.1", "GET /page2", 404],
        ["192.168.0.1", "POST /submit", 500],
      ];
      const expected = new Map([["192.168.0.1", 3]]);
      expect(countRequestsByIp(logs)).toEqual(expected);
    });

    test('single_request', () => {
      const logs: Array<[string, string, number]> = [["192.168.0.1", "GET /index.html", 200]];
      const expected = new Map([["192.168.0.1", 1]]);
      expect(countRequestsByIp(logs)).toEqual(expected);
    });
  });

  describe('integration tests', () => {
    test('filter_and_count_integration', () => {
      const logs: Array<[string, string, number]> = [
        ["192.168.0.1", "GET /index.html", 200],
        ["192.168.0.2", "POST /login", 401],
        ["192.168.0.1", "GET /about.html", 200],
        ["192.168.0.3", "GET /contact.html", 404],
        ["192.168.0.1", "POST /submit", 500],
        ["192.168.0.2", "GET /dashboard", 200],
      ];

      // Test filtering first
      const successful = filterSuccessfulRequests(logs);
      const expectedSuccessful = [
        ["192.168.0.1", "GET /index.html", 200],
        ["192.168.0.1", "GET /about.html", 200],
        ["192.168.0.2", "GET /dashboard", 200],
      ];
      expect(successful).toEqual(expectedSuccessful);

      // Test counting on successful requests
      const successfulCounts = countRequestsByIp(successful);
      const expectedCounts = new Map([
        ["192.168.0.1", 2],
        ["192.168.0.2", 1]
      ]);
      expect(successfulCounts).toEqual(expectedCounts);
    });

    test('various_status_codes', () => {
      const logs: Array<[string, string, number]> = [
        ["10.0.0.1", "GET /", 200],
        ["10.0.0.1", "GET /missing", 404],
        ["10.0.0.2", "POST /login", 401],
        ["10.0.0.2", "GET /admin", 403],
        ["10.0.0.3", "POST /upload", 500],
        ["10.0.0.3", "GET /api", 200],
      ];

      const successful = filterSuccessfulRequests(logs);
      expect(successful).toHaveLength(2);
      expect(successful.every(log => log[2] === 200)).toBe(true);

      const counts = countRequestsByIp(logs);
      const expectedCounts = new Map([
        ["10.0.0.1", 2],
        ["10.0.0.2", 2],
        ["10.0.0.3", 2]
      ]);
      expect(counts).toEqual(expectedCounts);
    });

    test('large_dataset', () => {
      // Create a larger dataset for performance testing
      const logs: Array<[string, string, number]> = [];
      for (let i = 0; i < 1000; i++) {
        const ip = `192.168.0.${i % 10}`;
        const request = `GET /page${i}`;
        const status = i % 3 === 0 ? 200 : 404;
        logs.push([ip, request, status]);
      }

      const successful = filterSuccessfulRequests(logs);
      expect(successful).toHaveLength(334); // 1000 / 3 + 1 (0-indexed)

      const counts = countRequestsByIp(logs);
      expect(counts.size).toBe(10); // 10 unique IPs
      expect(Array.from(counts.values()).every(count => count === 100)).toBe(true);
    });
  });
});
