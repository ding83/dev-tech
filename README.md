# Reviews and key improvements
* Created a folder specific for api controllers
* Created a route specific for api namespaces
* Add validators of request
* Add resource json
* Add new Dockerfile and docker-compose-dev.yml so project does not need to be run in host's environment such as composer install, npm install and etc.
* Update job table, add foreign key company_id
* Add companies table

# To run the application.
* docker-compose -f docker-compose-dev.yml up (Note: to run this for the first time you need to docker-compose -f docker-compose-dev.yml build)
* docker exec -it &lt;the-container-id-of-the-api&gt bash
* composer install
* php artisan migrate:fresh --path=./database/migrations/structure

# Key Folders
```
|── app
|   |── Http
|       |── Controllers
|           |── Api
|       |── Resources
|   |── Models
|   |── Observers
|   |── Tasks
|   |── Validators
|
|── database
|   |── factories
|   |── migrations
|── docker
|   |── apache
```
# Requirements
* PHP8
* Composer
* Docker
* Port 80 on localhost

