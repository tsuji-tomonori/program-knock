import { calcCpk } from '../src/calcCpk';

describe('calcCpk', () => {
  test('basic calculation sample 1', () => {
    const result = calcCpk({
      usl: 10.0,
      lsl: 2.0,
      data: [4.5, 5.0, 4.8, 5.2, 5.5]
    });
    expect(result).toBe(2.626);
  });

  test('symmetric data with small standard deviation', () => {
    const result = calcCpk({
      usl: 10,
      lsl: 0,
      data: [4.9999, 5.0, 5.0001, 5.0, 5.0]
    });
    expect(result).toBeGreaterThan(20000);
  });

  test('symmetric data with normal standard deviation', () => {
    const result = calcCpk({
      usl: 10,
      lsl: 0,
      data: [4, 5, 6, 5, 5]
    });
    expect(result).toBe(2.357);
  });

  test('data near lower specification limit', () => {
    const result = calcCpk({
      usl: 10,
      lsl: 2,
      data: [2.1, 2.2, 2.0, 2.1, 2.3]
    });
    expect(result).toBe(0.409);
  });

  test('data near upper specification limit', () => {
    const result = calcCpk({
      usl: 10,
      lsl: 2,
      data: [9.5, 9.7, 9.8, 9.9, 9.4]
    });
    expect(result).toBe(0.547);
  });

  test('large specification range', () => {
    const result = calcCpk({
      usl: 100,
      lsl: 0,
      data: [50, 55, 60, 45, 40]
    });
    expect(result).toBe(2.108);
  });

  test('narrow specification range', () => {
    const result = calcCpk({
      usl: 5,
      lsl: 4,
      data: [4.5, 4.3, 4.6, 4.2, 4.4]
    });
    expect(result).toBe(0.843);
  });

  test('minimum data with two points', () => {
    const result = calcCpk({
      usl: 10,
      lsl: 0,
      data: [4, 6]
    });
    expect(result).toBe(1.179);
  });

  test('k equals 1 case', () => {
    const result = calcCpk({
      usl: 10,
      lsl: 0,
      data: [9.9, 10.0, 10.1, 10.0, 10.0]
    });
    expect(result).toBe(0.0);
  });

  test('large dataset performance test', () => {
    const seedRandom = (seed: number) => {
      let state = seed;
      return () => {
        state = (state * 1664525 + 1013904223) % 2 ** 32;
        return (state / 2 ** 32) - 0.5;
      };
    };

    const random = seedRandom(42);
    const data = Array.from({ length: 10000 }, () => 5.0 + random() * 1.0);
    const result = calcCpk({
      usl: 10.0,
      lsl: 0.0,
      data
    });
    expect(result).toBeGreaterThanOrEqual(0.0);
    expect(result).toBeLessThanOrEqual(10.0);
  });

  test('extreme values test', () => {
    const result = calcCpk({
      usl: 1000000.0,
      lsl: -1000000.0,
      data: [500000.0, 500001.0, 499999.0, 500000.5, 499999.5]
    });
    expect(result).toBeGreaterThan(100000);
  });

  test('single data point throws error', () => {
    expect(() => {
      calcCpk({
        usl: 10.0,
        lsl: 0.0,
        data: [5.0]
      });
    }).toThrow('Data array must contain at least 2 elements for standard deviation calculation');
  });

  test('empty data array throws error', () => {
    expect(() => {
      calcCpk({
        usl: 10.0,
        lsl: 0.0,
        data: []
      });
    }).toThrow('Data array cannot be empty');
  });
});
