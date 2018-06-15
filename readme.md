## Teamleader Discount API

This is a small discount API built on top of Laravel 5.6 meant to be more like a proof of concept. Here's a small setup guide in the form of a checklist:

- Make sure your vagrant / docker machine runs at least PHP 7.0. 
- After cloning this repository, please run the provision script (bash provision.sh). This script will built a sqlite database and fill it with the necessary data (customers, products, users, discounts, etc). This script will also install and setup Laravel Passport which will be our authorization engine.  
- Once the provision script has been executed, please write down one of the generated client_id / secret combinations since those will be mandatory for obtaining a valid access token later.
- In order have access to a detailed documentation, please clone the [swagger](https://github.com/swagger-api/swagger-ui.git) repository in a separate folder. Once this has been achieved, navigate to this repository's public directory and create a symbolic link between this repo's public directory and the swagger bin directory. It should be something like this: "ln -s /var/www/swagger-ui/dist /var/www/discounts-api/public/docs". Once this is configured correctly, the http://example.test/docs?url=http://example.com/swagger/swagger-v1.json will take you to the swagger documentation.
- You can also setup swagger using the swagger.sh script provided here. This script assumes that the absolute path to your project is /var/www/discounts-api. Please change the script before running it if this is not the case.
- You can setup the default swagger url in the swagger-config.yaml file... The default url should be something like http://example.com/swagger/swagger-v1.json instead of the http://petstore.swagger.io/v2/swagger.json url
- In order to obtain an oauth access token, you may do a POST request at http://example.test/oauth/token. Here's an sample CURL for this: curl --request POST --url http://discounts-api.test/oauth/token --header 'content-type: multipart/form-data; boundary=---011000010111000001101001' --form grant_type=password --form username=tester@teamleader.com --form password=test123 --form client_id=2 --form client_secret=dVWOA41zgftV8b9NAA6QDGPLD9KwOK234bsPSUnz
- All other api endpoints (as documented in swagger) need to be accessed using a Authorization Bearer <token> header.
