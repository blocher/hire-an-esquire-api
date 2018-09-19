### Hire an Esquire Candidate API (PHP Implemenation)

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


* Configure webserver to server form `public` folder
* Copy `.env.example` to `.env` and customize based on your database settings.  It is currently configured for a standard Homestead installation
* From the root of the project, run `composer install`
* Run database migrations and seeder from root folder `php artisan migrate --seed`
* Consider using Laravel Homestead Vagrant box (https://laravel.com/docs/5.7/homestead) for fast setup of local environment

## Endpoints

* List candidates GET `api\v1\candidates`
	* Optional paramater: `sort` accepts either `status` or `date_applied` | default `date_app;ied`
	* Optional paramater: `order` accepts either `ASC` or `DESC` | default `ASC`
	* Optional paramater: `reviewed` accepts either `1` or `0` | default not present
* Show candidate GET `api\v1\candidates\{id}`
* Create candidate POST `api\v1\candidates\`
* Create candidate PUT `api\v1\candidates\{id}`
* Delete candidate DELETE `api\v1\candidates\{id}`

## Running Tests

* From project root director run `phpunit`

## Key Files

* \App\Http\Controllers\CandidateController.php
* \App\Models\Candidate.php
* \App\Http\Resources\CandidateResource.php
* \App\Http\Resources\CandidateCollection.php
* \App\Http\Rules\CanddateStatusRule.php
* tests\Feature\CandidateEndpointTest.php
* database\migrations\2018_09_15_191917_create_candidates_table.php
