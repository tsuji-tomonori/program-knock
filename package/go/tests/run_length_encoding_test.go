package tests

import (
	"strings"
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestRunLengthEncoding(t *testing.T) {
	t.Run("Sample1", func(t *testing.T) {
		// "aaabbcdddd" -> [("a", 3), ("b", 2), ("c", 1), ("d", 4)]
		result := src.RunLengthEncoding("aaabbcdddd")
		expected := []src.RunLengthEncodingResult{
			{Char: "a", Count: 3},
			{Char: "b", Count: 2},
			{Char: "c", Count: 1},
			{Char: "d", Count: 4},
		}
		assert.Equal(t, expected, result)
	})

	t.Run("Sample2", func(t *testing.T) {
		// "abc" -> [("a", 1), ("b", 1), ("c", 1)]
		result := src.RunLengthEncoding("abc")
		expected := []src.RunLengthEncodingResult{
			{Char: "a", Count: 1},
			{Char: "b", Count: 1},
			{Char: "c", Count: 1},
		}
		assert.Equal(t, expected, result)
	})

	t.Run("Sample3", func(t *testing.T) {
		// "aaaaaaa" -> [("a", 7)]
		result := src.RunLengthEncoding("aaaaaaa")
		expected := []src.RunLengthEncodingResult{
			{Char: "a", Count: 7},
		}
		assert.Equal(t, expected, result)
	})

	t.Run("AlternatingCharacters", func(t *testing.T) {
		// Alternating characters: each character appears consecutively once
		s := "abababab"
		result := src.RunLengthEncoding(s)
		expected := []src.RunLengthEncodingResult{
			{Char: "a", Count: 1},
			{Char: "b", Count: 1},
			{Char: "a", Count: 1},
			{Char: "b", Count: 1},
			{Char: "a", Count: 1},
			{Char: "b", Count: 1},
			{Char: "a", Count: 1},
			{Char: "b", Count: 1},
		}
		assert.Equal(t, expected, result)
	})

	t.Run("SingleCharacterMaxLength", func(t *testing.T) {
		// Boundary test: maximum character count of 100,000 identical characters
		s := strings.Repeat("a", 100000)
		result := src.RunLengthEncoding(s)
		expected := []src.RunLengthEncodingResult{
			{Char: "a", Count: 100000},
		}
		assert.Equal(t, expected, result)
	})

	t.Run("NonRepeatingCharacters", func(t *testing.T) {
		// All different characters in alphabetical order
		s := "abcdefghijklmnopqrstuvwxyz"
		result := src.RunLengthEncoding(s)

		var expected []src.RunLengthEncodingResult
		for c := 'a'; c <= 'z'; c++ {
			expected = append(expected, src.RunLengthEncodingResult{
				Char:  string(c),
				Count: 1,
			})
		}
		assert.Equal(t, expected, result)
	})

	t.Run("VariedRepetition", func(t *testing.T) {
		// Each character has different repetition count
		s := "aabbbccccddddd"
		result := src.RunLengthEncoding(s)
		expected := []src.RunLengthEncodingResult{
			{Char: "a", Count: 2},
			{Char: "b", Count: 3},
			{Char: "c", Count: 4},
			{Char: "d", Count: 5},
		}
		assert.Equal(t, expected, result)
	})

	t.Run("MixedGroups", func(t *testing.T) {
		// Mixed consecutive groups
		s := "zzzzzyxx"
		result := src.RunLengthEncoding(s)
		expected := []src.RunLengthEncodingResult{
			{Char: "z", Count: 5},
			{Char: "y", Count: 1},
			{Char: "x", Count: 2},
		}
		assert.Equal(t, expected, result)
	})

	t.Run("IncreasingGroupSizes", func(t *testing.T) {
		// Group sizes increase sequentially
		s := "abbcccddddeeeee"
		result := src.RunLengthEncoding(s)
		expected := []src.RunLengthEncodingResult{
			{Char: "a", Count: 1},
			{Char: "b", Count: 2},
			{Char: "c", Count: 3},
			{Char: "d", Count: 4},
			{Char: "e", Count: 5},
		}
		assert.Equal(t, expected, result)
	})

	t.Run("MinimumLengthString", func(t *testing.T) {
		// Boundary test: minimum string length (1)
		s := "a"
		result := src.RunLengthEncoding(s)
		expected := []src.RunLengthEncodingResult{
			{Char: "a", Count: 1},
		}
		assert.Equal(t, expected, result)
	})

	t.Run("EmptyString", func(t *testing.T) {
		// Edge case: empty string
		s := ""
		result := src.RunLengthEncoding(s)
		expected := []src.RunLengthEncodingResult{}
		assert.Equal(t, expected, result)
	})

	t.Run("LargePatternRepetition", func(t *testing.T) {
		// Large pattern with repetition
		s := strings.Repeat("aaa", 1000) + strings.Repeat("bbb", 1000)
		result := src.RunLengthEncoding(s)
		expected := []src.RunLengthEncodingResult{
			{Char: "a", Count: 3000},
			{Char: "b", Count: 3000},
		}
		assert.Equal(t, expected, result)
	})

	t.Run("ComplexPattern", func(t *testing.T) {
		// Complex pattern with various group sizes
		s := "aabbbaabbccccdddddeeeeeee"
		result := src.RunLengthEncoding(s)
		expected := []src.RunLengthEncodingResult{
			{Char: "a", Count: 2},
			{Char: "b", Count: 3},
			{Char: "a", Count: 2},
			{Char: "b", Count: 2},
			{Char: "c", Count: 4},
			{Char: "d", Count: 5},
			{Char: "e", Count: 7},
		}
		assert.Equal(t, expected, result)
	})
}
