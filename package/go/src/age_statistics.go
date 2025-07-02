package src

import (
	"math"
)

type AgeStatistics struct {
	MaxAge         int
	MinAge         int
	AvgAge         float64
	CountBelowAvg  int
}

func AnalyzeAgeDistribution(ages []int) AgeStatistics {
	if len(ages) == 0 {
		return AgeStatistics{}
	}

	maxAge := ages[0]
	minAge := ages[0]
	sum := 0

	for _, age := range ages {
		if age > maxAge {
			maxAge = age
		}
		if age < minAge {
			minAge = age
		}
		sum += age
	}

	avgAge := math.Round(float64(sum)/float64(len(ages))*100) / 100

	countBelowAvg := 0
	for _, age := range ages {
		if float64(age) <= avgAge {
			countBelowAvg++
		}
	}

	return AgeStatistics{
		MaxAge:        maxAge,
		MinAge:        minAge,
		AvgAge:        avgAge,
		CountBelowAvg: countBelowAvg,
	}
}
