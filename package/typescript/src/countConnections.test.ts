import { countConnections, Log, CountConnectionsParam } from './countConnections';

describe('countConnections', () => {
  test('basic test case', () => {
    /**
     * 基本的なテストケース。
     * 時刻ごとの接続数をテストする。
     * endTime=5, period=1 で複数のログを使用して接続数を集計。
     * 結果として [3, 5, 5, 5, 8, 6] が期待される。
     */
    const param: CountConnectionsParam = {
      endTime: 5,
      period: 1,
      logs: [
        { time: 0, nConnect: 3, nDisconnect: 0 },
        { time: 1, nConnect: 2, nDisconnect: 0 },
        { time: 4, nConnect: 5, nDisconnect: 2 },
        { time: 5, nConnect: 3, nDisconnect: 5 },
      ],
    };
    expect(countConnections(param)).toEqual([3, 5, 5, 5, 8, 6]);
  });

  test('no logs', () => {
    /**
     * ログが存在しない場合のテスト。
     * すべての接続数が 0 であることを確認。
     */
    const param: CountConnectionsParam = { endTime: 3, period: 1, logs: [] };
    expect(countConnections(param)).toEqual([0, 0, 0, 0]);
  });

  test('gap logs', () => {
    /**
     * ログの間隔にギャップがある場合のテスト。
     * endTime=6, period=2 で複数のログを使用して接続数を集計。
     * 結果として [0, 4, 4, 5] が期待される。
     */
    const param: CountConnectionsParam = {
      endTime: 6,
      period: 2,
      logs: [
        { time: 1, nConnect: 4, nDisconnect: 0 },
        { time: 3, nConnect: 1, nDisconnect: 1 },
        { time: 6, nConnect: 3, nDisconnect: 2 },
      ],
    };
    expect(countConnections(param)).toEqual([0, 4, 4, 5]);
  });

  test('single log', () => {
    /**
     * 単一のログが存在する場合のテスト。
     * endTime=5, period=1 で1つのログのみ使用し、接続数を集計。
     * 結果として [5, 5, 5, 5, 5, 5] が期待される。
     */
    const param: CountConnectionsParam = {
      endTime: 5,
      period: 1,
      logs: [
        { time: 0, nConnect: 5, nDisconnect: 0 },
      ],
    };
    expect(countConnections(param)).toEqual([5, 5, 5, 5, 5, 5]);
  });

  test('all disconnect', () => {
    /**
     * すべての接続が切断される場合のテスト。
     * 時刻 2 で全ての接続が切断され、結果として [10, 10, 0, 0, 0] が期待される。
     */
    const param: CountConnectionsParam = {
      endTime: 4,
      period: 1,
      logs: [
        { time: 0, nConnect: 10, nDisconnect: 0 },
        { time: 2, nConnect: 0, nDisconnect: 10 },
      ],
    };
    expect(countConnections(param)).toEqual([10, 10, 0, 0, 0]);
  });

  test('partial disconnect', () => {
    /**
     * 部分的に切断が行われる場合のテスト。
     * endTime=4, period=1 で部分的に接続が切断される状況をテスト。
     * 結果として [10, 10, 5, 5, 5] が期待される。
     */
    const param: CountConnectionsParam = {
      endTime: 4,
      period: 1,
      logs: [
        { time: 0, nConnect: 10, nDisconnect: 0 },
        { time: 2, nConnect: 0, nDisconnect: 5 },
      ],
    };
    expect(countConnections(param)).toEqual([10, 10, 5, 5, 5]);
  });

  test('large connect and disconnect', () => {
    /**
     * 非常に多くの接続が行われ、その後すべて切断されるケースのテスト。
     * endTime=100, period=10 で一気に大量の接続があり、その後すべて切断される状況をテスト。
     * 最終的にすべての接続が切断され、結果として [0, 10**10, 10**10, 10**10, 0, 0, 0, 0, 0, 0, 0] が期待される。
     */
    const param: CountConnectionsParam = {
      endTime: 100,
      period: 10,
      logs: [
        { time: 10, nConnect: 10 ** 10, nDisconnect: 0 },
        { time: 40, nConnect: 0, nDisconnect: 10 ** 10 },
      ],
    };
    expect(countConnections(param)).toEqual([0, 10 ** 10, 10 ** 10, 10 ** 10, 0, 0, 0, 0, 0, 0, 0]);
  });

  test('output 1000 entries', () => {
    /**
     * 上限のテスト。
     * endTime=1000, period=1 で各時刻ごとに出力が行われるケースをテスト。
     * すべての時間に対する接続数が正しく計算されているかを確認。
     */
    const logs: Log[] = [];
    for (let i = 0; i <= 1000; i++) {
      logs.push({ time: i, nConnect: 1, nDisconnect: 0 });
    }
    const param: CountConnectionsParam = { endTime: 1000, period: 1, logs };
    const expected = [];
    for (let i = 0; i <= 1000; i++) {
      expected.push(i + 1);
    }
    expect(countConnections(param)).toEqual(expected);
  });

  test('boundary period equals end time', () => {
    /**
     * period が end_time と等しい場合のテスト。
     * endTime=10, period=10 の場合、結果として [10, 10] が期待される。
     */
    const param: CountConnectionsParam = {
      endTime: 10,
      period: 10,
      logs: [
        { time: 0, nConnect: 10, nDisconnect: 0 },
      ],
    };
    expect(countConnections(param)).toEqual([10, 10]);
  });

  test('overlapping connections and disconnections', () => {
    /**
     * 接続と切断が重複して発生する場合のテスト。
     * 同じ時刻で接続と切断が同時に発生する状況をテスト。
     */
    const param: CountConnectionsParam = {
      endTime: 5,
      period: 1,
      logs: [
        { time: 0, nConnect: 5, nDisconnect: 0 },
        { time: 2, nConnect: 3, nDisconnect: 2 }, // 時刻2で3接続、2切断
        { time: 4, nConnect: 0, nDisconnect: 6 }, // 時刻4で全て切断
      ],
    };
    expect(countConnections(param)).toEqual([5, 5, 6, 6, 0, 0]);
  });
});
