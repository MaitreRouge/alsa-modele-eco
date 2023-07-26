# Alsa-modele-eco

## Presentation

alsa-modele-eco est un logiciel développé sous la forme d'un SAAS (Software as a Service) et qui sert à Alsatis pour créer des demandes de prix. Ce logiciel est destiné aux commerciaux et aux agents de l'avant-vente

## Techs

Pour ce projet, j'ai utilisé PHP ``v8.0.2`` et le [framework Laravel](https://laravel.com/) ``v9.6.0``.

## Use the app

Une version de sandbox est disponible à cet adresse : [https://r.mrkm.dev/alsatis/saas](https://r.mrkm.dev/alsatis/saas)

Mais vous pouvez aussi cloner le projet et setup l'environment (``.env``)

## Exploration des fichiers

### Packages 

Vous pouvez trouver un peu de mon code partout, si vous voulez savoir où est quoi, vous pouvez jeter un coup d'œil à l'arborescence des fichiers.

Pour vérifier tous les paquets, une liste est disponible sous ``./composer.json``

### Arborescence des fichiers 

Comme nous le savons, tout le monde n'est pas familier avec laravel ni avec la façon dont j'ai décidé de l'utiliser pour ce projet, je vais donc expliquer l'arborescence de fichiers
Si vous trouvez un fichier dans le projet qui n'est pas listé ici, c'est que je ne l'ai pas vraiment créé et que je l'utilise pas. 

    /app
        /Console
        /Exceptions
        /Http
            /Controllers => Dossier où se trouve toute la logique
                /ChangelogController.php   => Fichier dans lequel se trouve toute la logique des changelogs
                /EntrepriseController.php  => Fichier dans lequel se trouve toute la logique des entreprises (devis)
                /ExampleController.php     => Fichier d'exemple
                /ExportController.php      => Fichier dans lequel se trouve toute la logique des exports
                /PrestationsController.php => Fichier dans lequel se trouve toute la logique des prestations
                /UserController.php        => Fichier dans lequel se trouve toute la logique des utilisateurs 
            /Middleware => Dossier dans lequel sont stockés les modules qui interceptent la requête et vérifient certains paramètres.
        /Models => Dosier dans lequel se trouve les modèles utilisés par l'application
        /Providers
    /bootstrap
    /config => Tous les fichiers de config
    /database
        /factories => Dossier dans lequel on stocke les "fatories" (usier pour créer des "faux" enregistrements dans la bdd)
        /migrations => Fichiers spécifiant comment chaque table de la base de données doit être créée
        /seeders => Fichier exécuté pour "ensemencer" la base de données avec toutes les "factories"
    /lang
    /public
    /ressources => Dossier stockant toutes les ressources publiques (css/js/html)
        /css
        /js
        /views => Dossier stockant toutes les vues de notre application (le frontend)
    /routes => Notre routeur, on déclare toutes les routes possibles et ce qui doit être fait
        /web.php => Toutes les URLs "publiques"
    /storage
    /tests
    .env.example => Exemple du fichier d'environement qui s'occupe de tout ce qui est sensible
