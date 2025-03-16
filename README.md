### Steps

#### Run 
- `symfony server:start`
- `docker compose up -d`

#### Setup
- `symfony new my_project_directory`
- `composer require symfony/maker-bundle --dev`
- `composer require symfony/orm-pack` 

#### Database setup 
- `php bin/console make:entity`
- `php bin/console make:migration`
- `php bin/console doctrine:migrations:migrate`

#### Security
[Symfony docs](https://symfony.com/doc/current/security.html)

- `composer require symfony/security-bundle`
- `php bin/console make:user` 
- `php bin/console make:controller`

https://symfony.com/doc/current/security.html#the-firewall
