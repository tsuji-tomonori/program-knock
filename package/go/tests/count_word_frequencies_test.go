package tests

import (
	"strings"
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestCountWordFrequencies(t *testing.T) {
	t.Run("Basic", func(t *testing.T) {
		text := "apple banana apple orange banana apple"
		result := src.CountWordFrequencies(text)
		expected := map[string]int{"apple": 3, "banana": 2, "orange": 1}
		assert.Equal(t, expected, result)
	})

	t.Run("SingleWord", func(t *testing.T) {
		text := "python"
		result := src.CountWordFrequencies(text)
		expected := map[string]int{"python": 1}
		assert.Equal(t, expected, result)
	})

	t.Run("TiedFrequencies", func(t *testing.T) {
		text := "dog cat bird cat dog bird"
		result := src.CountWordFrequencies(text)
		expected := map[string]int{"bird": 2, "cat": 2, "dog": 2}
		assert.Equal(t, expected, result)
	})

	t.Run("EmptyString", func(t *testing.T) {
		text := ""
		result := src.CountWordFrequencies(text)
		expected := map[string]int{}
		assert.Equal(t, expected, result)
	})

	t.Run("RepeatedSingleWord", func(t *testing.T) {
		// 100 times "test"
		words := make([]string, 100)
		for i := range words {
			words[i] = "test"
		}
		text := strings.Join(words, " ")
		result := src.CountWordFrequencies(text)
		expected := map[string]int{"test": 100}
		assert.Equal(t, expected, result)
	})

	t.Run("WordsOfVariousLengths", func(t *testing.T) {
		text := "a bb ccc a bb"
		result := src.CountWordFrequencies(text)
		expected := map[string]int{"a": 2, "bb": 2, "ccc": 1}
		assert.Equal(t, expected, result)
	})

	t.Run("AlphabeticalOrderForSameFrequency", func(t *testing.T) {
		text := "cat dog cat dog fish bird fish bird"
		result := src.CountWordFrequencies(text)
		expected := map[string]int{"bird": 2, "cat": 2, "dog": 2, "fish": 2}
		assert.Equal(t, expected, result)
	})

	t.Run("VaryingFrequencies", func(t *testing.T) {
		text := "apple apple banana apple banana orange lemon"
		result := src.CountWordFrequencies(text)
		expected := map[string]int{"apple": 3, "banana": 2, "lemon": 1, "orange": 1}
		assert.Equal(t, expected, result)
	})

	t.Run("OneLetterWords", func(t *testing.T) {
		text := "a b c a b a"
		result := src.CountWordFrequencies(text)
		expected := map[string]int{"a": 3, "b": 2, "c": 1}
		assert.Equal(t, expected, result)
	})

	t.Run("ManyWordsCase", func(t *testing.T) {
		// Build test text: apple*12, banana*8, cherry*7
		var parts []string
		for i := 0; i < 10; i++ {
			parts = append(parts, "apple")
		}
		for i := 0; i < 5; i++ {
			parts = append(parts, "banana")
		}
		for i := 0; i < 5; i++ {
			parts = append(parts, "cherry")
		}
		for i := 0; i < 3; i++ {
			parts = append(parts, "banana")
		}
		for i := 0; i < 2; i++ {
			parts = append(parts, "apple")
		}
		for i := 0; i < 2; i++ {
			parts = append(parts, "cherry")
		}
		text := strings.Join(parts, " ")

		result := src.CountWordFrequencies(text)
		expected := map[string]int{"apple": 12, "banana": 8, "cherry": 7}
		assert.Equal(t, expected, result)
	})

	t.Run("ExtraSpacesHandling", func(t *testing.T) {
		text := "  apple   banana  apple  "
		result := src.CountWordFrequencies(text)
		expected := map[string]int{"apple": 2, "banana": 1}
		assert.Equal(t, expected, result)
	})

	t.Run("CaseSensitive", func(t *testing.T) {
		text := "Apple apple APPLE"
		result := src.CountWordFrequencies(text)
		expected := map[string]int{"Apple": 1, "apple": 1, "APPLE": 1}
		assert.Equal(t, expected, result)
	})

	t.Run("SpecialCharacters", func(t *testing.T) {
		text := "hello! world? hello!"
		result := src.CountWordFrequencies(text)
		expected := map[string]int{"hello!": 2, "world?": 1}
		assert.Equal(t, expected, result)
	})
}
