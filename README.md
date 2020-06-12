# Tutoriel : Explorateur de fichiers en PHP


## 1 - Afficher le contenu du répertoire :

Je créé une variable <code>$cwd</code> qui contient la fonction <code>getcwd()</code> laquelle  retourne l'url du dossier de travail courant.

  <code>$cwd = getcwd();</code>

Je peux m'assurer du résultat en faisant un <code>echo</code> :

  <code>echo $cwd;</code>

Je créé une variable <code>$all_contents</code> qui va contenir le résultat du <code>scandir()</code> sur la variable <code>$cwd</code>.

  <code>$all_contents = scandir($cwd);</code>

Avec <code>scandir()</code>, je liste les fichiers du répertoire et je les enregistre dans une variable sous forme d'array. Ensuite, je peux afficher le contenu de ma variable <code>$all_contents</code> avec la fonction <code>print_r()</code> :

  <code>print_r($all_contents);</code>

Maintenant, nous allons afficher, avec la structure de contrôle <code>foreach()</code>, le contenu de l'array <code>$all_contents</code>. <code>foreach()</code> est une boucle qui s'exécute pour chaque élément d'un tableau, elle prend comme paramètre la variable qui contient le tableau suivi du mot-clé <code>as</code> puis d'un nouveau nom de variable. Ensuite, on peut faire un <code>echo</code> sur cette nouvelle variable pour lister chaque élément, on utilise le séparateur <code><br></code> pour plus de lisibilité.

  <code>
  foreach ($all_contents as $item) {
    echo $item ."<br>";
  }</code>


## 2 - Le script ouvre un "enfant" du répertoire dans lequel il se trouve :

Le script ne doit pas ouvrir le répertoire qui le contient mais un sous-dossier appelé "home".
On commence donc par vérifier si le dossier "home" existe. S'il n'existe pas, il le créé :

  <code>
  $home = "home";
  if (!is_dir($home)) {
    mkdir("home");
  }</code>

À présent, nous allons demander au script d'ouvrir, à son lancement, le dossier. Pour ce faire, nous utilisons la fonction <code>chdir()</code> à laquelle nous passons en paramètre la fonction <code>getcwd()</code> suivie d'une constante pré-définie : <code>DIRECTORY_SEPARATOR</code>, puis de la variable contenant le nom du sous-dossier qui servira de répertoire racine (root) lors de l'exécution du programme.

  <code>chdir(getcwd() . DIRECTORY_SEPARATOR . $home);</code>

On placera tout cette partie, qui correspond à l'initialisation, au début du script.


## 3 - Faire en sorte que .. et . n’apparaissent pas :

On commence par déclarer une variable que l'on nomme <code>$contents</code> et qui va contenir un tableau vide :

  <code>$contents = [];</code>

Dans le <code>foreach</code>, on créé une condition qui vérifie que les valeurs de <code>$item</code> sont différentes de "." et ".." qui correspondent au dossier "racine" et au dossier "parent".
Ces deux objets familier aux utilisateurs de système d'exploitation de type UNIX ne nous sont pas utiles et gênent la lisibilité du résultat du <code>foreach</code>.
La condition sera toujours vérifiée puisque ces deux objets se retrouveront dans tous les répertoires lorsque l'on naviguera par la suite dans l'arborescence d'où la nécessité de les faire disparaître.
De plus, ".." permet de remonter en amont du répertoire "home".
Dans la condition, on fait apparaître chaque occurence de <code>$item</code> et on rempli le tableau contenu dans <code>$content</code> avec les <code>$item</code> en remplaçant chaque index du tableau par le nom du fichier correspondant.

  <code>
  foreach ($all_contents as $item) {
    if ($item !== "." && $item !== "..") {
      echo $item ."<br>"; // ou : echo "$item<br>";
      $contents[$item] = $item;
    }
  }</code>


## 4 - Afficher un fil d'ariane (breadcrumb) pour se repérer dans l'arborescence :

On déclare une variable <code>$breadcrumb</code> qui va contenir la fonction <code>explode()</code> qui sert à scinder une chaîne de caratères en plusieurs segments dans un tableau. On lui passe en paramètre la constante pré-définie <code>DIRECTORY_SEPARATOR</code> et la variable <code>$cwd</code> :

  <code>$breadcrumb = explode(DIRECTORY_SEPARATOR, $cwd);</code>

Ensuite, on fait apparaître chaque élément du tableau dans une boucle <code>foreach</code> pour révéler l'arborescence :

<code>
  foreach ($breadcrumb as $name) {
  echo "<button>$name</button>";
  }</code>

On utilise un formulaire afin d'envoyer la valeur modifiée de la variable <code>$cwd</code> pour la récupérer avec la variable super-globale <code>$\_POST()</code> à chaque changement de répertoire.

Afin de parcourir le fil d'ariane, on transforme chaque portion en un bouton avec un lien grace au formulaire :

  <code>
  echo "<form method='POST'>";
    echo "<a href='index.php'>";
      echo "<button type='submit'>";
      echo $name;
      echo "</button>";
    echo "</a>";
    echo "<input type='hidden' name='cwd' value='" . substr($cwd_road, 0, -1) . "'>";
  echo "</form>"; </code>

Précision : le <code>substr($cwd_road, 0, -1</code> permet de retirer le "DIRECTORY_SEPARATOR" en trop à la fin.

Grace à ce formulaire, on transmet le nom de notre nouveau répertoire après le rafraichissement de la page via la variable <code>$\_POST($cwd)</code>.

  <code>
  if (!isset($\_POST["cwd"])) {
    $cwd = getcwd() . DIRECTORY_SEPARATOR . $home;
  }
  else {
    $cwd = $\_POST["cwd"];
  }</code>

Dans le 1er chargement de la page, la variable <code>$\_POST($cwd)</code> n'existe pas. Avec la fonction <code>isset()</code>, on teste si cette variable existe, à défaut, la variable <code>$cwd</code> prend la valeur de <code>getcwd() . DIRECTORY_SEPARATOR . $home</code>


## 5 - Pouvoir se promener dans l'arborescence : ouvrir des dossiers enfants, remonter au parent, etc.) :






## 6 - Trier les fichiers par nom / taille / type / date de création :


## 8 - Bouton pour classer les fichiers par nom / taille / type / date de création :


## 9 - Option afficher/masquer les fichiers cachés :


## 10 - Ouvrir des fichiers :    


## 11 - Modifier des fichiers :


## 12 - Couper/copier/coller les fichiers :


## 13 - Supprimer des fichiers :


## 14 - Créer un nouveau dossier dans le répertoire où l'on est positionné :


## 15 - Créer un nouveau fichier dans le répertoire où l'on est positionné :


## 16 - Les fichiers supprimés vont dans une corbeille :


## 17 - Restaurer les fichiers depuis la corbeille :


## 18 - Styliser l'explorateur de fichier :


## 19 - Upload de fichier + un système de drag n’drop :
