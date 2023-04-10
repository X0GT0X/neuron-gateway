# Neuron Gateway

## Requirements
1. Install [Docker](https://docker.com/).
2. Install [docker-compose](https://docs.docker.com/compose/install/).

## Getting Started
1. Run `make build` to build fresh images
2. Run `make up` (the logs will be displayed in the current shell)
3. This application is now available at `https://gateway.localhost`.
4. Run `make down` to stop the Docker containers.

## Docs
1. [TLS Certificates](docs/tls.md)
2. [Debugging with Xdebug](docs/xdebug.md)
3. [Troubleshooting](docs/troubleshooting.md)
