# Tutoriel : Explorateur de fichiers en PHP


## 1 - Afficher le contenu du répertoire :

Je créé une variable ```$cwd``` qui contient la fonction ```getcwd()``` laquelle  retourne l'url du dossier de travail courant.

  ```
  $cwd = getcwd();
  ```

Je peux m'assurer du résultat en faisant un ```echo``` :

  ```
  echo $cwd;
  ```

Je créé une variable ```$all_contents``` qui va contenir le résultat du ```scandir()``` sur la variable ```$cwd```.

  ```
  $all_contents = scandir($cwd);
  ```

Avec ```scandir()```, je liste les fichiers du répertoire et je les enregistre dans une variable sous forme d'array. Ensuite, je peux afficher le contenu de ma variable ```$all_contents``` avec la fonction ```print_r()``` :

  ```
  print_r($all_contents);
  ```

Maintenant, nous allons afficher, avec la structure de contrôle ```foreach()```, le contenu de l'array ```$all_contents```. ```foreach()``` est une boucle qui s'exécute pour chaque élément d'un tableau, elle prend comme paramètre la variable qui contient le tableau suivi du mot-clé ```as``` puis d'un nouveau nom de variable. Ensuite, on peut faire un ```echo``` sur cette nouvelle variable pour lister chaque élément, on utilise le séparateur ```<br>``` pour plus de lisibilité.

  ```
  foreach ($all_contents as $item) {
    echo $item ."<br>";
  }
  ```


## 2 - Le script ouvre un "enfant" du répertoire dans lequel il se trouve :

Le script ne doit pas ouvrir le répertoire qui le contient mais un sous-dossier appelé "home".
On commence donc par vérifier si le dossier "home" existe. S'il n'existe pas, il le créé :

  ```
  $home = "home";
  if (!is_dir($home)) {
    mkdir("home");
  }
  ```

À présent, nous allons demander au script d'ouvrir, à son lancement, le dossier. Pour ce faire, nous utilisons la fonction ```chdir()``` à laquelle nous passons en paramètre la fonction ```getcwd()``` suivie d'une constante pré-définie : ```DIRECTORY_SEPARATOR```, puis de la variable contenant le nom du sous-dossier qui servira de répertoire racine (root) lors de l'exécution du programme.

  ```
  chdir(getcwd() . DIRECTORY_SEPARATOR . $home);
  ```

On placera tout cette partie, qui correspond à l'initialisation, au début du script.


## 3 - Faire en sorte que .. et . n’apparaissent pas :

On commence par déclarer une variable que l'on nomme ```$contents``` et qui va contenir un tableau vide :

  ```
  $contents = [];
  ```

Dans le ```foreach```, on créé une condition qui vérifie que les valeurs de ```$item``` sont différentes de "." et ".." qui correspondent au dossier "racine" et au dossier "parent".
Ces deux objets familier aux utilisateurs de système d'exploitation de type UNIX ne nous sont pas utiles et gênent la lisibilité du résultat du ```foreach```.
La condition sera toujours vérifiée puisque ces deux objets se retrouveront dans tous les répertoires lorsque l'on naviguera par la suite dans l'arborescence d'où la nécessité de les faire disparaître.
De plus, ".." permet de remonter en amont du répertoire "home".
Dans la condition, on fait apparaître chaque occurence de ```$item``` et on rempli le tableau contenu dans ```$content``` avec les ```$item``` en remplaçant chaque index du tableau par le nom du fichier correspondant.

  ```
  foreach ($all_contents as $item) {
    if ($item !== "." && $item !== "..") {
      echo $item ."<br>"; // ou : echo "$item<br>";
      $contents[$item] = $item;
    }
  }
  ```


## 4 - Afficher un fil d'ariane (breadcrumb) pour se repérer dans l'arborescence :

On déclare une variable ```$breadcrumb``` qui va contenir la fonction ```explode()``` qui sert à scinder une chaîne de caratères en plusieurs segments dans un tableau. On lui passe en paramètre la constante pré-définie ```DIRECTORY_SEPARATOR``` et la variable ```$cwd``` :

  ```
  $breadcrumb = explode(DIRECTORY_SEPARATOR, $cwd);
  ```

Ensuite, on fait apparaître chaque élément du tableau dans une boucle ```foreach()``` pour révéler l'arborescence :

  ```
  foreach($breadcrumb as $name) {
    echo "<button>$name</button>";
  }
  ```

On utilise un formulaire afin d'envoyer la valeur modifiée de la variable ```$cwd``` pour la récupérer avec la variable super-globale ```$_POST()``` à chaque changement de répertoire.

