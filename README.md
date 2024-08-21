
## Installation

```bash
# Clone the repository
git clone  git@github.com:senea777/laravel-api-submission-test.git
```

```bash
# Switch to the repo folder
cd laravel-api-submission-test

# Copy the example env file and make the required configuration changes in the .env file 
cp .env.example .env 


# Install all the dependencies using composer
composer install 

#run sail install  - select mysql and redis using space key
php artisan sail:install 




#copy the docker-compose.yml.txt file to the docker-compose.yml file
cp docker-compose.yml.txt docker-compose.yml 
  
```
## Running the application and tests
```bash
#up the docker containers with sail
vendor/bin/sail up -d

#run the migrations 
vendor/bin/sail artisan migrate 

#run bash in the container 
vendor/bin/sail bash 

#run the job queue 
php artisan queue:work 


#run the tests
#open new console and run bash in the container
vendor/bin/sail bash 

#run the tests in conatiner console
php artisan test

```

## API Endpoints
```bash
#POST /api/submissions

curl -X POST "http://localhost/api/submissions" -H "accept: application/json" -H "Content-Type: application/json" -d "{\"name\":\"Test Name\",\"email\":\"test@example.com\",\"message\":\"Test Message\"}" 

```

##View logs

```bash
#for viewing the logs
tail -f storage/logs/laravel.log 
```


## Stop the application
```bash
vendor/bin/sail down 
```
