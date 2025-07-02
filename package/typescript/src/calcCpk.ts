interface CpkInput {
  usl: number;
  lsl: number;
  data: number[];
}

export function calcCpk({ usl, lsl, data }: CpkInput): number {
  if (data.length === 0) {
    throw new Error('Data array cannot be empty');
  }

  if (data.length === 1) {
    throw new Error('Data array must contain at least 2 elements for standard deviation calculation');
  }

  const mean = data.reduce((sum, value) => sum + value, 0) / data.length;

  const variance = data.reduce((sum, value) => sum + Math.pow(value - mean, 2), 0) / (data.length - 1);
  const standardDeviation = Math.sqrt(variance);

  const centerValue = (usl + lsl) / 2;
  const specificationRange = usl - lsl;

  const k = Math.abs(mean - centerValue) / (specificationRange / 2);

  const cp = specificationRange / (6 * standardDeviation);

  const cpk = (1 - k) * cp;

  return Math.round(cpk * 1000) / 1000;
}
