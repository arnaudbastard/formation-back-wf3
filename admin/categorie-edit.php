<?php
require_once __DIR__ . '/../include/init.php';
adminSecurity();

$errors = [];
$nom = '';

if (!empty($_POST)) { // veut dire : si le formulaire a été soumis
    // Nettoyage des données du formulaire (cf. include/fonctions.php)
    sanitizePost();
    extract($_POST); // Crée des variables à partir d'un tableau. Les variables portent les noms des clés dans le tableau : 'nom' étant la clé du tableau $_POST
    // Test de la saisie du champ nom
    if (empty($_POST['nom'])) {
        $errors[] = 'Le nom est obligatoire';
    } elseif (strlen($_POST['nom']) > 50) {
        $errors[] = 'Le nom ne doit pas faire plus de 50 caractères';
    }
    
    // Si le formulaire est correctement rempli
    if (empty($errors)) {
        if (isset($_GET['id'])) { // modification de catégorie
            $query = 'UPDATE categorie SET nom = :nom WHERE id = :id';
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':nom' => $nom,
                ':id' => $_GET['id']
            ]);
        } else { // création
            // insertion en bdd
            $query = 'INSERT INTO categorie(nom) VALUES (:nom)';
            $stmt = $pdo->prepare($query);
            $stmt->execute([':nom' => $nom]);
        }

        // Enregistrement d'un message en session
        setFlashMessage('La catégorie est enregistrée');
        // Redirection vers la page de liste
        header('Location: categories.php');
        die; // termine l'exécution du script
    }
} elseif (isset($_GET['id'])) {
    // En modification, si on n'a pas de retour de formulaire, on va chercher la catégorie en bdd pour affichage
    $query = 'SELECT * FROM categorie WHERE id=' . (int)$_GET['id'];
    $stmt = $pdo->query($query);
    $categorie = $stmt->fetch();
    $nom = $categorie['nom'];
}

require __DIR__ . '/../layout/top.php';

if (!empty($errors)) :
?>
    <div class="alert alert-danger">
        <h5 class="alert-heading">Le formulaire contient des erreurs</h5>
        <?= implode('<br>', $errors); // transforme un tableau en chaîne de caractères ?>
    </div>    
<?php
endif;
?>
<h1>Edition catégorie</h1>

<form method="post">
    <div class="form-group">
        <label>Nom</label>
        <input type="text" name="nom" class="form-control" value="<?= $nom; ?>">
    </div>
    <div class="form-btn-group text-right">
        <button type=""submit" class="btn btn-primary">
                Enregistrer
        </button>
        <a class="btn btn-secondary" href="categories.php">
            Retour
        </a>
    </div>
</form>
<?php
require __DIR__ . '/../layout/bottom.php';
?>