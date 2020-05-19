# Project Title

Rental System

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project. In src folder detail readme for drupal is shared. But To setup environment please follow below sections.

### Prerequisites

Install docker and docker compose

### Installing


#### Docker Command

#### Rebuild docker
docker-compose build --no-cache

#### Run docker
docker-compose up -d

#### Access workspace environment
docker-compose exec workspace bash

#### Update vendors
docker-compose exec workspace composer install

#### Load Initital Database dump
* Initial dump is share in root db folder.
* Access phpmyadmin by localhost:8080 and import dump zip in default database


## Built With

* Laravel 5.4
* Php 7.2

## Versioning

Gitlab

## Authors

* **Mudassar Ali** - *Initial work* 