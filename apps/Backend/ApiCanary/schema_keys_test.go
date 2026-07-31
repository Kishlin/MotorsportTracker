package main

import (
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type SchemaKeysUnitTestSuite struct {
	suite.Suite
}

func TestUnit_SchemaKeys(t *testing.T) {
	suite.Run(t, new(SchemaKeysUnitTestSuite))
}

func (suite *SchemaKeysUnitTestSuite) TestUnknownKeys() {
	suite.Run("No drift", func() {
		schema := `{"type":"object","properties":{"uuid":{"type":"string"},"name":{"type":"string"}}}`
		payload := `{"uuid":"abc","name":"Formula One"}`

		keys, err := unknownKeys([]byte(schema), []byte(payload))

		require.NoError(suite.T(), err)
		require.Empty(suite.T(), keys)
	})

	suite.Run("Added key at the root of an object", func() {
		schema := `{"type":"object","properties":{"uuid":{"type":"string"}}}`
		payload := `{"uuid":"abc","broadcastUrl":"https://example.com"}`

		keys, err := unknownKeys([]byte(schema), []byte(payload))

		require.NoError(suite.T(), err)
		require.Equal(suite.T(), []string{"/: broadcastUrl"}, keys)
	})

	suite.Run("Added key at the root of an array", func() {
		schema := `{"type":"array","items":{"type":"object","properties":{"uuid":{"type":"string"}}}}`
		payload := `[{"uuid":"abc","category":"Single Seater"}]`

		keys, err := unknownKeys([]byte(schema), []byte(payload))

		require.NoError(suite.T(), err)
		require.Equal(suite.T(), []string{"/[]: category"}, keys)
	})

	suite.Run("Added key nested inside an array", func() {
		schema := `{
			"type":"object",
			"properties":{
				"details":{
					"type":"array",
					"items":{
						"type":"object",
						"properties":{
							"carNumber":{"type":"string"},
							"drivers":{
								"type":"array",
								"items":{"type":"object","properties":{"uuid":{"type":"string"}}}
							}
						}
					}
				}
			}
		}`
		payload := `{
			"details":[
				{"carNumber":"1","pitStops":3,"drivers":[{"uuid":"a","nationalityCode":"NLD"}]},
				{"carNumber":"2","drivers":[{"uuid":"b"}]}
			]
		}`

		keys, err := unknownKeys([]byte(schema), []byte(payload))

		require.NoError(suite.T(), err)
		require.Equal(
			suite.T(),
			[]string{"/details[]: pitStops", "/details[]/drivers[]: nationalityCode"},
			keys,
		)
	})

	suite.Run("Repeated key across many items is reported once", func() {
		schema := `{"type":"array","items":{"type":"object","properties":{"uuid":{"type":"string"}}}}`
		payload := `[
			{"uuid":"a","tyreCompound":"soft"},
			{"uuid":"b","tyreCompound":"medium"},
			{"uuid":"c","tyreCompound":"hard"}
		]`

		keys, err := unknownKeys([]byte(schema), []byte(payload))

		require.NoError(suite.T(), err)
		require.Equal(suite.T(), []string{"/[]: tyreCompound"}, keys)
	})

	suite.Run("Several added keys on one path are sorted and joined", func() {
		schema := `{"type":"object","properties":{"uuid":{"type":"string"}}}`
		payload := `{"uuid":"a","zulu":1,"alpha":2}`

		keys, err := unknownKeys([]byte(schema), []byte(payload))

		require.NoError(suite.T(), err)
		require.Equal(suite.T(), []string{"/: alpha, zulu"}, keys)
	})

	suite.Run("Renamed key surfaces as an unknown key", func() {
		schema := `{"type":"object","properties":{"shortCode":{"type":"string"}}}`
		payload := `{"short_code":"F1"}`

		keys, err := unknownKeys([]byte(schema), []byte(payload))

		require.NoError(suite.T(), err)
		require.Equal(suite.T(), []string{"/: short_code"}, keys)
	})

	suite.Run("Schema silent about a subtree stops the descent", func() {
		schema := `{"type":"object","properties":{"gap":{"type":"object"}}}`
		payload := `{"gap":{"timeToLead":1.5,"timeToNext":0.3}}`

		keys, err := unknownKeys([]byte(schema), []byte(payload))

		require.NoError(suite.T(), err)
		require.Empty(suite.T(), keys)
	})

	suite.Run("Null value against a declared property is not drift", func() {
		schema := `{"type":"object","properties":{"picture":{"type":["string","null"]}}}`
		payload := `{"picture":null}`

		keys, err := unknownKeys([]byte(schema), []byte(payload))

		require.NoError(suite.T(), err)
		require.Empty(suite.T(), keys)
	})

	suite.Run("Malformed payload errors", func() {
		schema := `{"type":"object","properties":{}}`

		_, err := unknownKeys([]byte(schema), []byte(`{not json`))

		require.Error(suite.T(), err)
	})
}
