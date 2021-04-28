# Repository dédié au site web "DonjonFacile.fr"
> Site communautaire autour du jeu de rôle Naheulbeuk de 2012 à 2021.
Il permettait de créer des fiches de personnages de ce JDR sous format jpg.
Un base de donnée de près de 13000 objets, 13000 personnages, 100 compagnies et 32000 utilisateurs (dont bon nombre de bots...)

## Code du site web
Le code complet en PHP/JS/HML/CSS est disponible en l'état.
Ce code a été peu maintenu et documenté. En cas de volonté de reprise serieuse, me contacter.
Ce site se voulant communautaire, de nombreuses fonctions annexes (profil, messagerie, gestion de droits et de compagnie) sont présentes.

Le coeur du site étant la génération de fiche de personnage, je pointe en particulier vers les fichiers suivants qui gèrent cette partie :
- /pages/fiche.php
- /pages/fiche_advanced.php

## Données/BDD
La base de donnée du site est disponible dans le dossier __DATABASES__ sous les formats suivants :
- ods
- xlsx (exporté depuis ods, sans garantie de qualité)
- structure SQL du site
Il n'est pas censé rester de données personnelle autre que les pseudos des utilisateurs. Si j'ai fait un oubli, je vous invite à me le signaler, ce n'est pas le but.

La structure de donnée est assez simple, avec une table par type de data (objet, personnage, user, compagine) et des tables liant les ID entre eux.