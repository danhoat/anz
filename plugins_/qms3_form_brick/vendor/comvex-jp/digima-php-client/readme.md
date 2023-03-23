# Digima PHP Client

## Installation

### Via Composer

> Coming soon

### Standalone

#### Step 1: Download the PHP Client

Download the latest version of the Digima PHP Client at the following link:

```
https://dropbox.digima.com/digima-php-client-latest.zip
```

#### Step 2: Copy the library to your server

Unzip the content of the downloaded archive in a new `digima` folder in your web server.

For example in `/var/www/digima`

#### Step 4: Install Composer

Used standalone, The Digima PHP Client still requires [Composer](https://getcomposer.org/), the popular package manager for PHP.

Download and install Composer following the instructions on the official documentation: `https://getcomposer.org/download/`

> NOTE: There seem to be no official documentation in Japanese.
> An unofficial one can be find at the link bellow but doesn't seem to be up-to-date:
> https://kohkimakimoto.github.io/getcomposer.org_doc_jp/doc/00-intro.html

#### Step 5: Run Composer

Go to the folder where you unarchived the Digima PHP Client and run the following command:

```shell
composer install -o
```

This command will create and autoloader for the client and also install all the necessary packages.

> This can take a few seconds or minutes

Once the operation is done, you can start using the Digima PHP Client.

## Usage

### Web Form submissions

#### Creating a Form

First, the Digima PHP Client `Client` class must be imported into your PHP script:

```php
require_once {PATH_TO_THE_DIGIMA_PHP_CLIENT}.'/Client.php';
```

For example: `require_once '/var/www/digima/Client.php`

Next, instantiate a `Client` object by providing your Digima account code:

```php
$client = new \Digima\Client('YOUR_DIGIMA_ACCOUNT_CODE');
```

We can now create a Form object by providing its Web Form code:

```php
$form = $client->makeForm('YOUR_WEB_FORM_CODE');
```

#### Adding information about the page

You can set the Web Form's page title and URL:

```php
$form->setPageTitle('Contact us');
$form->setPageUrl('https://www.your-domain.com/contact');
```

#### Adding information about the contact groups

Contact groups can be assigned to the contact converted from Web Form data.

You can retrieve the list of groups with `$form->contact()->groups()`.

To assign a contact group to the future contact, use the `push` function:

```php
$form->contact()->groups()->push('2018-01 Contacts');
```

To assign many contact groups to the future contact, use the `pushMany` function:

```php
$form->contact()->groups()->pushMany(array(
    '2018-01 Contacts',
    'Product Site Contacts',
    'Product ABCD Information Requests',
));
```

#### Adding information about the person in charge

The person in charge of the contact is a user registered to your Digima account.

You can assign a person in charge to the future contact with their email address:

```php
$form->contact()->setPersonInChargeEmail('john.doe@example.com');
```

#### Adding information about the contact

Digima contacts have 2 types of fields:

- **Static fields**: Those fields exist for all Digima contacts (For example: `email`, `first_name` and `personal_telephone_number`).
- **Custom fields**: Those fields are additional fields proper to one Digima account that users added by themselves.

You can set those fields by using the `contact()` function of your Form object followed by `staticFields()` or `customFields()`:

```php
// We register some static fields
$form->contact()->staticFields()->setMany(array(
    'email'      => $_POST['email'],
    'first_name' => $_POST['first_name'],
    'last_name'  => $_POST['last_name'],
));

// We register a custom field
$form->contact()->customFields()->set('interest', $_POST['interest']);
```

The list of available static and custom fields can be found at the following URL:

```
https://app.digima.com/web-form/available-fields
```

#### Validating the contact information

To validate the Form data, just call the `validate()` function.

The Form data won't be registered but you can check if there are validation errors:

```php
$response = $form->validate();
```

#### Registering the contact information

If you want to register the Form data on your Digima account, call the `submit()` function:

```php
$response = $form->submit();
```

#### Handling errors

When calling a `validate()` or `submit()` function, the result will be:

- `true` if the request was successful.
- `false` if one or more error occurred.

You can check if one or more errors occurred during the request by calling one of the following functions:

* `hasError()` : Returns `true` if an error occurred.
* `hasStaticFieldError()` : Returns `true` if a validation error occurred for a static field.
* `hasCustomFieldError()` : Returns `true` if a validation error occurred for a custom field.

You can then use one of the following functions to receive a list of errors and check them:

* `getErrors()` : Returns all errors.
* `getStaticFieldErrors()` : Returns all errors concerning static fields.
* `getCustomFieldErrors()` : Returns all errors concerning custom fields.

You can also retrieve errors for a specific field by providing its name:

* `getStaticFieldErrors('email')` : Returns all errors concerning the static field `email`.
* `getCustomFieldErrors('interests')` : Returns all errors concerning the custom field `interests`.

Example:

```php
$form->submit();

if($form->hasError()) {
    // Loop through all the errors to check them
    foreach ($form->getErrors() as $error) {
        switch ($error->getType()) {
            case \Digima\Errors\Error::TYPE_DATA_VALIDATION_ERROR:
                // Handle validation errors
                $field = $error->getField();
                $code = $error->getCode();
                $message = $error->getMessage();
                break;
            case \Digima\Errors\Error::TYPE_REQUEST_ERROR:
                // Handle request error (authorization, service availability, etc.)
                break;
            default:
                // Handle other errors
                break;
        }
    }
}
```

#### Most frequent errors

Each error has a type, a code and a message that you can use to identify the error.

Here is a list of the possible errors:

##### Data validation error

* __type__: \Digima\Errors\Error::TYPE_DATA_VALIDATION_ERROR
* __code__: One of the validation error codes that you can find [here](https://support.digima.com/api/validation-errors)

##### Request unauthorized

* __type__: \Digima\Errors\Error::TYPE_REQUEST_ERROR
* __code__: \Digima\Errors\RequestError::CODE_UNAUTHORIZED

The request was not authorized.
Check the following points:
  - Is the account code valid?
  - Is the Web Form code valid?
  - Is the IP address of the website allowed on your Digima account?

##### Request method not allowed

* __type__: \Digima\Errors\Error::TYPE_REQUEST_ERROR
* __code__: \Digima\Errors\RequestError::CODE_METHOD_NOT_ALLOWED

The HTTP method (GET, POST, PUT or DELETE) is not allowed.
This could happen if the API has changed and the current PHP client is outdated.
Consider upgrading the PHP client.

##### Resource not found

* __type__: \Digima\Errors\Error::TYPE_REQUEST_ERROR
* __code__: \Digima\Errors\RequestError::CODE_RESOURCE_NOT_FOUND

The request URL is invalid.
This could happen if the API has changed and the current PHP client is outdated.
Consider upgrading the PHP client.

##### Service error

* __type__: \Digima\Errors\Error::TYPE_REQUEST_ERROR
* __code__: \Digima\Errors\RequestError::CODE_SERVICE_ERROR

Digima's API could be unavailable.
If the error persists, please contact a Digima representative.

##### Invalid response format

* __type__: \Digima\Errors\Error::TYPE_RESPONSE_ERROR
* __code__: \Digima\Errors\RequestError::CODE_INVALID_RESPONSE_FORMAT

The response format is invalid.
This could happen if the API has changed and the current PHP client is outdated.
Consider upgrading the PHP client.

##### Unknown error

* __type__: \Digima\Errors\Error::TYPE_REQUEST_ERROR
* __code__: \Digima\Errors\RequestError::CODE_UNKNOWN_ERROR

This error occurs when we were not able to precisely identify the cause.
For example, it could be a network connection error.

#### Summary

Here is a summary of all the steps above:

```php
<?php

require_once {PATH_TO_THE_DIGIMA_PHP_CLIENT}.'/Client.php';

// Create a Digima client
$client = new \Digima\Client('YOUR_DIGIMA_ACCOUNT_CODE');

// Create a Web Form
$form = $client->makeForm('YOUR_DIGIMA_FORM_CODE');

// Set the contact static fields
$form->contact()->staticFields()->setMany(array(
    'email'           => $_POST['email'],
    'first_name'      => $_POST['first_name'],
    'first_name_kana' => $_POST['first_name_kana'],
    'last_name'       => $_POST['last_name'],
));

// Set the contact custom fields
$form->contact()->customFields()->set('interest', $_POST['interest'])

// Assign a person in charge
$form->contact()->setPersonInChargeEmail('john.doe@example.com');

// Assign contact groups
$form->contact()->groups()->pushMany(array(
    '2018-01 Contacts',
    'Product Site Contacts',
    'Product ABCD Information Requests',
));

// Submit the form data for registration
$form->submit();

// Check if there are errors and handle them
if($form->hasError()) {
    // Loop through all the errors to check them:
    foreach ($form->getErrors() as $error) {
        switch ($error->getType()) {
            case \Digima\Errors\Error::TYPE_DATA_VALIDATION_ERROR:
                // Handle validation errors
                $field = $error->getField();
                $code = $error->getCode();
                $message = $error->getMessage();
                break;
            case \Digima\Errors\Error::TYPE_REQUEST_ERROR:
                // Handle request error (authorization, service availability, etc.).
                break;
            default:
                // Handle other errors
                break;
        }
    }
}
```
