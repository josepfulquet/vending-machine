# vending machine

## how it works?


### the machine

- Choose and insert coins  
- Push button to buy product  
- Collect the product, and if necessary, the change  
- You can collect your coins

### the service

- By clicking top right "service" button, you can access to service  
- Set the stock of every item and the amount of coins.  
- _Please note that on submit the form, coins with value 1 will be automatically collected ;)_


## install

This Vending Machine is intended to operate in a MAMP-like environment

1. You will need to import the database located in resources/vendingmachine.sql

2. If you use a different environment than the one proposed, you must change path values in the following files:
	- apiUrl   -> vending-machine-test/js/config.js 
	- baseHref -> api.vending-machine/v1/libs/config.php
	
	And if needed, change database credentials in api.vending-machine/v1/libs/config.php