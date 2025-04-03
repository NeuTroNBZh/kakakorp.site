<?php

// Initialisation des variables
$email = $_POST['email'];
$pseudo = $_POST['pseudo'];
$jeu = $_POST['jeu'];
$rang = $_POST['rang'];
$motivation = $_POST['motivation'];

// Vérification des erreurs
if (empty($email)) {
  die("L'adresse e-mail est obligatoire.");
}
if (empty($pseudo)) {
  die("Le pseudo est obligatoire.");
}
if (empty($jeu)) {
  die("Le jeu est obligatoire.");
}

// Connexion à la base de données
try {
  $db = new PDO("mysql:host=localhost;dbname=u425755607_formulaire", "u425755607_root", "Louiscc123!");
} catch (PDOException $e) {
  die($e->getMessage());
}

// Récupération de la photo
if (isset($_FILES['photo'])) {
  $temp_file = $_FILES['photo']['tmp_name'];
  $filename = $_FILES['photo']['name'];

  $target_dir = 'uploads/';
  if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
  }

  $target_file = $target_dir . basename($filename);
  if (move_uploaded_file($temp_file, $target_file)) {
    $photo = $filename;
  } else {
    echo "Erreur lors du téléchargement de la photo.";
  }
} else {
  $photo = '';
}

// Insertion des données
$sql = "INSERT INTO candidatures (email, pseudo, jeu, rang, photo) VALUES (:email, :pseudo, :jeu, :rang, :photo);";
$stmt = $db->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':pseudo', $pseudo);
$stmt->bindParam(':jeu', $jeu);
$stmt->bindParam(':rang', $rang);
$stmt->bindParam(':photo', $photo);
$stmt->bindParam(':motivation', $motivation);

$stmt->execute();

// Validation des données
if ($stmt->rowCount() != 1) {
  die("Une erreur s'est produite lors de l'insertion des données.");
}

// Envoi de l'email
$to = 'contact@kakakorp.site';
$subject = 'Nouvelle candidature';
$message = "Une nouvelle candidature a été soumise:\n\n";
$message .= "Email: $email\n";
$message .= "Pseudo: $pseudo\n";
$message .= "Jeu: $jeu\n";
$message .= "Rang: $rang\n";
$message .= "motivation: $motivation\n";

$headers = "From: noreply@kakakorp.site\r\n";
$headers .= "Reply-To: $email\r\n";

mail($to, $subject, $message, $headers);

// Redirection vers la page de confirmation
header("Location: confirmation.html");
exit();

// Fermeture de la connexion à la base de données
$db = null;
?>
