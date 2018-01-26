------------
INTRODUCTION
------------
This is letShout application repository

--------
FEATURES
--------
Rest API service, that simply gets last N Twitter messages by a given username


--------------
IMPLEMENTATION
--------------
## Built With
PHP

## Libraries
Symfony4, PHP Unit

## Timing
It took me 6 hours to finish this project. Estimation was 3 hours. Spent a lot of time adding fancy cache feature,
cool unit tests, polishing the Code and creating this exciting GIT repo 😁

------------------------------
INSTALLATION AND CONFIGURATION
------------------------------

1) Run commands in console:

```
mkdir letShout
cd letShout
git clone https://github.com/zerotool/letShout.git .
composer update
php -S 127.0.0.1:8000 -t public
```

2) Then open in browser page like http://localhost:8000/api/get/microsoft to load last 25 tweets for username 
'microsoft'

Don't forget to change your Twitter Application credentials in `config/services.yaml`. You can obtain them here 
https://apps.twitter.com/

-------
RUNNING
-------
* http://localhost:8000/api/get/microsoft - load last 25 tweets
* http://localhost:8000/api/get/microsoft/100 - load last 100 tweets


-------
TESTING
-------
To run tests execute in terminal:

```
php bin/phpunit
```

-----------------
CREDITS / CONTACT
-----------------
st.erokhin@gmail.com


--------
LICENSE
--------
Copyright (c) Stanislav Erokhin

