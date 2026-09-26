FROM kishlin/base-kishlin-golang:1.24-dev

ARG DOCKER_USER
ARG DOCKER_USER_ID

USER root

# Install psql
RUN apt-get update && apt-get install -y \
    postgresql-client \
    && rm -rf /var/lib/apt/lists/*

USER gouser

# Use tail -f /dev/null to keep the container running indefinitely
CMD ["tail", "-f", "/dev/null"]
