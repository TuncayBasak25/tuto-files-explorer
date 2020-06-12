# Tutoriel: Explorateur de fichiers en PHP

## Afficher le contenu du répertoire :

Je créé une variable <code>$cwd</code> qui contient la fonction <code>getcwd()</code> (qui  retourne l'url du dossier de travail courant).

  <code>$cwd = getcwd();</code>

Je m'assure du résultat en faisant un <code>echo</code> :

  <code>echo $cwd;</code>

Je créé une variable <code>$allContents</code> qui va contenir le résultat du <code>scandir()</code> sur la variable <code>$cwd</code>.

  <code>$all_contents = scandir($cwd);</code>

Avec <code>scandir()</code>, je liste les fichiers du répertoire et je les enregistre dans une variable sous forme d'array. Ensuite, j'affiche le contenu de ma variable <code>$allContents</code> avec la fonction <code>print_r()</code> :

  <code>print_r($all_contents);</code>
