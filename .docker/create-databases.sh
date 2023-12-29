#!/bin/bash


## Exit immediately if a command exits with a non zero status
#set -e
## Treat unset variables as an error when substituting
#set -u
#function create_databases() {
#    database=$1
#    password=$2
#    echo "Creating user and database '$database' with password '$password'"
#    psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" <<-EOSQL
#      CREATE USER $database with encrypted password '$password';
#      CREATE DATABASE $database;
#      GRANT ALL PRIVILEGES ON DATABASE $database TO $database;
#EOSQL
#}
## POSTGRES_MULTIBLE_DATABASES=db1,db2
## POSTGRES_MULTIBLE_DATABASES=db1:password,db2
#if [ -n "$POSTGRES_MULTIBLE_DATABASES" ]; then
#  echo "Multiple database creation requested: $POSTGRES_MULTIBLE_DATABASES"
#  for db in $(echo $POSTGRES_MULTIBLE_DATABASES | tr ',' ' '); do
#    user=$(echo $db | awk -F":" '{print $1}')
#    pswd=$(echo $db | awk -F":" '{print $2}')
#    if [[ -z "$pswd" ]]
#    then
#      pswd=$user
#    fi
#    echo "user is $user and pass is $pswd"
#    create_databases $user $pswd
#  done
#  echo "Multiple databases created!"
#fi
#psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" <<-EOSQL
#      CREATE USER $database with encrypted password '$password';
#      CREATE DATABASE $database;
#      GRANT ALL PRIVILEGES ON DATABASE $database TO $database;
#EOSQL

#psql -v ON_ERROR_STOP=1 --username "shop_test" --dbname "shop_test" <<-EOSQL
#    CREATE USER shop_test;
#    CREATE DATABASE shop_test;
#    GRANT ALL PRIVILEGES ON DATABASE shop_test TO shop_test;
#
#EOSQL



# Set the database name and user credentials
DB_NAME="your_database_name"
DB_USER="your_user"
DB_PASSWORD="your_password"

# Create the database
createdb $DB_NAME

# Create the user and grant all privileges to the database
psql -c "CREATE USER $DB_USER WITH PASSWORD '$DB_PASSWORD';"
psql -c "GRANT ALL PRIVILEGES ON DATABASE $DB_NAME TO $DB_USER;"


# Set the database name and user credentials
DB_NAME2="your_database_name2"
DB_USER2="your_use2r"
DB_PASSWORD2="your_password2"

# Create the database
createdb $DB_NAME2

# Create the user and grant all privileges to the database
psql -c "CREATE USER $DB_USER2 WITH PASSWORD '$DB_PASSWORD2';"
psql -c "GRANT ALL PRIVILEGES ON DATABASE $DB_NAME2 TO $DB_USER2;"
