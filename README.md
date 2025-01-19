## Setup

- composer install
- php artisan key:generate
- php artisan key:secret
- rename .env.example to .env
- php artisan migrate (after adding database in mysql and .env file)
- php artisan db:seed

## How To Use
- postman collection file (in database folder)
- import postman collection and start the steps
- admin credentials (username :: admin , password :: 123456)

## Testing

- to test project use (php artisan test)
- after finishing test use (php artisan db:seed) again


## How To Add More Payment Gateways

- payment classes passed on strategy pattern which useful to add dynamic payment gateways 
in the future 
- to add  another payment method you have to 
1- add new strategy class in app => Payment => Strategy 
2- add to database payment_methods table the new payment gateway name
3- add the condition of the new payment gateway in app => Payment => PaymentStrategyLink Class

## Additional Notes

- there is an info table in orders table and order_products table and payments table
which helps in case of deleting users so the order will be exists buy user_id=NULL and also if the payment method deleted so the payment of the order will be ... , also the product ...
-(note) we can manage the deleting case by soft delete ...


