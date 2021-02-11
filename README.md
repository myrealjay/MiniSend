# MiniSend


## Description

This is a simple transactional email sending service

## Installation
Clone the repository and run composer install, cp .env.example .env, configure your database parameters in the .env file and email server settings.

Run php artisan key:generate, php artisan jwt:secret
you need to have redis installed in you system,
set up

```
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

you need to generate
```ENCRYPTION_KEY=14bee1b7d0011fe4b6b452c7b1fbb5ef6a185bf0c1aad08e9fed61142065d4cf```

youuu can just place that one in your .env file or generate a new key of that length

run migration php artisan migrate

run ```npm install``` to install node dependencies

run ```php artisan serve```

visit http://127.0.0.1:8000 to see the application. you can register to start testing out the application features.

you can send an email directly from the front-end using the provided form.

to send email using api call simply send an ajax call to this url:

### Url

```
/api/mails/send
```
### Method
POST

### Headers
Authorization: ```Bearer Token```
minisend-key: ```Your API key```

you can find the API in your dashboard by cllicking of the b utton too display it, just copy it and place in the header of the request as above.

### Parameters

```
from_email [string]
from_name [string]
to_email [string]
to_name [string]
subject [string]
text_content [string]
html_content [string]
attachments [array] array of blobs
```
a success response will be sent back

youu can see the status of the emails sent when you login to the dashboard.


