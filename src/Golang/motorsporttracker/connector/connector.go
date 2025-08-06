package connector

type Connector interface {
	Get(url string) ([]byte, error)
}
