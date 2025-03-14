### Steps

#### Setup
- `symfony new my_project_directory`
- `composer require symfony/maker-bundle --dev`
- `composer require symfony/orm-pack` 

#### Database setup 
- `php bin/console make:entity`
- `php bin/console make:migration`
- `php bin/console doctrine:migrations:migrate`
