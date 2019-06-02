# crypto-sim
Cryptocurrency trading simulator

Docker LAMP Stack from https://github.com/pnglabz/docker-compose-lamp

## Setting Up Dev Environment

1. Clone this repository
2. Open a shell (git bash, etc.) and navigate to the directory of the cloned project
3. Run `docker-compose up -d`.
4. Navigate to the root of the project, then navigate to `bin/init`
5. Run `init.sh`
6. Run `docker exec -it webserver bash` to connect to the web server container
7. Within the web server, navigate to `sql` directory
8. Run `createDBs.sh`
9. Exit the web server container using typing `exit`