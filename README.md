# Enqueue sandbox. 

The repo used to play with enqueue in envirments close to real ones. 
Contains a docker container and symfony app.
  
## Setup

```
git clone git@github.com:php-enqueue/enqueue-sandbox.git
cd enqueue-sandbox
git submodule init; git submodule update
./bin/sandbox -b
cd symfony
composer install
```

## Usage

Run docker containers

```
./bin/sandbox -u
```

Enter to sandbox container

```
./bin/sandbox -e
```
