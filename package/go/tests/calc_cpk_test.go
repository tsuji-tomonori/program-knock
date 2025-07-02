package tests

import (
	"math/rand"
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestCalcCpk(t *testing.T) {
	t.Run("Basic1", func(t *testing.T) {
		// Test case from problem specification
		param := src.CalcCpkParam{
			USL:  10.0,
			LSL:  2.0,
			Data: []float64{4.5, 5.0, 4.8, 5.2, 5.5},
		}
		result := src.CalcCpk(param)
		assert.Equal(t, 2.626, result)
	})

	t.Run("SymmetricDataSmallStdev", func(t *testing.T) {
		// Data with very small standard deviation
		param := src.CalcCpkParam{
			USL:  10,
			LSL:  0,
			Data: []float64{4.9999, 5.0, 5.0001, 5.0, 5.0},
		}
		result := src.CalcCpk(param)
		// Should result in very high Cpk value
		assert.Greater(t, result, 20000.0)
	})

	t.Run("SymmetricDataNormalStdev", func(t *testing.T) {
		param := src.CalcCpkParam{
			USL:  10,
			LSL:  0,
			Data: []float64{4, 5, 6, 5, 5},
		}
		result := src.CalcCpk(param)
		assert.Equal(t, 2.357, result)
	})

	t.Run("NearLSL", func(t *testing.T) {
		// Data close to lower specification limit
		param := src.CalcCpkParam{
			USL:  10,
			LSL:  2,
			Data: []float64{2.1, 2.2, 2.0, 2.1, 2.3},
		}
		result := src.CalcCpk(param)
		assert.Equal(t, 0.409, result)
	})

	t.Run("NearUSL", func(t *testing.T) {
		// Data close to upper specification limit
		param := src.CalcCpkParam{
			USL:  10,
			LSL:  2,
			Data: []float64{9.5, 9.7, 9.8, 9.9, 9.4},
		}
		result := src.CalcCpk(param)
		assert.Equal(t, 0.547, result)
	})

	t.Run("LargeRange", func(t *testing.T) {
		param := src.CalcCpkParam{
			USL:  100,
			LSL:  0,
			Data: []float64{50, 55, 60, 45, 40},
		}
		result := src.CalcCpk(param)
		assert.Equal(t, 2.108, result)
	})

	t.Run("NarrowRange", func(t *testing.T) {
		param := src.CalcCpkParam{
			USL:  5,
			LSL:  4,
			Data: []float64{4.5, 4.3, 4.6, 4.2, 4.4},
		}
		result := src.CalcCpk(param)
		assert.Equal(t, 0.843, result)
	})

	t.Run("MinimumDataTwoPoints", func(t *testing.T) {
		param := src.CalcCpkParam{
			USL:  10,
			LSL:  0,
			Data: []float64{4, 6},
		}
		result := src.CalcCpk(param)
		assert.Equal(t, 1.179, result)
	})

	t.Run("SingleDataRaisesError", func(t *testing.T) {
		param := src.CalcCpkParam{
			USL:  10,
			LSL:  0,
			Data: []float64{5},
		}
		assert.Panics(t, func() {
			src.CalcCpk(param)
		})
	})

	t.Run("KEquals1", func(t *testing.T) {
		// When k=1, Cpk should be 0
		param := src.CalcCpkParam{
			USL:  10,
			LSL:  0,
			Data: []float64{9.9, 10.0, 10.1, 10.0, 10.0},
		}
		result := src.CalcCpk(param)
		assert.Equal(t, 0.0, result)
	})

	t.Run("LargeDataset", func(t *testing.T) {
		// Performance test with large dataset
		rand.Seed(42) // For reproducible results
		data := make([]float64, 10000)
		for i := range data {
			data[i] = 5.0 + rand.NormFloat64()*0.5
		}
		param := src.CalcCpkParam{
			USL:  10.0,
			LSL:  0.0,
			Data: data,
		}
		result := src.CalcCpk(param)
		// Verify Cpk is within reasonable range
		assert.GreaterOrEqual(t, result, 0.0)
		assert.LessOrEqual(t, result, 5.0)
	})

	t.Run("ExtremeValues", func(t *testing.T) {
		// Test with extremely large values
		param := src.CalcCpkParam{
			USL:  1000000.0,
			LSL:  -1000000.0,
			Data: []float64{500000.0, 500001.0, 499999.0, 500000.5, 499999.5},
		}
		result := src.CalcCpk(param)
		// Should result in very high process capability
		assert.Greater(t, result, 100000.0)
	})

	t.Run("EmptyDataPanics", func(t *testing.T) {
		param := src.CalcCpkParam{
			USL:  10,
			LSL:  0,
			Data: []float64{},
		}
		assert.Panics(t, func() {
			src.CalcCpk(param)
		})
	})

	t.Run("PrecisionTest", func(t *testing.T) {
		// Test precision of rounding
		param := src.CalcCpkParam{
			USL:  10,
			LSL:  0,
			Data: []float64{5.0, 5.0, 5.0, 5.0, 5.1},
		}
		result := src.CalcCpk(param)
		// Verify result has at most 3 decimal places
		rounded := float64(int(result*1000)) / 1000
		assert.Equal(t, rounded, result)
	})
}
