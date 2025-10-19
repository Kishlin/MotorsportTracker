CREATE TABLE IF NOT EXISTS series (
    id SERIAL PRIMARY KEY,
    uuid UUID UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    short_name VARCHAR(255),
    short_code VARCHAR(50) NOT NULL,
    category VARCHAR(100) NOT NULL
);
