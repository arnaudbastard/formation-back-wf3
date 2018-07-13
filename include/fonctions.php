<?php
function dump($var)
{
   echo '<pre>';
   var_dump($var);
   echo '</pre>';
}

// Pour nettoyer une chaîne de caratères
function sanitizeValue(&$value)
{
    // trim() supprime les espaces en début et fin de chaîne de caractères
    // strip_tags() supprime les balises HTML
    $value = trim(strip_tags($value));
}

function sanitizeArray(array &$array)
{
    // Applique la fonction sanitizeValue() sur tous les éléments du tableau
    array_walk($array, 'sanitizeValue');
}

function sanitizePost()
{
    sanitizeArray($_POST);
}

// Enregistre un message en session pour affichage "one shot"
function setFlashMessage($message, $type = 'success')
{
    $_SESSION['flashMessage'] = [
        'message' => $message,
        'type' => $type
    ];
}

// Affiche un message flash s'il y en a un en session, puis le supprime
function displayFlashMessage()
{
    if (isset ($_SESSION['flashMessage'])) {
        $message = $_SESSION['flashMessage']['message'];
        // Pour la classe alert-danger de bootstrap
        $type = ($_SESSION['flashMessage']['type'] == 'error') 
                ? 'danger'
                : $_SESSION['flashMessage']['type']
        ;
        
        echo '<div class="alert alert-' . $type . '">'
            . '<h5 class="alert-heading">' . $message . '</h5>'
            . '</div>'
        ;
        // Suppression du message dans la session pour affichage unique
        unset($_SESSION['flashMessage']);
    }   
}

function isUserConnected()
{
    return isset($_SESSION['utilisateur']); 
}

function getUserFullName()
{
    if (isUserConnected()) {
        return $_SESSION['utilisateur']['prenom'] . ' ' . $_SESSION['utilisateur']['nom'];
    }
}

function isUserAdmin()
{
    return isUserConnected() 
        && $_SESSION['utilisateur']['role'] == 'admin';
}

function adminSecurity()
{
    if (!isUserAdmin()) {
        if (!isUserConnected()) {
            header('Location: ' . RACINE_WEB . 'connexion.php'); // header est une fonction qui change les entêtes HTTP
        } else {
            header('HTTP/1.1 403 Forbidden');
            echo "Vous n'avez pas le droit d'accéder à cette page";
        }

        die;
    }
}

function prixFr($prix)
{
    return number_format($prix, 2, ',', ' ') . ' € ';
}

function datetimeFr($datetimeSql)
{
    return date('d/m/Y H:i', strtotime($datetimeSql));
}

function ajoutPanier(array $produit, $quantite)
{
    // Initialisation du panier
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }
    // Si le produit n'est pas encore dans le panier
    if (!isset($_SESSION['panier'][$produit['id']])) {
        $_SESSION['panier'][$produit['id']] = [
            'nom' => $produit['nom'],
            'prix' => $produit['prix'],
            'quantite' => $quantite
        ];
    } else {
        // Si le produit est déjà dans le panier, on met à jour la quantité
        $_SESSION['panier'][$produit['id']]['quantite'] += $quantite;
    }
}

function totalPanier()
{
    $total = 0;

    if (isset($_SESSION['panier'])) {
        foreach ($_SESSION['panier'] as $produit) {
            $total += $produit['prix'] * $produit['quantite'];
        }
    }

    return $total;
}

function modifierQuantitePanier ($produitId, $quantite)
{
    if (isset ($_SESSION['panier'][$produitId])) {
        if ($quantite != 0) {
            $_SESSION['panier'][$produitId]['quantite'] = $quantite;
        } else {
            unset($_SESSION['panier'][$produitId]);
        }
    }
}