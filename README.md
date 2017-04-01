# Installer l'application Videodorant

Voici le manuel d'installation pour utiliser notre application Videodorant.

##### 1 Télécharger le projet
Vous pouvez récupérer manuellement le projet en utilisant la commande git clone, ou en téléchargement directement le .zip sur le github.
Puis, utilisez ```composer install```
```
$ git clone git@github.com:Kebatoufragile/Videodorant.git
$ composer install
```

##### 2 Permissions
Après avoir fini d'installer les dépendances, assurez vous de bien changer les permissions.
```
$ chmod -R 777 storage
$ chmod 666 config/database.php
```

##### 3 Configurer la base de données
Éditez le fichier ```config/database.php``` pour bien mettre vos identifiants de base de données. A la ligne ``` 'database'```, assurez vous de bien mettre le nom 'Videodorant'

##### 4 Installer la base de données
Avant d'utiliser la commande qui suit, veuillez vous assurer d'avoir bien créée préalablement une base de données vide nommée Videodorant dans votre gestionnaire de base de données.
```
$ php migrate
```

##### 5 Lancez votre serveur

### Vous pouvez dès à présent accéder à notre application Videodorant depuis votre localhost !
