# BanguBank Admin Credentials For All Type of Storages

Email - admin@admin.com
Password - admin@1234

# BanguBank User Credentials For All Type of Storages

Email - sakib@gmail.com
Password - 12345678

Email - saad@gmail.com
Password - 12345678

# MySql Database Information

### $serverName = "localhost";
### $dbName = "bangubank_db";
### $userName = "root";
### $password = "root";

## There are two tables in the mysql database -> users, transactions

## SQL For Creating Users table

CREATE TABLE users (id VARCHAR(50), name VARCHAR(50), email VARCHAR(50), password VARCHAR(100), role VARCHAR(20), balance DOUBLE(25,2));

## SQL For Creating Transactions table

CREATE TABLE transactions (sender_email VARCHAR(100), sender_name VARCHAR(50), receiver_email VARCHAR(100), transfer_amount DOUBLE(25,2), transfer_time VARCHAR(40), transfer_type VARCHAR(20));

