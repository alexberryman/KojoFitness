# Defines the fitness of Kōjō for
 - Installing into databases that contain non-primitive column types (e.g. enums, multipolygons)
 
 ## Setup UseCase44
 
 ### Running Kōjō
 Setting up the containers using docker-compose:
 
 ```bash
 # Get the latest repo
 git clone git@github.com:neighborhoods/KojoFitness.git;
 cd KojoFitness;
 git checkout 4.x;
 git pull;
 
 #start the containers
 docker-compose build --no-cache && docker-compose up -d;
 
 # Create a sample database with a table with a column that is of non-primitive type
 cp UseCase44/sample_db_schema.sql data/pgsql/dumps/kojo_fitness.sql;
 docker-compose exec pgsql /docker-entrypoint-initdb.d/init.sh;
 
 # Prepare Kōjō
 docker-compose exec kojo_fitness bash -c 'cd UseCase44; composer install';
 docker-compose exec kojo_fitness bash -c 'cd UseCase44; ./vendor/bin/kojo db:setup:install $PWD/Environment/';
 
 # Delete the Kōjō Tables and clear redis
 docker-compose exec kojo_fitness bash -c 'cd UseCase44; ./vendor/bin/kojo db:tear_down:uninstall $PWD/Environment/';
 docker-compose exec redis redis-cli flushall;
 # The sample table will remain in kojo_fitness, but it will get deleted next time a FitnessFunction is run
 rm data/pgsql/dumps/kojo_fitness.sql
 ```
