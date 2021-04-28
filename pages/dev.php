<?php
$titre='information de developpement du site';
?>
<h3>PROPOSITIONS REÇUES</h3>
<ul>
<li>Pouvoir enregistrer la page en tant qu'image (enfin on peut toujours faire une capture d'écran, mais c'est plus sympa si on a juste à cliquer/enregistrer sous)</li>
<li>Mettre des majuscules à chaque mot "seul" (masculin/féminin, connection/déconnection, etc) mais c'est vraiment une amélioration minime</li> 
<li>Ajouter des images pour chaque arme (très fastidieux, enfin ça égaierai le tout)</li>
<li>wiki du jdr</li>
</ul>

<!--
<h3>PROJETS À LONG TERME</h3>

<ul>

<li>gestion du passage de niveau</li>
<li>choix de la fiche secondaire indépendamment du métier</li>
<li>objets communautaires (mise en avant)</li>
<li>liste des items par type de perso</li>
</ul>

<h3>À FAIRE À COURT TERME</h3>

<ul>
<li></li>
</ul>
-->

<h3>BUGS À REGLER</h3>

<ul>
<li>newsletter non-fonctionnelle</li>
</ul>

<!--<div class="progress progress-striped active">
  <div class="bar" style="width: 40%;"></div>
</div>-->


<h3>CHANGELOG</h3>

<pre>
-b1 :
+ delete_item en ajax + animation
+ protection totale dans la fiche perso web
* url du menu horizontal valides
* bug du compteur d'utilisateur
* pages innaccessibles si le nom du personnage/item est un nombre entier

-b2 :
+ unifier le système de rupture
+ gestion équipé ou non dans delete_item en ajax (A VERIFIER)
+ ajout de la description dans les fiches de personnage web

-b3 :
+ amélioration de l'affichage dans la fiche de personnage web (ajout de marge entre les colonnes de texte)
* suppression de la catégorie des objets dans la fiche de personnage finale (pour éviter les textes trop longs)

-b4 :
+ mise en place du système "qui est en ligne" désactivable dans les options
+ fonction d'ajout d'objets multipersonnages via un bouton à sous-menu
+ ajout d'un système + ou - pour la gestion de la quantité des objets
+ passage sous bootstrap
	* refonte aspect général du site
	* refonte de la page d'accueil
	* refonte page d'un personnage
*correction d'un bug concernant la fonction de recherche

-b5 :
+ système de favoris
+ ajout du module compagnie
* correction de la recherche avancée

-b6 :
+ mail visible pour les admins si caché
+ ajout du compteur de personnage sur la page "profil" d'un membre
* mise à jour des compétences
* mise à jour des armes de bases
* correction orthographique mineure
* correction des liens vers le module "objet" du menu latéral.
* l'accès au cache n'est plus fermée aux non-administrateurs.
* des \ apparaissent avant les " et les ' sur certaines pages, ce problème devrait être désormais réglé.

-b7 :
+ ajout dynamique d'un objet depuis la fiche web par son nom
+ actions de groupe dans le module compagnie
+ système de modification dynamique des quantités d'objets sur la fiche perso web
+ ajout du compteur d'objet et de compagnie sur la page "profil" d'un membre
* amélioration de l'affichage détaillé des objets dans la fiche web
* La recherche devrait fonctionner à nouveau
* clarification des limites de tailles ou de format pour les images du personnage et du profil, qui n'étaient pas précisées.
* les favoris qui font référence à un élément supprimé n'apparaissent plus
* ajout d'une aide sur la fiche de personnage web
* Certaines caractéristiques non-modifiables fonctionnent maintenant correctement
* revue complète du système de création d'objets.
* Ajout d'un titre à la page lors de la validation de l'inscription
* Mauvaise redirection après connexion depuis la page d'inscription
* allègement du css du site

-b8 :
+ ajout fiches secondaires
+ ajout de commentaires aux news
+ création d'une banque d'avatar officiels
+ les champs comme la protection totale ou l'or total sont mis à jour autoatiquement lors de la modification d'un personnage
+ le compte de test est désormais remis à 0 tous les jours (sauf les message sur le livre d'or)
* optimisation de la génération et de l'affichage des fiches officielles(temps divisé par 2 ou 3)
* amélioration des fenêtres modales
* Les valeurs MagiePsy et MagiePhys ne sont plus affichées si la valeur d'énergie astrale est égale à 0.
* Les antislashes sont défénitivement supprimés de la base de donnée, ainsi que tous les bugs qui y était lié
* ajouter un personnage 2 fois de suite à une même compagnie ne provoque plus d'erreur

- b9:
Nouvelles sections :
----+ ajout d'une section faq
----+ ajout d'une section "ressources" pour référencer dynamiquement les ressources officielle du jeu de rôle de manière compacte
Ergonomie :
----+ les compétences s'affichent désormais sur la fiche graphique (limité à 4 pour l'instant, 8 dans le futur)
----+ ajout d'un champ d'ajout par nom sur la fiche personnage
----+ ajout d'un module de création et d'ajout rapide de nouveau contenu à votre personnage
----+ ajout de la liste générale des personnages et des compagnies dans leur section respective
----+ option de flux de suggestion avancé sur le menu horizontal
----+ il est désormais possible de suggérer un contenu officiel (merci de ne pas en abuser ^^)
+ création d'une interface normalisée ajax

