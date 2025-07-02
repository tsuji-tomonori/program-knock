import { analyzeAgeDistribution } from '../src/ageStatistics';

describe('ageStatistics', () => {
  test('sample 1', () => {
    const ages = [25, 30, 35, 40, 45, 50];
    expect(analyzeAgeDistribution(ages)).toEqual([50, 25, 37.5, 3]);
  });

  test('sample 2', () => {
    const ages = [18, 22, 22, 24, 29, 35, 40, 50, 60];
    expect(analyzeAgeDistribution(ages)).toEqual([60, 18, 33.33, 5]);
  });

  test('single age', () => {
    const ages = [30];
    expect(analyzeAgeDistribution(ages)).toEqual([30, 30, 30.0, 1]);
  });

  test('all same age', () => {
    const ages = [25, 25, 25, 25];
    expect(analyzeAgeDistribution(ages)).toEqual([25, 25, 25.0, 4]);
  });

  test('two ages', () => {
    const ages = [20, 40];
    expect(analyzeAgeDistribution(ages)).toEqual([40, 20, 30.0, 1]);
  });

  test('edge case young', () => {
    const ages = [0, 1, 2, 3, 4];
    expect(analyzeAgeDistribution(ages)).toEqual([4, 0, 2.0, 3]);
  });

  test('edge case old', () => {
    const ages = [110, 115, 120];
    expect(analyzeAgeDistribution(ages)).toEqual([120, 110, 115.0, 2]);
  });

  test('large dataset', () => {
    const ages = Array.from({ length: 51 }, (_, i) => i + 20); // Ages from 20 to 70
    expect(analyzeAgeDistribution(ages)).toEqual([70, 20, 45.0, 26]);
  });

  test('mixed ages', () => {
    const ages = [18, 25, 32, 45, 52, 60, 65, 70, 22, 28];
    expect(analyzeAgeDistribution(ages)).toEqual([70, 18, 41.7, 5]);
  });

  test('precision rounding', () => {
    const ages = [33, 34, 35];
    expect(analyzeAgeDistribution(ages)).toEqual([35, 33, 34.0, 2]);
  });

  test('precision rounding 2', () => {
    const ages = [10, 20, 30, 40, 50, 60, 70];
    expect(analyzeAgeDistribution(ages)).toEqual([70, 10, 40.0, 4]);
  });

  test('empty array throws error', () => {
    expect(() => {
      analyzeAgeDistribution([]);
    }).toThrow('Ages array cannot be empty');
  });
});
