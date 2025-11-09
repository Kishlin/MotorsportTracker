package infrastructure

import (
	"fmt"
	"io"
	"log/slog"
	"os"
	"path/filepath"
)

type FileSystemCache struct {
	rootDir, fileExtension string
}

func NewFileSystemCache(rootDir, fileExtension string) *FileSystemCache {
	return &FileSystemCache{rootDir: rootDir, fileExtension: fileExtension}
}

func (f *FileSystemCache) Get(namespace, key string) (value []byte, hit bool, err error) {
	filename := key + f.fileExtension
	file := filepath.Clean(filepath.Join(f.rootDir, namespace, filename))

	logger := slog.With("namespace", namespace, "key", key, "file", file)

	handle, err := os.Open(file)
	if err != nil {
		if os.IsNotExist(err) {
			logger.Debug("Cache miss")
			return nil, false, nil
		}
		return nil, false, fmt.Errorf("openning file: %w", err)
	}
	defer func() {
		if closeErr := handle.Close(); closeErr != nil && err == nil {
			err = fmt.Errorf("closing cache file: %w", closeErr)
		}
	}()

	logger.Debug("Cache hit")

	value, err = io.ReadAll(handle)
	if err != nil {
		return nil, false, err
	}

	return value, true, nil
}

func (f *FileSystemCache) Set(namespace, key string, value []byte) (err error) {
	filename := key + f.fileExtension
	file := filepath.Clean(filepath.Join(f.rootDir, namespace, filename))

	err = os.MkdirAll(filepath.Dir(file), 0750)
	if err != nil {
		return fmt.Errorf("creating cache directories: %w", err)
	}

	handle, err := os.OpenFile(file, os.O_CREATE|os.O_TRUNC|os.O_WRONLY, 0600)
	if err != nil {
		return fmt.Errorf("creating cache file: %w", err)
	}
	defer func() {
		if closeErr := handle.Close(); closeErr != nil && err == nil {
			err = fmt.Errorf("closing cache file: %w", closeErr)
		}
	}()

	slog.Debug("Set value", "namespace", namespace, "key", key, "file", file)

	_, err = handle.Write(value)
	if err != nil {
		return fmt.Errorf("writing to cache file: %w", err)
	}

	return nil
}
