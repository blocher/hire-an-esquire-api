### Hire an Esquire Candidate API (PHP Implementation)

## Live site

* The API may be demoed at https://hire-an-esquire.benlocher.com/api/v1/{endpoint}

## Requirements

* Nginx
* PHP >= 7.1.3
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension
* Ctype PHP Extension
* JSON PHP Extension
* Composer

** The easiest way to meet these requirements is to use the Laravel Homestead Vagrant box (https://laravel.com/docs/5.7/homestead)

## Installation

* Configure webserver to serve form `public` folder
* Copy `.env.example` to `.env` and customize based on your database settings.  It is currently configured for a standard Homestead installation
* From the root of the project, run `composer install`
* Run database migrations and seeder from root folder `php artisan migrate --seed`
* Consider using Laravel Homestead Vagrant box (https://laravel.com/docs/5.7/homestead) for fast setup of local environment

## Endpoints

* List candidates GET `api\v1\candidates`
	* Optional parameter: `sort` accepts either `status` or `date_applied` | default `date_applied`
	* Optional parameter: `order` accepts either `ASC` or `DESC` | default `ASC`
	* Optional parameter: `reviewed` accepts either `1` or `0` | default not present
	* Pagination: 2 results are returned per page, page links are in `meta.links` of the result
* Show candidate GET `api\v1\candidates\{id}`
* Create candidate POST `api\v1\candidates\`
* Update candidate PUT `api\v1\candidates\{id}`
* Delete candidate DELETE `api\v1\candidates\{id}`

## Authentication

* All requests must have a api_token parameter
* Token generation is not fully implemented, but each user is assigned an `api_token` when users are seeded in database
* If using database seeds, `1ee567a5-83ae-4309-8f2b-3ad94bcc94dd` is a valid token


## Running Tests

* From project root directory run `phpunit` or if that fails `vendor/bin/phpunit`

## Key Files

* app\Http\Controllers\CandidateController.php
* app\Models\Candidate.php
* app\Http\Resources\CandidateResource.php
* app\Http\Resources\CandidateCollection.php
* app\Rules\CanddateStatusRule.php
* app\Api\ApiResponses.oho
* tests\Feature\CandidateEndpointTest.php
* database\migrations\2018_09_15_191917_create_candidates_table.php
* database\seeds\UserSeeder.php
* database\seeds\CandidateSeeder.php


