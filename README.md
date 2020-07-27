# PHP RestAPI Boilerplate
Template for simple rest api in php \

### Authorization
In order to authorize with a client on api calls, you need to send an Authorization header with a ``Bearer Token``. That could look like this: 
```
Authorization: Bearer YOUR_TOKEN_HERE
```
The api then checks on endpoints, that require authorization, for a valid token in the database. For that you will have to provide a mysql table called ``ts_access_tokens``. The structure should be as follows:
```
id - The user's ID
token - Access token for the user's session
expiry - Expiration date in milliseconds
```
