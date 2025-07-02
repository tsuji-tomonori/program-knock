import { hitAndBlow } from '../src/hitAndBlow';

describe('hitAndBlow', () => {
  test('case 1', () => {
    expect(hitAndBlow([1, 2, 3, 4], [1, 3, 2, 5])).toEqual([1, 2]);
  });

  test('case 2', () => {
    expect(hitAndBlow([5, 6, 7, 8], [8, 7, 6, 5])).toEqual([0, 4]);
  });

  test('case 3', () => {
    expect(hitAndBlow([3, 1, 4, 7], [3, 1, 4, 7])).toEqual([4, 0]);
  });

  test('case 4', () => {
    expect(hitAndBlow([9, 8, 7, 6], [1, 2, 3, 4])).toEqual([0, 0]);
  });

  test('single digit', () => {
    expect(hitAndBlow([5], [5])).toEqual([1, 0]);
    expect(hitAndBlow([5], [3])).toEqual([0, 0]);
  });

  test('two digits', () => {
    expect(hitAndBlow([1, 2], [2, 1])).toEqual([0, 2]);
    expect(hitAndBlow([1, 2], [1, 3])).toEqual([1, 0]);
    expect(hitAndBlow([1, 2], [3, 2])).toEqual([1, 0]);
  });

  test('partial match', () => {
    expect(hitAndBlow([1, 2, 3], [1, 5, 2])).toEqual([1, 1]);
    expect(hitAndBlow([1, 2, 3], [4, 1, 5])).toEqual([0, 1]);
  });

  test('all hits', () => {
    expect(hitAndBlow([0, 1, 2, 3], [0, 1, 2, 3])).toEqual([4, 0]);
    expect(hitAndBlow([9, 8, 7], [9, 8, 7])).toEqual([3, 0]);
  });

  test('all blows', () => {
    expect(hitAndBlow([1, 2, 3], [3, 1, 2])).toEqual([0, 3]);
    expect(hitAndBlow([1, 2], [2, 1])).toEqual([0, 2]);
  });

  test('no matches', () => {
    expect(hitAndBlow([1, 2, 3], [4, 5, 6])).toEqual([0, 0]);
    expect(hitAndBlow([0, 9], [1, 8])).toEqual([0, 0]);
  });

  test('mixed scenario', () => {
    expect(hitAndBlow([1, 2, 3, 4, 5], [1, 5, 6, 4, 7])).toEqual([2, 1]);
    expect(hitAndBlow([0, 1, 2, 3], [0, 2, 1, 4])).toEqual([1, 2]);
  });

  test('edge case zeros', () => {
    expect(hitAndBlow([0, 0, 0], [0, 0, 0])).toEqual([3, 0]);
    expect(hitAndBlow([0, 1, 2], [0, 2, 1])).toEqual([1, 2]);
  });

  test('longer sequence', () => {
    expect(hitAndBlow([1, 2, 3, 4, 5, 6], [1, 6, 2, 3, 4, 5])).toEqual([1, 5]);
    expect(hitAndBlow([0, 1, 2, 3, 4, 5], [5, 4, 3, 2, 1, 0])).toEqual([0, 6]);
  });

  test('one hit multiple blows', () => {
    expect(hitAndBlow([1, 2, 3, 4], [1, 4, 2, 5])).toEqual([1, 2]);
    expect(hitAndBlow([9, 8, 7, 6], [9, 6, 8, 5])).toEqual([1, 2]);
  });
});
