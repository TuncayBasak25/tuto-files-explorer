# Tutoriel: Explorateur de fichiers en PHP


Afficher le contenu du répertoire ;

Je créé une variable $cwd qui contient la fonction getcwd() (qui  retourne l'url du dossier de travail courant).

  <code>$cwd = getcwd();</code>

Je m'assure du résultat en faisant un echo :

  <code>echo $cwd;</code>

Création d'une variable $allContents qui va contenir le résultat du scandir() sur la variable $cwd

Avec scandir(), lister les fichiers du répertoire et les enregistrer dans une variable sous forme d'array



Le script ouvre un enfant du rep dans lequel il se trouve

Faire en sorte que .. et . n’apparaissent pas


pouvoir se promener dans l'arborescence(ouvrir des dossiers enfants, remonter au parent, etc.) ;



Ne pas remonter plus haut dans l’arborescence

afficher   un fil d'arianne (breadcrumb) pour se repérer dans l'arborescence ;



trier les fichiers par nom / taille / type / date de création ;



Bouton pour classer les fichiers par nom / taille / type / date de création ;



option afficher/masquer les fichiers cachés ;


ouvrir des fichiers ;       


Modifier des fichiers ;


Couper/copier/coller les fichiers ;


supprimer des fichiers ;


Créer un nouveau dossier dans le répertoire où l'on est positionné ;



Créer un nouveau fichier  dans le répertoire où l'on est positionné ;



les fichiers supprimés vont dans une corbeille ;


restaurer les fichiers depuis la corbeille ;


styliser l'explorateur de fichier !



Upload de fichiers (bonus  : système de drag n’drop)
