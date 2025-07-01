from typing import List, Tuple, Dict


def filter_successful_requests(
    logs: List[Tuple[str, str, int]],
) -> List[Tuple[str, str, int]]:
    """
    Filter logs to return only requests with status code 200.

    Args:
        logs: List of tuples (IP address, request, status_code)

    Returns:
        List of logs with status code 200
    """
    return [log for log in logs if log[2] == 200]


def count_requests_by_ip(logs: List[Tuple[str, str, int]]) -> Dict[str, int]:
    """
    Count the number of requests per IP address.

    Args:
        logs: List of tuples (IP address, request, status_code)

    Returns:
        Dictionary mapping IP address to request count
    """
    ip_counts: Dict[str, int] = {}
    for log in logs:
        ip_address = log[0]
        ip_counts[ip_address] = ip_counts.get(ip_address, 0) + 1
    return ip_counts
