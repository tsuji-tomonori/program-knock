import { calculateNewYearHoliday } from '../src/calculateNewYearHoliday';

describe('calculateNewYearHoliday', () => {
  test('sample 1 - year 2025', () => {
    const result = calculateNewYearHoliday(2025);
    const expected: [string, string, number] = ["2024-12-28", "2025-01-05", 9];
    expect(result).toEqual(expected);
  });

  test('sample 2 - year 2026', () => {
    const result = calculateNewYearHoliday(2026);
    const expected: [string, string, number] = ["2025-12-27", "2026-01-04", 9];
    expect(result).toEqual(expected);
  });

  test('year 2023', () => {
    const result = calculateNewYearHoliday(2023);
    const expected: [string, string, number] = ["2022-12-29", "2023-01-03", 6];
    expect(result).toEqual(expected);
  });

  test('year 2024', () => {
    const result = calculateNewYearHoliday(2024);
    const expected: [string, string, number] = ["2023-12-29", "2024-01-03", 6];
    expect(result).toEqual(expected);
  });

  test('year 2027', () => {
    const result = calculateNewYearHoliday(2027);
    const expected: [string, string, number] = ["2026-12-29", "2027-01-03", 6];
    expect(result).toEqual(expected);
  });

  test('year 2021', () => {
    const result = calculateNewYearHoliday(2021);
    const expected: [string, string, number] = ["2020-12-29", "2021-01-03", 6];
    expect(result).toEqual(expected);
  });

  test('year 2020', () => {
    const result = calculateNewYearHoliday(2020);
    const expected: [string, string, number] = ["2019-12-28", "2020-01-05", 9];
    expect(result).toEqual(expected);
  });

  test('year 2019', () => {
    const result = calculateNewYearHoliday(2019);
    const expected: [string, string, number] = ["2018-12-29", "2019-01-03", 6];
    expect(result).toEqual(expected);
  });

  test('year 2000', () => {
    const result = calculateNewYearHoliday(2000);
    const expected: [string, string, number] = ["1999-12-29", "2000-01-03", 6];
    expect(result).toEqual(expected);
  });

  test('year 2100', () => {
    const result = calculateNewYearHoliday(2100);
    const expected: [string, string, number] = ["2099-12-29", "2100-01-03", 6];
    expect(result).toEqual(expected);
  });

  test('output format validation', () => {
    const result = calculateNewYearHoliday(2025);
    const [startDate, endDate, days] = result;

    const datePattern = /^\d{4}-\d{2}-\d{2}$/;
    expect(datePattern.test(startDate)).toBe(true);
    expect(datePattern.test(endDate)).toBe(true);
    expect(typeof days).toBe('number');
    expect(Number.isInteger(days)).toBe(true);
  });
});