Afin de parcourir le fil d'ariane, on transforme chaque portion en un bouton avec un lien grace au formulaire.
On commence par déclarer une variable ```$cwd_road = "";``` qui sert à enregistrer le chemin qui mène aux dossiers que l'on parcour dans le fil d'ariane, cette varaible se contruit au fur et à mesure où l'on navigue dans les dossiers enfants. Ensuite, dans le ```foreach()```:

  ```
  foreach ($breadcrumb as $name) {
    $cwd_road .= $name . DIRECTORY_SEPARATOR;
      echo "<form method='POST'>";
        echo "<a href='index.php'>";
          echo "<button type='submit'>";
          echo $name;
          echo "</button>";
        echo "</a>";
        echo "<input type='hidden' name='cwd' value='" . substr($cwd_road, 0, -1) . "'>";
      echo "</form>";
  }
  ```

Précision : le ```substr($cwd_road, 0, -1``` permet de retirer le "DIRECTORY_SEPARATOR" en trop à la fin.

Grace à ce formulaire, on transmet le nom de notre nouveau répertoire après le rafraichissement de la page via la variable ```$_POST($cwd)```.
On vérifie qu'il y a quelque chose dans ```$_POST["cwd"]``` en mettant le code suivant en début de fichier, après la déclaration de la variable ```$home```.


  ```
  if (!isset($_POST["cwd"])) {
    $cwd = getcwd() . DIRECTORY_SEPARATOR . $home;
  }
  else {
    $cwd = $_POST["cwd"];
  }
  ```

On fait ensuite un ```echo "<form id='changecwd' method='POST'></form>";``` que l'on place avant le ```foreach()```.

Dans le 1er chargement de la page, la variable ```$_POST($cwd)``` n'existe pas. Avec la fonction ```isset()```, on teste si cette variable existe, à défaut, la variable ```$cwd``` prend la valeur de ```getcwd() . DIRECTORY_SEPARATOR . $home```.


## 5 - Pouvoir se promener dans l'arborescence : ouvrir des dossiers enfants, remonter au parent, etc.) :

En reprenant le code de l'affichage du fil d'ariane et en l'adaptant, on obtient l'affichage du contenu :

  ```
  echo "<button type='submit' form='changecwd' name='cwd' value='" . $cwd . DIRECTORY_SEPARATOR . $name . "'>";
  ```

Ceci permet d'obtenir le chemin des répertoires dans le dossier actuel.

Précision : on réutilise le formulaire en utilisant l'attribut ```form='changecwd'``` pour pointer le formulaire auquel on a donné l'```id='changecwd'```.


## 6 - Récupérer les nom / taille / type / date de création de chaque élément :

On commence par créer 3 variables qui vont contenir 3 tableaux : la date, la taille et le type.

  ```
  $contents_size = [];
  $contents_date = [];
  $contents_type = [];
  ```

Ensuite, dans le <ode>foreach```, dans la condition qui vérifie que le contenu des variables <code$item``` n'est ni "." ni "..", on va créer une variable sous forme de tableau pour récupérer les noms des items :

  ```
  $contents[$item] = $item;
  ```

Puis, on créé une deuxième variable qui va contenir un tableau pour récupérer la date des items :

  ```
  $contents_date[$item] = filemtime($cwd . DIRECTORY_SEPARATOR . $item);
  ```

On va récupérer les "size" et les "type", en distinguant les fichiers des dossiers. On créé une condition qui vérifie si l'item est un "dossier" :

  ```
  if (is_dir($cwd . DIRECTORY_SEPARATOR . $item)) {
    $contents_size[$item] = "";
    $contents_type[$item] = "Folder";
  }
  ```

Si c'est un dossier, on ne calcule pas sa taille et on lui attribut le type "dossier".
Si ce n'est pas un dossier alors c'est un fichier ! :-D
On utilise la fonction ```filesize()``` en lui passant en paramètre le chemin de l'item :

  ```
  $contents_size[$item] = filesize($cwd . DIRECTORY_SEPARATOR . $item);
  ```

On utilise la fonction ```strpos()``` dans une condition, cette fonction vérifie la présence d'un caractère et retourne sa position. En l'occurence, dans ce cas, on va vérifier la présence d'un "." dans le nom du fichier :

  ```
  if (strpos($item, ".")) {
    $type = explode(".", $item);
    $contents_type[$item] = end($type);
  }
  ```

S'il y a un ".", on déclare une variable ```$type``` qui contient le résultat de la fonction ```explode()``` à laquelle on passe en paramètre le caractère "." suivi de l'occurence de la variable de ```$item```.
Enfin, dans la variable ```$contents_type[$item]```, on récupére le dernier élément du tableau grace à la fonction ```end($type)``` qui nous donne l'extension du fichier.
S'il n'y a pas de ".", l'extension étant non définie, on indique "undefined" :

  ```
  else {
    $contents_type[$item] = "undefined";
  }
  ```


