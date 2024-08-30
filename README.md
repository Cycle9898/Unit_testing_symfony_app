# Fichier README.md initial

-   Avoir installé php 8 avec ses dépendances
-   Installer composer
-   Lancer un composer install après avoir récupéré le projet `composer install`

# Ajouts

Le but de ce projet est d'écrire et de faire passer des tests unitaires dans une application Symfony existante à l'aide de PHPUnit.

C'est principalement la méthode de connexion au compte GitHub qui a été testée et une classe Produit.

Les tests écrits se trouve dans le dossier tests du projet.

On lance les tests à l'aide de la commande :

```
vendor/bin/phpunit
```

Les résultats des tests apparaissent alors à l'écran.

Ces tests s'exécute également lorsqu'on push du code sur GitHub grâce à une CI mise en place avec les GitHub actions.

Le fichier de configuration YAML de celle-ci est visible dans le dossier .github/workflows.
