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
			"hello":        "2cf24dba5fb0a30e26e83b2ac5b9e29e1b161e5c1fa7425e73043362938b9824",
			"world":        "486ea46224d1bb4fb680f34f7c9ad96a8f24ec88be73ea8e5a6c65260e9cb8a7",
			"motorsport":   "2c645a531eb8640f3bf0cb1d805f772fede9a2238cb13304c01808e45ee47b9c",
			"cryptography": "e06554818e902b4ba339f066967c0000da3fcda4fd7eb4ef89c124fa78bda419",
			"":             "e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855",
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