* mise en cache des suggestions de recherche pour accélérer leur affichage
* les armes sont désormais équipées par défaut à leur ajout
* les prix des objets peuvent désormais être des valeurs à virgule
* correction de l'affichage des polices officielles
* correction de l'affichage des objets généraux
* correction de l'ajout d'un personnage à une compagnie
* correction de l'affichage des objets équipés sur la fiche graphique
* suppression de l'affichage de la fin du texte en l'absence d'objet sur la fiche graphique
* on peut à nouveau modifier une compagnie créée
* correction d'une erreur de la gestion des droits de modification d'un personnage
- mail avertissement pb bdd
- compteur de commentaire
- antislashs dans les commentaires

- b10:
+ le nombre de compétences affichées sur la fiche graphique passe de 4 à 13
+ mise en place du module de vote (inutile pour le moment mais vu qu'il était fini je l'ai ajouté)
+ ajout d'information sur le créateur d'un personnage lors de son affichage
* suppression des doublons sur la fiche graphique principale par rapport aux objets de la fiche secondaire (dites moi si il reste des bugs)

- b11:
+ ajout d'un système de messagerie
+ ajout de l'intégration de gravatar
+ ajout de l'affichage des modificateurs sur la fiche de personnage graphique
* la fiche graphique principale ne contient plus de champ inutile
* le nombre de compétences affichées sur la fiche graphique passe de 13 à 12

-b12 :
+ ajout d'une gestion plus fine des droits sur les personnages
+ la FAQ se remplit doucement
* correction d'erreurs lors de la modification, la suppression et l'ajout d'objets depuis la page personnage
* correction de bugs divers
* suppression temporaire de la fonction de création rapide depuis la page d'un personnage

- b13 :
+ Ajout d'un système de mp global pour les administrateurs (plus diverses notifications)
+ ajout d'un système de réponse pour l'admin dans le livre d'or (pour plus de clarté)
+ Nouvelle version de la page de personnage
+ Chaque compagnie dispose désormais d'un chat privé sur sa page (le fondateur de la compagnie est op)
* css/js allégé

- b14 :
+ ajout d'un champ de spécialisation
+ ajout d'une page récapitulative des droits de vos personnages
+ il est possible de spécifier une valeur personnalisée différente de celles les plus fréquentes pour le métier/origine/spécialisation des personnages et le type des objets
* les catégories vides n'apparaissent plus dans la fiche de personnage web

- b15 :
+ ajout du rang "validateur"
+ retour du système d'ajout par nom depuis la fiche personnage
+ ajout d'une bibliothèque personnelle d'images qui peuvent être redimensionnée pour votre avatar ou vos personnage (- de 1 Mo par image et 12 images maximum)
+  il est désormais possible de proposer un objet officiel après sa création depuis sa page
+ Un motif peut être indiqué lors du refus d'un objet officiel
+ ajout d'une fonction "copier dans le presse papier" pour les fiches de personnage graphiques
* correction bug d'affichage des messages lors de réponses multiples

- b16 :
+ la page personnage vérifie si celui-ci est à jour automatiquement
+ amélioration du système d'ajout d'objets
+ on peut désormais choisir l'attaque et la parade dès la création d'un personnage
+ la recherche avancée permet le tri par catégorie (une seule pour l'instant)
+ ajout de la date de dernière modification sur la fiche personnage
* objets non-équipés par défaut
* la liste officielle des objets se met correctementà jour

- b17 :
+ le fait qu'un admin ait répondu est désormais signalé dans l'espace dernier commentaire du menu
+ le nombre de personne connectées sur le chat est désormais affiché dans le menu
+ ajout d'une surprise aléatoire (très rare)
* le système d'oubli de mot de passe vous laisse en choisir un nouveau plutôt que d'en générer un
* correction d'un bug majeur qui empéchait l'affichage d'informations en pointant sur une page sans style

- b18 :
+ Ajout du champ "recit" pour les compagnies
+ Création d'un système de butin pour isoler les objets qui vous semblent inutiles
+ lors de la création d'un personnage les compétences héritées sont automatiqement ajoutées
+ ajout d'un tirage aléatoire de caractéristiques à la création d'un personnage
+ ajout d'une fonction de lancer de dé dans le chat
+ clarification de la fiche personnage
+ transfert de personnage ajouté (section droit)
+ possibilité de créer des PNJ
+ Les objets officiels sont désormais uniques
+ on peut désormais ajouter des membres qui auront accès au chat de la compagnie sans qu'un personnage leur appartenant n'en fasse partie.
* refonte de la gestion des quantités d'objets
* allègement de l'affichage du code forum
* suppression des messages de join/quit du chat pour une meilleure visibilité
* les actions de groupe n'affectent plus les personnages lorsque le MJ n'a pas les droits suffisants
</pre>