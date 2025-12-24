# 📚 Virtual Library (Symfony 8.0)
API for managing books and authors.

## 🛠 Setup
1. `git clone git@github.com:patryk-lukasz-kacprowicz/symfony-library-application.git`
2. `composer install`
3. Configure `DATABASE_URL` in `.env`
4. `php bin/console doctrine:database:create`
5. `php bin/console doctrine:migrations:migrate`

## 🚀 Run
`symfony serve` or `php -S localhost:8000 -t public`

Docs: `http://localhost:8000/`

## 🛠 Tech Stack
- PHP 8.4
- Symfony 8.0
- Doctrine ORM (MySQL)
- Swagger / OpenAPI

## 📝 License
MIT License
