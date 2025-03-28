# GreenGoodies - Projet 13 - Openclassrooms

## Description

Projet du parcours Développeur d'application PHP/Symfony d'Openclassrooms consistant en la création d'un site e-commerce.

## Dépendances

- PHP 8.0 ou supérieure
- OpenSSL
- Composer
- MySQL ou un autre système de gestion de base de données compatible avec Doctrine

## Installation

1. Cloner le projet sur votre machine
2. Installer les dépendances avec Composer :```composer install```
3. Ajout des variables d'environnements :
   A) DATABASE_URL : Lien vers la BDD utilisée.
   B) JWT_SECRET_KEY : Sous la forme "%kernel.project_dir%/config/jwt/private.pem"
   C) JWT_PUBLIC_KEY : Sous la forme "%kernel.project_dir%/config/jwt/public.pem"
   D) JWT_PASSPHRASE : Une suite de caractères aléatoires

## Initialisation de la base de données

1. Créer la base de donnée :
   ```php bin/console doctrine:database:create```
2. Générer la migration :
   ```php bin/console make:migration```
3. Effectuer la migration :
   ```php bin/console doctrine:migrations:migrate```
4. Charger les fixtures :
   ```php bin/console doctrine:fixtures:load```

## Création des fichiers de configuration JWT

Ouvrez une invite de commande dans le dossier racine du projet et saisissez les commandes suivantes :
   - openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
   - openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

## Utilisation

Vous pouvez lancer un serveur en local ave la commande ```symfony serve```.

------------------------------------------------------------------------------------------------------------------------

# GreenGoodies - Project 13 - Openclassrooms

## Description

A project from the Openclassrooms PHP/Symfony Application Developer path involving the creation of an e-commerce website.

## Dependencies

- PHP 8.0 or higher
- OpenSSL
- Composer
- MySQL or another database management system compatible with Doctrine

## Installation

1. Clone the project on your machine
2. Install dependencies with Composer: ```composer install```
3. Add environment variables:
   A) DATABASE_URL: Link to the database used.
   B) JWT_SECRET_KEY: Path of file like : "%kernel.project_dir%/config/jwt/private.pem"
   C) JWT_PUBLIC_KEY: Path of file like : "%kernel.project_dir%/config/jwt/public.pem"
   D) JWT_PASSPHRASE: A random string of characters

## Database Initialization

1. Create the database:
   ```php bin/console doctrine:database:create```
2. Generate migration:
   ```php bin/console make:migration```
3. Execute migration:
   ```php bin/console doctrine:migrations:migrate```
4. Load fixtures:
   ```php bin/console doctrine:fixtures:load```

## Creating JWT Configuration Files

Open a command prompt in the project's root directory and enter the following commands:
   - openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
   - openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

## Usage

You can start a local server with the command ```symfony serve```.

