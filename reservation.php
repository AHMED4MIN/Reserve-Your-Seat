<?php
// Paramètres de connexion à la base de données
$host = 'localhost'; // Hôte (souvent 'localhost' pour les serveurs locaux)
$dbname = 'holiday';  // Nom de la base de données
$username = 'root';  // Nom d'utilisateur pour la base de données
$password = '';      // Mot de passe de l'utilisateur

try {
    // Création de la connexion PDO
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8"; // DSN pour MySQL avec encodage UTF-8
    $pdo = new PDO($dsn, $username, $password);

    // Définir le mode d'erreur PDO à Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En cas d'erreur de connexion, afficher un message d'erreur
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialisation du tableau des erreurs
    $errors = [];

    // Récupérer les données du formulaire
    $nom = $_POST["nom"] ?? "";
    $cin = $_POST["cin"] ?? "";
    $cuisine = $_POST["cuisine"] ?? "";
    $nbTables = intval($_POST["places"] ?? 0);
    $date = $_POST["date"] ?? "";

    // Validation des champs
    if (empty($nom)) {
        $errors[] = "Le champ 'Nom' est requis.";
    }

    if (empty($cin)) {
        $errors[] = "Le champ 'CIN' est requis.";
    }

    if (empty($cuisine)) {
        $errors[] = "Veuillez sélectionner un type de cuisine.";
    }

    if (empty($nbTables) || $nbTables < 1 || $nbTables > 10) {
        $errors[] = "Le nombre de tables doit être entre 1 et 10.";
    }

    if (empty($date)) {
        $errors[] = "Le champ 'Date' est requis.";
    }

    // Si des erreurs existent, les afficher
    if (!empty($errors)) {
        echo "<h3>Erreurs :</h3><ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
    } else {
        // Si aucune erreur, procéder à l'insertion dans la base de données

        // Afficher les données du formulaire
        echo "<h2>Réservation Confirmée</h2>";
        echo "<p><strong>Nom :</strong> $nom</p>";
        echo "<p><strong>CIN :</strong> $cin</p>";
        echo "<p><strong>Cuisine Choisie :</strong> $cuisine</p>";
        echo "<p><strong>Nombre de Tables :</strong> $nbTables</p>";
        echo "<p><strong>Date de Réservation :</strong> $date</p>";

        // Préparer l'insertion des informations du client dans la base de données
        $sql_insert_client = "INSERT INTO client (nom, cin) VALUES (:nom, :cin)";
        $stmt = $pdo->prepare($sql_insert_client);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':cin', $cin, PDO::PARAM_STR);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "<p>Client enregistré avec succès.</p>";
        } else {
            echo "<p>Erreur lors de l'enregistrement du client.</p>";
        }

        // Insertion des informations de réservation
        $sql_insert_reservation = "INSERT INTO reservation (cuisine, nb_tables, date) VALUES (:cuisine, :nb_tables, :date)";
        $stmt = $pdo->prepare($sql_insert_reservation);
        $stmt->bindParam(':cuisine', $cuisine, PDO::PARAM_STR);
        $stmt->bindParam(':nb_tables', $nbTables, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);

        // Exécuter la requête
      
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Réservation</title>
    <link rel="stylesheet" href="reservation.css">
</head>
<body>

    <div class="page-wrapper">
        <!--header start -->
        <header class="header">
            <div class="container">
                <nav class="nav">
                    <div class="logo">
                        <img src="images/logo HD.png" alt="Menu des cultures">
                    </div>
                    <ul>
                        <li><a href="home.html">Home</a></li>
                        <li><a href="#menu">Menu</a></li>
                        <li><a href="">Reservation</a></li>
                        <li><a href="#staff">Staff</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <!-- Formulaire de Réservation -->
        <div class="form-container">
            <h2>Réservez une Table</h2>
            <form id="reservationForm" action="" method="post">
                <!-- Champ Nom -->
                <label for="nom">Nom Complet :</label>
                <input type="text" id="nom" name="nom" placeholder="Votre nom complet" value="<?php echo htmlspecialchars($nom ?? ''); ?>" required>
                
                <!-- Champ CIN -->
                <label for="cin">CIN :</label>
                <input type="text" id="cin" name="cin" placeholder="Votre CIN" value="<?php echo htmlspecialchars($cin ?? ''); ?>" required>
                
                <!-- Liste déroulante Cuisine -->
                <label for="cuisine">Cuisine choisi :</label>
                <select id="cuisine" name="cuisine" required>
                    <option value="" disabled selected>Choisissez une cuisine</option>
                    <option value="mexicain" <?php if ($cuisine == "mexicain") echo "selected"; ?>>Mexicain</option>
                    <option value="marocain" <?php if ($cuisine == "marocain") echo "selected"; ?>>Marocain</option>
                    <option value="japonais" <?php if ($cuisine == "japonais") echo "selected"; ?>>Japonais</option>
                    <option value="italien" <?php if ($cuisine == "italien") echo "selected"; ?>>Italien</option>
                    <option value="chinois" <?php if ($cuisine == "chinois") echo "selected"; ?>>Chinois</option>
                </select>
                
                <!-- Champ Nombre de Places -->
                <label for="places">Nombre de tables :</label>
                <input type="number" id="places" name="places" min="1" max="10" placeholder="Exemple : 2" value="<?php echo htmlspecialchars($nbTables ?? ''); ?>" required>
                
                <!-- Champ Date -->
                <label for="date">Date :</label>
                <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date ?? ''); ?>" required>
                
                <!-- Boutons -->
                <div class="buttons">
                    <button type="reset" id="resetButton">Réinitialiser</button>
                    <button type="submit" id="reserveButton">Réserver</button>
                </div>
            </form>
        </div>
    </div>

    <script src="reservation.js"></script>
</body>
</html>
