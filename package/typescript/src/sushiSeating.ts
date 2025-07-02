/**
 * 回転寿司屋の席案内システムを実装する関数。
 *
 * @param commands - 到着・席案内のコマンドリスト
 * @returns 案内されたお客さんのリスト
 */
export function sushiSeating(commands: string[]): string[] {
  const waitingQueue: string[] = []; // FIFO queue for waiting customers
  const seatedCustomers: string[] = []; // List of seated customers in order

  for (const command of commands) {
    const parts = command.split(' ');

    if (parts[0] === 'arrive') {
      const name = parts[1];
      // Add to queue only if not already in queue
      if (!waitingQueue.includes(name)) {
        waitingQueue.push(name);
      }
    } else if (parts[0] === 'seat') {
      const n = parseInt(parts[1], 10);
      // Seat up to n customers from the front of the queue
      const customersToSeat = Math.min(n, waitingQueue.length);
      for (let i = 0; i < customersToSeat; i++) {
        const customer = waitingQueue.shift()!;
        seatedCustomers.push(customer);
      }
    }
  }

  return seatedCustomers;
}
