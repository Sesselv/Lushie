# Lushie

Lushie est une plateforme web dédiée à la comparaison, la notation et la gestion de savons naturels. Elle permet aux utilisateurs de découvrir des produits, de donner leur avis, de gérer leurs favoris et d'interagir avec la communauté.

---

## Sommaire

-   [Présentation](#présentation)
-   [Fonctionnalités](#fonctionnalités)
-   [Technologies utilisées](#technologies-utilisées)
-   [Architecture](#architecture)
-   [Installation](#installation)
-   [Utilisation](#utilisation)
-   [Structure des fichiers](#structure-des-fichiers)
-   [Auteur](#auteur)

---

## Présentation

Lushie propose une expérience interactive autour des savons naturels : comparaison, notation par étoiles, gestion des favoris, commentaires, espace administrateur et plus encore.

---

## Fonctionnalités

-   Inscription et connexion utilisateur
-   Ajout et suppression de favoris
-   Système de notation par étoiles
-   Comparateur de savons
-   Gestion des commentaires
-   Interface administrateur (gestion des utilisateurs, savons, articles)
-   Navigation rapide grâce à Turbo
-   Animation et interactions dynamiques (barre animée, dropdown, burger menu)

---

## Technologies utilisées

### Langages

-   **HTML** (structure des pages via Twig)
-   **SCSS / CSS** (styles modulaires)
-   **JavaScript** (interactions dynamiques)
-   **PHP** (logique serveur)

### Frameworks / Bibliothèques

-   **Symfony 7.3** (framework principal)
-   **Twig** (moteur de templates)
-   **Doctrine ORM** (gestion base de données)
-   **Symfony UX Turbo** (navigation rapide)
-   **Symfony UX Stimulus** (contrôleurs JS)

### Outils

-   **AssetMapper** (gestion des assets)
-   **Composer** (dépendances PHP)
-   **Git** (gestion de versions)
-   **Visual Studio Code** (IDE)
-   **PowerShell / Symfony CLI** (ligne de commande)

### Base de données

-   **MySQL**

---

## Architecture

-   **Front-end** : Twig, SCSS, JavaScript (modulaire par fonctionnalité)
-   **Back-end** : Symfony (MVC), Doctrine ORM
-   **Base de données** : MySQL
-   **API** : Endpoints AJAX pour notation, favoris, etc.

---

## Installation

1. Cloner le projet :
    ```bash
    git clone <url-du-repo>
    ```
2. Installer les dépendances PHP :
    ```bash
    composer install
    ```
3. Configurer la base de données dans `.env.local`
4. Lancer les migrations :
    ```bash
    php bin/console doctrine:migrations:migrate
    ```
5. Compiler les assets :
    ```bash
    symfony asset:install
    ```
6. Démarrer le serveur :
    ```bash
    symfony serve
    ```

---

## Utilisation

-   Accédez à la page d'accueil pour découvrir les savons
-   Inscrivez-vous pour accéder aux fonctionnalités avancées (favoris, notation, commentaires)
-   Utilisez le comparateur pour comparer deux savons
-   Gérez vos favoris et notes depuis votre espace utilisateur
-   L'espace admin permet la gestion des contenus et utilisateurs

---

## Auteur

Projet réalisé par Selvi BEKAR dans le cadre de Simplon.
