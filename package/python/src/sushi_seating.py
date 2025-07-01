from typing import List
from collections import deque


def sushi_seating(commands: List[str]) -> List[str]:
    """
    回転寿司屋の席案内システムを実装する関数。

    Args:
        commands: 到着・席案内のコマンドリスト

    Returns:
        案内されたお客さんのリスト
    """
    waiting_queue: deque[str] = deque()  # FIFO queue for waiting customers
    seated_customers = []  # List of seated customers in order

    for command in commands:
        parts = command.split()

        if parts[0] == "arrive":
            name = parts[1]
            # Add to queue only if not already in queue
            if name not in waiting_queue:
                waiting_queue.append(name)

        elif parts[0] == "seat":
            n = int(parts[1])
            # Seat up to n customers from the front of the queue
            for _ in range(min(n, len(waiting_queue))):
                customer = waiting_queue.popleft()
                seated_customers.append(customer)

    return seated_customers
