interface Log {
  time: number; // 時刻 0 <= time <= 1000
  nConnect: number; // この時間に新規接続した端末の台数
  nDisconnect: number; // この時間に新規切断した端末の台数
}

interface CountConnectionsParam {
  endTime: number; // 集計を終了する時刻
  period: number; // 集計する間隔
  logs: Log[]; // ログ
}

/**
 * ある期間ごとの接続数を出力する関数.
 *
 * @param param - 接続情報
 * @returns ある期間ごとの接続数
 */
export function countConnections(param: CountConnectionsParam): number[] {
  const connect = new Array(param.endTime + 1).fill(0);
  const disconnect = new Array(param.endTime + 1).fill(0);

  for (const log of param.logs) {
    connect[log.time] = log.nConnect;
    disconnect[log.time] = log.nDisconnect;
  }

  const result: number[] = [];
  for (let t = 0; t <= param.endTime; t += param.period) {
    const totalConnect = connect.slice(0, t + 1).reduce((sum, val) => sum + val, 0);
    const totalDisconnect = disconnect.slice(0, t + 1).reduce((sum, val) => sum + val, 0);
    result.push(totalConnect - totalDisconnect);
  }

  return result;
}

export type { Log, CountConnectionsParam };
