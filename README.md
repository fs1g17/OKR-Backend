### Steps

#### Run 
- `symfony server:start`
- `docker compose up -d`
if volume has been deleted, re-run migrations:
- `php bin/console doctrine:migrations:migrate`

#### Setup
- `symfony new my_project_directory`
- `composer require symfony/maker-bundle --dev`
- `composer require symfony/orm-pack` 
(Optional) install profiler:
- `composer require --dev symfony/profiler-pack` 

#### Database setup 
- `php bin/console make:entity`
- `php bin/console make:migration`
- `php bin/console doctrine:migrations:migrate`

#### Security
[Symfony docs](https://symfony.com/doc/current/security.html)

- `composer require symfony/security-bundle`
- `php bin/console make:user` 
- `php bin/console make:controller`

https://medium.com/@agharsaifeddine/set-up-jwt-authentication-with-symfony-using-the-lexikjwtauthenticationbundle-2df8e9170bec
- [generate keys](https://stackoverflow.com/a/78348410)
- `composer require lexik/jwt-authentication-bundle`
- `openssl genrsa -out config/jwt/private.pem`
- `openssl rsa -in config/jwt/private.pem -pubout > config/jwt/public.pem` 

https://github.com/lexik/LexikJWTAuthenticationBundle/issues/646#issuecomment-590422947

I'm here: https://symfony.com/doc/current/security.html#the-firewall
