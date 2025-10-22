package doman

import (
	"testing"

	"github.com/stretchr/testify/suite"
)

type CryptoUnitTestSuite struct {
	suite.Suite
}

func (suite *CryptoUnitTestSuite) TestHash() {
	suite.T().Run("hashes strings correctly", func(t *testing.T) {
		cases := map[string]string{
			"hello":        "5d41402abc4b2a76b9719d911017c592",
			"world":        "7d793037a0760186574b0282f2f435e7",
			"motorsport":   "08476cf7cd921a422d69db4515cd2ea0",
			"cryptography": "e0d00b9f337d357c6faa2f8ceae4a60d",
			"":             "d41d8cd98f00b204e9800998ecf8427e",
		}

		for input, expectedHash := range cases {
			actualHash := Hash(input)
			suite.Equal(expectedHash, actualHash, "Hash(%q) = %q; want %q", input, actualHash, expectedHash)
		}
	})
}

func TestUnit_Crypto(t *testing.T) {
	suite.Run(t, new(CryptoUnitTestSuite))
}
