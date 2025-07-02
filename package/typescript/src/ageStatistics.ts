export function analyzeAgeDistribution(ages: number[]): [number, number, number, number] {
  if (ages.length === 0) {
    throw new Error('Ages array cannot be empty');
  }

  const maxAge = Math.max(...ages);
  const minAge = Math.min(...ages);
  const avgAge = Math.round((ages.reduce((sum, age) => sum + age, 0) / ages.length) * 100) / 100;
  const countBelowAvg = ages.filter(age => age <= avgAge).length;

  return [maxAge, minAge, avgAge, countBelowAvg];
}
