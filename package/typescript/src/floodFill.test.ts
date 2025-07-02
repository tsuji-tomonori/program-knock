import { floodFill } from './floodFill';

describe('floodFill', () => {
  test('sample 1', () => {
    const image = [[1, 1, 0], [1, 0, 1], [0, 1, 1]];
    const sr = 1, sc = 2, newColor = 0;
    const result = floodFill(image, sr, sc, newColor);
    const expected = [[1, 1, 0], [1, 0, 0], [0, 0, 0]];
    expect(result).toEqual(expected);
  });

  test('sample 2', () => {
    const image = [[1, 1, 1], [1, 1, 0], [1, 0, 1]];
    const sr = 1, sc = 1, newColor = 2;
    const result = floodFill(image, sr, sc, newColor);
    const expected = [[2, 2, 2], [2, 2, 0], [2, 0, 1]];
    expect(result).toEqual(expected);
  });

  test('sample 3', () => {
    const image = [[0, 0, 0], [0, 1, 1]];
    const sr = 1, sc = 1, newColor = 1;
    const result = floodFill(image, sr, sc, newColor);
    const expected = [[0, 0, 0], [0, 1, 1]]; // 変更なし
    expect(result).toEqual(expected);
  });

  test('single cell', () => {
    const image = [[1]];
    const result = floodFill(image, 0, 0, 0);
    const expected = [[0]];
    expect(result).toEqual(expected);
  });

  test('single cell no change', () => {
    const image = [[1]];
    const result = floodFill(image, 0, 0, 1);
    const expected = [[1]];
    expect(result).toEqual(expected);
  });

  test('fill entire grid', () => {
    const image = [[1, 1, 1], [1, 1, 1], [1, 1, 1]];
    const result = floodFill(image, 1, 1, 0);
    const expected = [[0, 0, 0], [0, 0, 0], [0, 0, 0]];
    expect(result).toEqual(expected);
  });

  test('no connected region', () => {
    const image = [[0, 1, 0], [1, 0, 1], [0, 1, 0]];
    const result = floodFill(image, 1, 1, 1);
    const expected = [[0, 1, 0], [1, 1, 1], [0, 1, 0]];
    expect(result).toEqual(expected);
  });

  test('corner cell', () => {
    const image = [[1, 0, 0], [0, 0, 0], [0, 0, 1]];
    const result = floodFill(image, 0, 0, 2);
    const expected = [[2, 0, 0], [0, 0, 0], [0, 0, 1]];
    expect(result).toEqual(expected);
  });

  test('l shaped region', () => {
    const image = [[1, 1, 0], [1, 0, 0], [1, 0, 1]];
    const result = floodFill(image, 0, 0, 2);
    const expected = [[2, 2, 0], [2, 0, 0], [2, 0, 1]];
    expect(result).toEqual(expected);
  });

  test('complex pattern', () => {
    const image = [[1, 0, 1, 1], [0, 1, 1, 0], [1, 1, 0, 1], [1, 0, 1, 1]];
    const result = floodFill(image, 1, 1, 2);
    const expected = [[1, 0, 2, 2], [0, 2, 2, 0], [2, 2, 0, 1], [2, 0, 1, 1]];
    expect(result).toEqual(expected);
  });

  test('rectangular grid', () => {
    const image = [[1, 1, 1, 1, 1], [0, 0, 0, 0, 0]];
    const result = floodFill(image, 0, 2, 0);
    const expected = [[0, 0, 0, 0, 0], [0, 0, 0, 0, 0]];
    expect(result).toEqual(expected);
  });

  test('original image not modified', () => {
    const original = [[1, 1, 0], [1, 0, 1]];
    const imageCopy = original.map(row => [...row]);
    floodFill(imageCopy, 0, 0, 2);
    // Original should remain unchanged
    expect(original).toEqual([[1, 1, 0], [1, 0, 1]]);
  });
});
