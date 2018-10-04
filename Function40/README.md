# Defines the fitness of Kōjō for
 - A single job that deletes SQS queue messages
 
 ## Setup Function42
 
 ### Running Kōjō
 Setting up the container:
 
 Make sure you have pulled the latest version of Mason from Master
 ``` bash
 git clone git@github.com:neighborhoods/KojoFitness.git
 cd KojoFitness;
 git checkout 4.x;
 cd ../Mason;
 docker-compose build --no-cache kojo_fitness nginx && docker-compose up -d;
 touch data/pgsql/dumps/kojo_fitness.sql;  
 docker-compose exec pgsql /docker-entrypoint-initdb.d/init.sh;
 docker-compose exec kojo_fitness bash -c 'cd Function40; composer install';
 docker-compose exec kojo_fitness bash -c 'cd Function40; ./vendor/bin/kojo db:setup:install $PWD/src/V1/Environment/';
 docker-compose exec kojo_fitness bash -c 'cd Function40; php ./bin/setup-worker.php';
 
 # Create messages for Kōjō to delete
 docker-compose exec kojo_fitness bash -c 'cd Function40; php ./bin/create-messages.php';
 
 # Run Kōjō to delete the messages
 docker-compose exec kojo_fitness bash -c 'cd Function40; ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Environment/' |\
  awk '{ gsub("new_worker", "\033[1;36m&\033[0m"); gsub("working", "\033[1;33m&\033[0m"); gsub("complete_success", "\033[1;32m&\033[0m"); print }';
 
 docker-compose exec kojo_fitness bash -c 'cd Function40; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V1/Environment/'
 ```