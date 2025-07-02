import { nextGeneration } from './lifeGame';

describe('nextGeneration', () => {
  test('empty board', () => {
    expect(nextGeneration([])).toEqual([]);
  });

  test('single cell dies', () => {
    const board = [[1]];
    const expected = [[0]];
    expect(nextGeneration(board)).toEqual(expected);
  });

  test('block pattern (stable)', () => {
    const board = [
      [0, 0, 0, 0],
      [0, 1, 1, 0],
      [0, 1, 1, 0],
      [0, 0, 0, 0]
    ];
    const expected = [
      [0, 0, 0, 0],
      [0, 1, 1, 0],
      [0, 1, 1, 0],
      [0, 0, 0, 0]
    ];
    expect(nextGeneration(board)).toEqual(expected);
  });

  test('blinker pattern (period 2)', () => {
    const board = [
      [0, 0, 0],
      [1, 1, 1],
      [0, 0, 0]
    ];
    const expected = [
      [0, 1, 0],
      [0, 1, 0],
      [0, 1, 0]
    ];
    expect(nextGeneration(board)).toEqual(expected);
  });
});
