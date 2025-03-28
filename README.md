# GreenGoodies - Projet 13 - Openclassrooms

## Description

Dernier projet du parcours Développeur d'application PHP/Symfony d'Openclassrooms.

## Dépendances

- PHP 8.0 ou supérieure
- Composer
- MySQL ou un autre système de gestion de base de données compatible avec Doctrine

## Installation

1. Cloner le projet sur votre machine
2. Installer les dépendances avec Composer :```composer install```
3. Ajouter l'url de la base de données dans vos variables d'environnement (DATABASE_URL)

## Initialisation de la base de données

1. Créer la base de donnée :
   ```php bin/console doctrine:database:create```
2. Générer la migration :
   ```php bin/console make:migration```
3. Effectuer la migration :
   ```php bin/console doctrine:migrations:migrate```
4. Charger les fixtures :
   ```php bin/console doctrine:fixtures:load```

## Utilisation

Vous pouvez lancer un serveur en local ave la commande ```symfony serve```.

------------------------------------------------------------------------------------------------------------------------

# GreenGoodies - Project 13 - Openclassrooms

## Description

Last project of the PHP/Symfony Application Developer track on Openclassrooms.

## Dependencies

- PHP 8.0 or higher
- Composer
- MySQL or another database management system compatible with Doctrine

## Installation

1. Clone the project on your machine
2. Install dependencies with Composer: ```composer install```
3. Add the database URL to your environment variables (DATABASE_URL)

## Database Initialization

1. Create the database:
   ```php bin/console doctrine:database:create```
2. Generate the migration:
   ```php bin/console make:migration```
3. Perform the migration:
   ```php bin/console doctrine:migrations:migrate```
4. Load the fixtures:
   ```php bin/console doctrine:fixtures:load```

## Usage

You can start a local server with the command ```symfony serve```.

