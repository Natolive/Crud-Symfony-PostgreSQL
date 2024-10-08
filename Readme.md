# Crud Symfony PostgreSQL

## Description

Projet avec un crud simple backend api en Symfony/PostgreSQL

## Installation

### Composer
Installation des paquets composer: `composer install`

### Database
Installation de la stack docker avec la base de données: `docker compose up -d`

### Doctrine
Lancement des migrations: `php bin/console doctrine:migrations:migrate`

### Symfony
Lancement du serveur local: `symfony server:start --no-tls`