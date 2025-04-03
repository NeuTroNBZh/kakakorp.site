<?php
header('Content-Type: application/json');

// Configuration de la base de données (à remplacer par vos informations)
$db_host = 'localhost';
$db_name = 'kakakorp';
$db_user = 'root';
$db_pass = '';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des données du formulaire
    $data = json_decode(file_get_contents('php://input'), true);

    // Validation des données
    $errors = [];
    $required_fields = ['nom', 'email', 'message'];

    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            $errors[$field] = "Ce champ est requis";
        }
    }

    // Validation de l'email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Adresse email invalide";
    }

    // Si des erreurs sont présentes, on les renvoie
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    // Préparation de la requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO contacts (nom, email, message, date_creation) VALUES (:nom, :email, :message, NOW())");
    
    // Exécution de la requête
    $stmt->execute([
        ':nom' => $data['nom'],
        ':email' => $data['email'],
        ':message' => $data['message']
    ]);

    // Envoi d'un email de confirmation (à configurer selon votre serveur)
    $to = $data['email'];
    $subject = "Confirmation de réception - KAKAKORP Esports";
    $message = "Bonjour " . $data['nom'] . ",\n\n";
    $message .= "Nous avons bien reçu votre message. Nous vous répondrons dans les plus brefs délais.\n\n";
    $message .= "Cordialement,\nL'équipe KAKAKORP Esports";
    $headers = "From: contact@kakakorp.site\r\n";
    $headers .= "Reply-To: contact@kakakorp.site\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    mail($to, $subject, $message, $headers);

    // Réponse de succès
    echo json_encode(['success' => true, 'message' => 'Message envoyé avec succès']);

} catch (PDOException $e) {
    // En cas d'erreur de base de données
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données']);
} catch (Exception $e) {
    // En cas d'autres erreurs
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue']);
}
?> 