# Snowtricks | [![Maintainability](https://api.codeclimate.com/v1/badges/547c8f1fccb05234e7fd/maintainability)](https://codeclimate.com/github/ArtGM/P6-snowtricks/maintainability) | [![SymfonyInsight](https://insight.symfony.com/projects/58950692-72d0-46b9-9724-a9772805244c/mini.svg)](https://insight.symfony.com/projects/58950692-72d0-46b9-9724-a9772805244c)

Snowboard tricks community website

## Prerequisites

- Composer v1.9.3
- Docker v20.10.5 / Docker Desktop
- Symfony CLI v4.23.5
- PHP 7.4

## Installation

- clone repo

- duplicate .env file and rename it to .env.local

- Add youtube Api key and Gmail credentials

- type ``composer install``

- ``docker-compose up -d --build``

- `` symfony console doctrine:fixtures:load``

- `` symfony serve ``

- `` yarn run dev-server ``

- Open web browser on `` http://127.0.0.1:8000 ``



