<p align="center"><a href="https://thiio.com/" target="_blank"><img src="https://storage.googleapis.com/thiio.thiiomedia.com/Page/4/thiio.png" width="400" alt="Thiio"></a></p>

## About this proyect

Thiio_test is the application for skill testing.

This is the exercise that was laid in order to prove my skillset with [Laravel Framework](https://laravel.com/) and [Vuetify](https://vuetifyjs.com/)

## The exercise

Develop a simple yet robust web application that allows for managing user accounts. This includes functionalities for user registration, login, viewing, editing, and deleting user profiles. For this it will be used: PHP/Laravel, Vue.js with Vuetify, MySQL, JWT (JSON Web Tokens) for authentication and PHPUnit for the TDD approach.
- Requirements

**Requirements**

- Design and implement RESTful APIs for user management (Create, Read, Update, Delete).
- Use Laravel to develop these APIs following the TDD approach.
- Ensure API endpoints are secure and accessible only to authenticated users.
- Develop the frontend using Vue.js and Vuetify.
- Implement a login page that authenticates users through the API.
- Create a dashboard for viewing, creating, editing, and deleting users. This should be accessible only to authenticated users.
- Implement form validations on both frontend and backend to ensure data integrity.
- Implement secure login functionality.
- Use JWT to manage user sessions and protect API routes.
- Write comprehensive unit and feature tests using PHPUnit.
- Tests must cover API endpoints, authentication mechanisms, and front-end interactions.

## Configurations

It is presumend that the environment has *PHP*, *MySql*, a webserver (probably *Apache*) operating and the proyect cloned from the repository.

With that in mind, this configuration has as starting the /thiio_test directory in the local server.

### Preparing *.env*

Since the proyect was created using Laravel with the breeze starting kit and keeping the default migrations it comes with, the first actions that must be done is to generate a ***.env*** file copying ***.env.example*** file and changing it's name. Then prepare the ***.env*** file with the credentials of the environment. Open it and adjust the variables. The variables that must be ajusted are:

- *DB_HOST*=127.0.0.1
- *DB_PORT*=3306
- *DB_DATABASE*=thiio_test
- *DB_USERNAME*=root
- *DB_PASSWORD*=

If the steps where taken according to this instructions, the variable *DB_CONNECTION* should not be altered and must have the value 'mysql'.

If you will start your server with *php artisan serve* command, then you're ready to go. If your'e server will be started in another url different from *http://localhost:8000/*, then you must adjust the next variable:

- *APP_URL*=YOUR_URL_GOES_HERE

With that, you'll be ready to go.

### Install dependencies

After that, we can install the dependencies that laravel and vuetify requires in order to function properly. Firstly, execute the command *composer install*. With that, our Laravel API will start to download and prepare the required libreries.

When it finishes, we must enter in the directory /frontend and execute the command *npm install* in order to prepare our frontend with the required classes and libreries.

At this point, we are ready to prepare our database.

### Execute migrations

The next step is prepare the database. In order to do that, we must execute the next command in our command-line *php artisan migrate*. This command will create every table needed for the system to work.

When this process is done, we'll have all our tables created.

### NOTES

You have everything ready to start using this program, but here are some extra advices in order to have a little starter.

- If you like to start with some data already loaded in your database, you might like to excecute the command *php artisan db:seed* in order to populate your database with some testing users. This action will create an "admin" (username: 'admin@email.com', password: '123456789') and some "guests" in the system.
- You could always reconfigure your database for your own purpouses.
- If you want to run the Tests created for this application, you could excecute the command *php artisan test*.