# detikcom
This is a take home test from Detikcom. It is an API for payment services. This API allow us to create and get payment transaction details.

# Getting Started
## How to run
1. Clone Repository
```
Git clone https://github.com/fakriardian/detikcom.git
```
2. Install package
``` 
composer install 
```
3. Setup environment for database at [env.php](https://github.com/fakriardian/detikcom/blob/main/env.php)
```
$variables = [
    'DB_HOST' => 'localhost',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '',
    'DB_NAME' => 'test',
    'DB_PORT' => 3306,
];
```
4. Create database
```
php ./src/db/createDatabase.php
```
5. Migration table
```
php ./vendor/bin/phinx migrate -e development
```
6. Seeder table
```
php ./vendor/bin/phinx seed:run -s TTransactionSeeder
```
7. Run server
```
php -S localhost:8000
```
8. Update transaction
```
php ./transaction-cli.php {references_id} {status}
```
## Documentation
1. create transaction
```
curl --location --request POST 'http://localhost:8000/create' \
--header 'Content-Type: application/json' \
--data-raw '{
    "invoice_id": "INV-20211025",
    "item_name": "sepatu",
    "amount": 200000,
    "payment_type": "credit_card",
    "customer_name": "uxi",
    "merchant_id": "a8706902-6cd0-42ea-a7a2-83316521ab61"
}'
```
2. get detail transaction
```
curl --location --request GET 'http://localhost:8000/status?references_id=ac79dba1-8bed-48ca-9e03-beb48c754fd9&merchant_id=a8706902-6cd0-42ea-a7a2-83316521ab61'
```
3. get all transaction
```
curl --location --request GET 'http://localhost:8000/status'
```