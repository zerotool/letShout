#########################
# CONTENTS OF THIS FILE #
#########################
 * Introduction
 * Features
 * Implementation
 * Installation and Configuration
 * Running
 * Testing
 * Future Developments
 * FAQ
 * Credits / Contact
 * License
 * Useful Resources
 * Further Documentation


------------
INTRODUCTION
------------
This is letShout application

--------
FEATURES
--------
Rest API service, that simply gets last Twitter message by a given username


--------------
IMPLEMENTATION
--------------
## Built With
PHP


## Libraries
Symfony4

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

2) Then open in browser page like http://localhost:8000/api/get/twitter_username to load last 25 tweets


-------
RUNNING
-------
http://localhost:8000/api/get/twitter_username - load last 25 tweets
http://localhost:8000/api/get/twitter_username/100 - load last 100 tweets


-------
TESTING
-------
## API
N/A


## Code Coverage
N/A


-------------------
FUTURE DEVELOPMENTS
-------------------
N/A


----
FAQ
----
N/A


-----------------
CREDITS / CONTACT
-----------------
st.erokhin@gmail.com


--------
LICENSE
--------
Copyright (c) Stanislav Erokhin


----------------------
FURTHER DOCUMENTATION
----------------------
N/A