## 7 - Pour chaque élément, afficher et trier par nom / taille / type / date de création :

On créé une barre de catégorie que l'on place au-dessus du contenu de notre explorateur et qui va contenir les nom, date, taille et type de chaque dossier ou fichier :

  ```
  echo "<div class='breadcrumb'>";
    echo "<div class='w-25'>";
      echo "<button type='submit' form='sort' name='sort' value='name'>";
      echo "Name";
      echo "</button>";
    echo "</div>";
    echo "<div class='w-25'>";
      echo "<button type='submit' form='sort' name='sort' value='date'>";
      echo "Date";
      echo "</button>";
    echo "</div>";
    echo "<div class='w-25'>";
      echo "<button type='submit' form='sort' name='sort' value='size'>";
      echo "Size";
      echo "</button>";
    echo "</div>";
    echo "<div class='w-25'>";
      echo "<button type='submit' form='sort' name='sort' value='type'>";
      echo "Type";
      echo "</button>";
    echo "</div>";
  echo "</div>";

  ```
Précision : les ```class``` Bootstrap (breadcrumb et w-25) ne servent que pour la présentation esthétique.

Les boutons correspondent chacun à une catégorie et pointent vers un nouveau formulaire grace à ```form='sort'``` :

  ```
  echo "<form id='sort' method='POST'></form>";

  ```

Maintenant, lorsqu'on clique sur un bouton, la ```value``` de celui-ci est stoquée dans  ```$_POST["sort"]```.

La variable super-globale ```$_POST["sort"]``` est réinitialisée à chaque rafraichissement de la page.
On a besoin de garder la valeur de "cwd" (répertoire dans lequel on se trouve) donc on ajoute à notre formulaire :

  ```
  echo "<input type='hidden' name='cwd' value='$cwd'>";

  ```

Par défaut, on souhaite un tri par ordre alphabétique.
Donc, on teste si un tri est déjà existant à l'aide de :

  ```
  if (!isset($_POST["sort"])) {
    $sort_by = "name";
  }

  ```

Si le tri n'existe pas alors on déclare la variable ```$sort_by``` en lui assignant la valeur "name" par défaut.

On va créer un nouveau tableau ```$sorted_contents```, que l'on place dans des conditions, on vérifie sui la variable ```$sort_by``` contient les paramètres "date", "size" ou "type", et en fonction, on affecte des variables idoines ```$contents_date, $contents_size et $contents_type```.
Puis, on tri les contenus de ce tableau par ordre numérique ou alphanumérique :

  ```
  if ($sort_by === "date") {
    $sorted_contents = $contents_date;
    asort($sorted_contents);
  }
  elseif ($sort_by === "size") {
    $sorted_contents = $contents_size;
    asort($sorted_contents);
  }
  elseif ($sort_by === "type") {
    $sorted_contents = $contents_type;
    natcasesort($sorted_contents);
  }
  else {
    $sorted_contents = $contents;
    natcasesort($sorted_contents);
  }

  ```

la fonction ```asort()``` tri un tableau et conserve l'association des index, la fonction ```natcasesort()``` traite les chaînes alphanumériques du tableau (array) comme un être humain tout en conservant la relation clé/valeur. C'est ce qui est appelé l'"ordre naturel".


Si l'ordre de tri n'existe pas alors on déclare la variable ```$sort_order``` en lui assignant la valeur "down" par défaut.

Par défaut, on souhaite un tri croissant donc, si la variable ```$_POST["sort_order"]``` n'existe pas, on assigne la valeur "up" à la variable ```$sort_order```.
Si la variable ```$_POST["sort_order"]``` existe, on assigne l'inverse de sa valeur à la variable ```$sort_order``` (donc la valeur inverse sera soit "up" soit "down" selon la valeur de départ)

Donc, on teste si un tri est déjà existant.

  ```
  if (!isset($_POST["sort_order"])) {
    $sort_order = "up";
  }
  else {
    if ($_POST["sort_order"] === "up") {
      $sort_order = "down";
    }
    else {
      $sort_order = "up";
    }
  }

  ```




## 8 - Option afficher/masquer les fichiers cachés :


## 9 - Ouvrir des fichiers :    


## 10 - Modifier des fichiers :


## 11 - Couper/copier/coller les fichiers :


## 12 - Supprimer des fichiers :


## 14 - Créer un nouveau dossier dans le répertoire où l'on est positionné :


## 15 - Créer un nouveau fichier dans le répertoire où l'on est positionné :


## 16 - Les fichiers supprimés vont dans une corbeille :


## 17 - Restaurer les fichiers depuis la corbeille :


## 18 - Styliser l'explorateur de fichier :


## 19 - Upload de fichier + un système de drag n’drop :
