# Inspector Project

This is a tool built with Symfony that enables inspectors to assign jobs to themselves in different locations. After the job is finished, they complete it through an API.

## Getting Started

### Prerequisites

Before running this application, make sure you have the following installed:

- PHP 7.4
- Composer
- Symfony 5.8
- Postman
- XAMPP (or any other local development environment with MySQL support)

### Installation

1. Install the dependencies:
    ```bash
    composer install
    ```

2. Copy the `.env.example` file and set your database credentials:
    ```bash
    cp .env.example .env
    ```

3. Create the database, run the migrations, and load data fixtures:
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    bin/console doctrine:fixtures:load
    ```

4. Start the Symfony server:
    ```bash
    symfony server:start
    ```

5. Navigate to the application:
    ```bash
    http://localhost:8000
    ```

6. Test the API with Postman:
    ```bash
    http://localhost:8000/api/
    ```

## License

This project is licensed under the MIT License. See the [MIT license](https://opensource.org/license/mit) file for details.
