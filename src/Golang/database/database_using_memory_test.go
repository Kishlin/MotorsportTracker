package database

import (
	"testing"

	_func "github.com/kishlin/MotorsportTracker/src/Golang/func"
	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type DatabaseUsingMemoryUnitTestSuite struct {
	suite.Suite

	db *MemoryDatabase
}

func (suite *DatabaseUsingMemoryUnitTestSuite) SetupTest() {
	suite.db = NewMemoryDatabase()
}

func (suite *DatabaseUsingMemoryUnitTestSuite) TearDownTest() {
	suite.db.Close()
}

func (suite *DatabaseUsingMemoryUnitTestSuite) TestConnect() {
	err := suite.db.Connect(suite.T().Context())
	require.NoError(suite.T(), err)
	require.True(suite.T(), suite.db.isConnected())

	// Second connect should fail
	err = suite.db.Connect(suite.T().Context())
	require.Error(suite.T(), err)
}

func (suite *DatabaseUsingMemoryUnitTestSuite) TestPing() {
	// Not connected yet
	err := suite.db.Ping(suite.T().Context())
	require.Error(suite.T(), err)

	err = suite.db.Connect(suite.T().Context())
	require.NoError(suite.T(), err)

	// Now ping should succeed
	err = suite.db.Ping(suite.T().Context())
	require.NoError(suite.T(), err)

	suite.db.Close()

	// After close, ping should fail
	err = suite.db.Ping(suite.T().Context())
	require.Error(suite.T(), err)
}

//goland:noinspection SqlResolve
func (suite *DatabaseUsingMemoryUnitTestSuite) TestSetQueryResponse() {
	response := QueryResponse{
		Rows:    []map[string]interface{}{{"id": 123, "name": "John Doe"}},
		Columns: []string{"id", "name"},
	}

	suite.db.SetQueryResponse("SELECT * FROM users WHERE id = $1", []any{123}, response)
	require.Equal(suite.T(), 1, len(suite.db.queryResponses))
}

//goland:noinspection SqlResolve
func (suite *DatabaseUsingMemoryUnitTestSuite) TestNormalizeQuery() {
	testCases := []struct {
		name     string
		input    string
		expected string
	}{
		{
			"Multi-line with indentation",
			`SELECT *
			FROM users
			WHERE id = 1`,
			"select * from users where id = 1",
		},
		{
			"Mixed case with extra whitespace",
			`SELECT  ID,  Name
				FROM   Users
				WHERE  ID = $1`,
			"select id, name from users where id = $1",
		},
		{
			"Complex formatting with tabs and empty lines",
			"\t\tSELECT\tid,\tname\n\n\t\t\tFROM users\n\t\t\tWHERE id = $1\n\n",
			"select id, name from users where id = $1",
		},
	}

	for _, tc := range testCases {
		suite.T().Run(tc.name, func(t *testing.T) {
			result := suite.db.normalizeQuery(tc.input)
			require.Equal(suite.T(), tc.expected, result)
		})
	}
}

//goland:noinspection SqlResolve
func (suite *DatabaseUsingMemoryUnitTestSuite) TestQuery() {
	_, err := suite.db.Query(suite.T().Context(), "SELECT * FROM users")
	require.Error(suite.T(), err, "Query must fail before connection")
	_func.Must(suite.db.Connect(suite.T().Context()))

	response := QueryResponse{
		Rows:    []map[string]interface{}{{"id": 123, "name": "John Doe"}},
		Columns: []string{"id", "name"},
	}

	suite.db.SetQueryResponse("SELECT id, name FROM users WHERE id = $1", []any{123}, response)

	testCases := []struct {
		name  string
		query string
	}{
		{"Multi-line indented", `SELECT id, name
			FROM users
			WHERE id = $1`},
		{"Mixed case", "select ID, NAME from USERS where ID = $1"},
		{"Extra spaces and tabs", "SELECT  id,  name\t\tFROM\tusers\tWHERE\tid = $1"},
	}

	for _, tc := range testCases {
		suite.T().Run(tc.name, func(t *testing.T) {
			rows, err := suite.db.Query(suite.T().Context(), tc.query, 123)
			require.NoError(suite.T(), err)

			require.True(suite.T(), rows.Next())

			var id int
			var name string
			err = rows.Scan(&id, &name)
			require.NoError(suite.T(), err)

			require.Equal(suite.T(), 123, id)
			require.Equal(suite.T(), "John Doe", name)
		})
	}

	suite.db.Close()
	_, err = suite.db.Query(suite.T().Context(), "SELECT * FROM users")
	require.Error(suite.T(), err, "Query must fail after close")
}

//goland:noinspection SqlResolve
func (suite *DatabaseUsingMemoryUnitTestSuite) TestExec() {
	_, err := suite.db.Query(suite.T().Context(), "SELECT * FROM users")
	require.Error(suite.T(), err, "Query must fail before connection")
	_func.Must(suite.db.Connect(suite.T().Context()))

	response := QueryResponse{Error: nil}
	suite.db.SetQueryResponse("INSERT INTO users (name) VALUES ($1)", []any{"John Doe"}, response)

	err = suite.db.Exec(suite.T().Context(), "INSERT INTO users (name) VALUES ($1)", "John Doe")
	require.NoError(suite.T(), err)

	suite.db.Close()
	_, err = suite.db.Query(suite.T().Context(), "SELECT * FROM users")
	require.Error(suite.T(), err, "Query must fail after close")
}

// Close tests
func (suite *DatabaseUsingMemoryUnitTestSuite) TestClose() {
	require.False(suite.T(), suite.db.isConnected())

	suite.db.Close()

	require.False(suite.T(), suite.db.isConnected())

	// Should not panic on multiple closes
	suite.db.Close()
	suite.db.Close()
	suite.db.Close()

	require.False(suite.T(), suite.db.isConnected())
}

func (suite *DatabaseUsingMemoryUnitTestSuite) TestBuildArgsKey() {
	tests := []struct {
		name     string
		args     []any
		expected string
	}{
		{"no args", []any{}, "no_args"},
		{"with args", []any{"test", 123, true}, "test:string|123:int|true:bool"},
	}

	for _, tt := range tests {
		suite.T().Run(tt.name, func(t *testing.T) {
			key := suite.db.buildArgsKey(tt.args)
			if key != tt.expected {
				t.Errorf("Expected '%s', got '%s'", tt.expected, key)
			}
		})
	}

	// Test consistency
	key1 := suite.db.buildArgsKey([]any{"test", 123, true})
	key2 := suite.db.buildArgsKey([]any{"test", 123, true})
	if key1 != key2 {
		suite.T().Error("Same args should produce same key")
	}

	// Test uniqueness
	key3 := suite.db.buildArgsKey([]any{"different", 456})
	if key1 == key3 {
		suite.T().Error("Different args should produce different keys")
	}
}

func TestUnit_DatabaseUsingMemory(t *testing.T) {
	suite.Run(t, new(DatabaseUsingMemoryUnitTestSuite))
}
