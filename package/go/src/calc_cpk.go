package src

import (
	"math"
)

// CalcCpkParam represents the parameters needed for Cpk calculation
type CalcCpkParam struct {
	USL  float64   // Upper Specification Limit (規格上限値)
	LSL  float64   // Lower Specification Limit (規格下限値)
	Data []float64 // Process data (工程データ)
}

// CalcCpk calculates the process capability index (Cpk)
func CalcCpk(param CalcCpkParam) float64 {
	if len(param.Data) <= 1 {
		// For single data point, sample standard deviation would be division by zero
		panic("insufficient data points for Cpk calculation")
	}

	// 1. Calculate mean of data
	sum := 0.0
	for _, value := range param.Data {
		sum += value
	}
	mean := sum / float64(len(param.Data))

	// 2. Calculate sample standard deviation
	squaredDiffSum := 0.0
	for _, value := range param.Data {
		diff := value - mean
		squaredDiffSum += diff * diff
	}
	sigma := math.Sqrt(squaredDiffSum / float64(len(param.Data)-1))

	// 3. Calculate specification center (M) and range (R)
	M := (param.USL + param.LSL) / 2
	R := param.USL - param.LSL

	// 4. Calculate k
	k := math.Abs(mean-M) / (R / 2)

	// 5. Calculate Cp
	Cp := (param.USL - param.LSL) / (6 * sigma)

	// 6. Calculate Cpk
	Cpk := (1 - k) * Cp

	// Round to 3 decimal places as per problem specification
	return math.Round(Cpk*1000) / 1000
}
