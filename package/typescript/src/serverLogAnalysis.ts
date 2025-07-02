/**
 * Filter logs to return only requests with status code 200.
 *
 * @param logs - List of tuples [IP address, request, statusCode]
 * @returns List of logs with status code 200
 */
export function filterSuccessfulRequests(
  logs: Array<[string, string, number]>
): Array<[string, string, number]> {
  return logs.filter(log => log[2] === 200);
}

/**
 * Count the number of requests per IP address.
 *
 * @param logs - List of tuples [IP address, request, statusCode]
 * @returns Map mapping IP address to request count
 */
export function countRequestsByIp(logs: Array<[string, string, number]>): Map<string, number> {
  const ipCounts = new Map<string, number>();

  for (const log of logs) {
    const ipAddress = log[0];
    ipCounts.set(ipAddress, (ipCounts.get(ipAddress) || 0) + 1);
  }

  return ipCounts;
}
