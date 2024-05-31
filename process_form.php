<?php

// Initialisation des variables
$email = $_POST['email'];
$pseudo = $_POST['pseudo'];
$jeu = $_POST['jeu'];
$rang = $_POST['rang'];

// Vérification des erreurs
if (empty($email)) {
  die("<script>alert('L\'adresse e-mail est obligatoire.'); window.history.back();</script>");
}
if (empty($pseudo)) {
  die("<script>alert('Le pseudo est obligatoire.'); window.history.back();</script>");
}
if (empty($jeu)) {
  die("<script>alert('Le jeu est obligatoire.'); window.history.back();</script>");
}

// Connexion à la base de données
try {
  $db = new PDO("mysql:host=localhost;dbname=u425755607_formulaire", "u425755607_root", "Louiscc123!");
} catch (PDOException $e) {
  die($e->getMessage());
}

// Vérification si l'email est déjà utilisé
$sql = "SELECT * FROM candidatures WHERE email = :email";
$stmt = $db->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->rowCount() > 0) {
  die("<script>alert('Cet e-mail est déjà utilisé pour une inscription.'); window.history.back();</script>");
}

// Insertion des données
$sql = "INSERT INTO candidatures (email, pseudo, jeu, rang) VALUES (:email, :pseudo, :jeu, :rang);";
$stmt = $db->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':pseudo', $pseudo);
$stmt->bindParam(':jeu', $jeu);
$stmt->bindParam(':rang', $rang);

$stmt->execute();

// Validation des données
if ($stmt->rowCount() != 1) {
  die("<script>alert('Une erreur s\'est produite lors de l\'insertion des données.'); window.history.back();</script>");
}

// Envoi de l'email de confirmation
$to = $email;
$subject = "Confirmation d'inscription";
$message = "Bonjour " . $pseudo . ",\n\nMerci pour votre inscription. Votre compte a été créé avec succès.\n\nCordialement,\nL'équipe";
$headers = "From: no-reply@votre-domaine.com";

// Vérifiez si l'email a été envoyé avec succès
if(mail($to, $subject, $message, $headers)) {
    echo "<script>alert('Un email de confirmation a été envoyé à " . $to . "'); window.location.href='confirmation.html';</script>";
} else {
    echo "<script>alert('L\'envoi de l\'email de confirmation a échoué.'); window.history.back();</script>";
}

// Fermeture de la connexion à la base de données
$db = null;

?>
