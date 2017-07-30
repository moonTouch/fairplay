# Application mini-blog
========

A Symfony project created on July 28, 2017, 1:07 pm.

## Configuration

- Paramétrer un compte email dans parameters.yml

## Fonctionnalités

- Développé avec la version 3.3.5 de Symfony, et un thème bootstrap.

- Création des fixtures, qui permettent de visualiser le projet avec une liste d'utilisateurs enregistrés et des articles associés : `bin/console doctrine:fixtures:load`

- Activation de FOSUserBundle pour la gestion des utilisateurs : validation de l'enregistrement par email, page de profil permettant de modifier son username, mot de passe et adresse email

- Date de dernière connexion enregistrée : il s'agit plus précisément de l'avant dernière date de connexion (afficher le last_login issu de FOSUser aurait été, à mon sens, inutile : cela aurait affiché la date et l'heure actuelle !)

- Génération CRUD de l'entité Article.

- Un utilisateur enregistré peut créer un article, le modifier ou le supprimer (s'il en est l'auteur)

- Installation de KNPPaginator pour lister tous les articles présents en base de données

- Création d'une page contact avec service de Swiftmailer

- J'ai commencé la traduction du site sur la page de contact, pour les label et les messages de validation