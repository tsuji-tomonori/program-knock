import { sushiSeating } from './sushiSeating';

describe('SushiSeating', () => {
  test('sample_1', () => {
    const commands = [
      "arrive Alice",
      "arrive Bob",
      "seat 1",
      "arrive Charlie",
      "seat 2",
      "arrive Dave",
      "arrive Eve",
      "seat 3",
    ];
    const expected = ["Alice", "Bob", "Charlie", "Dave", "Eve"];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('sample_2', () => {
    const commands = [
      "arrive Tom",
      "arrive Jerry",
      "arrive Spike",
      "seat 2",
      "arrive Butch",
      "seat 2",
    ];
    const expected = ["Tom", "Jerry", "Spike", "Butch"];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('sample_3', () => {
    const commands = ["arrive Anna", "arrive Elsa", "seat 5"];
    const expected = ["Anna", "Elsa"];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('empty_commands', () => {
    const commands: string[] = [];
    const expected: string[] = [];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('only_arrivals', () => {
    const commands = ["arrive Alice", "arrive Bob", "arrive Charlie"];
    const expected: string[] = [];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('only_seating', () => {
    const commands = ["seat 1", "seat 2", "seat 5"];
    const expected: string[] = [];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('seat_zero', () => {
    const commands = ["arrive Alice", "seat 0", "seat 1"];
    const expected = ["Alice"];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('duplicate_arrivals_ignored', () => {
    const commands = [
      "arrive Alice",
      "arrive Alice", // Should be ignored
      "arrive Bob",
      "seat 2",
    ];
    const expected = ["Alice", "Bob"];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('multiple_seat_commands', () => {
    const commands = [
      "arrive A",
      "arrive B",
      "arrive C",
      "arrive D",
      "seat 1",
      "seat 1",
      "seat 1",
      "seat 1",
    ];
    const expected = ["A", "B", "C", "D"];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('mixed_operations', () => {
    const commands = [
      "arrive John",
      "seat 1",
      "arrive Jane",
      "arrive Jack",
      "seat 1",
      "arrive Jill",
      "seat 2",
    ];
    const expected = ["John", "Jane", "Jack", "Jill"];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('large_seat_number', () => {
    const commands = [
      "arrive A",
      "arrive B",
      "seat 100", // Much larger than queue size
    ];
    const expected = ["A", "B"];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('fifo_order_maintained', () => {
    const commands = [
      "arrive First",
      "arrive Second",
      "arrive Third",
      "arrive Fourth",
      "seat 2",
      "arrive Fifth",
      "seat 3",
    ];
    const expected = ["First", "Second", "Third", "Fourth", "Fifth"];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('large_performance', () => {
    // 大規模パフォーマンステスト
    // 1000人の顧客と多数の席案内をテスト
    const commands: string[] = [];
    // 1000人の顧客が到着
    for (let i = 0; i < 1000; i++) {
      commands.push(`arrive Customer${i.toString().padStart(4, '0')}`);
    }

    // 100人ずつ10回に分けて席案内
    for (let i = 0; i < 10; i++) {
      commands.push("seat 100");
    }

    const result = sushiSeating(commands);

    // 全ての顧客が正しい順序で席に案内されていることを確認
    const expected = Array.from({ length: 1000 }, (_, i) =>
      `Customer${i.toString().padStart(4, '0')}`
    );
    expect(result).toEqual(expected);
  });

  test('negative_seat_numbers', () => {
    // 負の席数での動作テスト
    const commands = [
      "arrive Alice",
      "arrive Bob",
      "seat -1", // 負の値
      "seat 0",  // ゼロ
      "seat 1",  // 正の値
    ];
    const expected = ["Alice"];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('very_long_customer_names', () => {
    // 非常に長い顧客名でのテスト
    const longName = "A".repeat(100); // 100文字の名前
    const commands = [
      `arrive ${longName}`,
      "arrive Bob",
      "seat 2",
    ];
    const expected = [longName, "Bob"];
    expect(sushiSeating(commands)).toEqual(expected);
  });

  test('special_characters_in_names', () => {
    // 特殊文字を含む顧客名でのテスト
    const commands = [
      "arrive Alice-123",
      "arrive Bob_456",
      "arrive Charlie.789",
      "seat 3",
    ];
    const expected = ["Alice-123", "Bob_456", "Charlie.789"];
    expect(sushiSeating(commands)).toEqual(expected);
  });
});
