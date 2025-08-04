package database

type Factory interface {
	NewDatabase(connStr string) (Database, error)
}

type PGXPoolDatabaseFactory struct{}

func NewDatabaseFactory() *PGXPoolDatabaseFactory {
	return &PGXPoolDatabaseFactory{}
}

func (f *PGXPoolDatabaseFactory) NewDatabase(connStr string) (Database, error) {
	return NewPGXPoolAdapter(connStr), nil
}
