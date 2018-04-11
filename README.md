MailerLite Subscribers DEMO

This is a simple API endpoint and subscriber insert/update form. It is not intended to perform any 3rd party API/MailerLite API external calls and is a standalone external application with integration potential.

INSTALLATION:
    -Locate Constants.php in the includes directory
    -Adjust DEVELOPMENT and LOCALHOST variables as needed
    -ADJUST DB_NAME, DB_USER, DB_PASS (and DB_HOST if necessary) for the appropriate environment
    -Adjust the defined SERVERADDRESS variable to the root address of the installation. I've adjusted my hosts file to add mailerlitedev.com, so this is obviously NOT a real address.
    -Adjust the defined SERVERPATH variable to the path location of the project. This is used to properly include files instead of by reference.

FIRST RUN:
    -Visit the SERVERADDRESS and choose to install the required tables
    -If it fails verify the credentials are set in the MYSQL users table correctly

USAGE:
    -The first step would be to insert a new user. On failure, a rudimentary error description is provided. Correct the error, and try again.
    -Once a subscriber is created, update them under the "Subscriber Update Form" tab

API Endpoint:
    -The API endpoint is very basic and is insert only.

Development checklist:

Subscribers & Fields API resources
     - Your task is to create an HTTP API backend service for two resources and their relations: subscribers and fields.

Minimum validation rules for a single subscriber
     - Email must be in valid format and host domain must be active. - COMPLETE
     - Do not reactivate subscribers when creating/updating it implicitly. - COMPLETE

General requirements
    -Any PHP framework - used custom "semi-from-scratch" framewwork
    -HTTP JSON API - using JSON for all transactions
    -ORM and relationships/associations - MYSQL relates accordingly
    -Validating requests - validation is handled in object instantiation prior to insertion/update
    -Usage of other framework features: migrations, seeders, tests, etc - ......sort...of...
    -Instructions how to run a project on local environment - this file named README.md
    -PSR-2 compliant source code - complete

Updates:
-Corrected Tab redirection for "no subscribers" condition
-Corrected syntax error in SubscriberCore.php for domain validation
-Corrected redundant "endpoint ready" message
-Corrected email domain resolution condition to reflect dual failure on 301 redirects for popular domains. This benefits smaller domains, but could be a weakness.
-Corrected padding on meta box entries