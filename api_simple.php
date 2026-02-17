<?php
// API autonome pour distribution proportionnelle
header('Content-Type: application/json');

// Connexion directe à la base de données
try {
    $host = '127.0.0.1';
    $dbname = 'bngrc';
    $user = 'sharon';
    $password = 'sharon';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si la session est déjà démarrée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $action = $_GET['action'] ?? '';
    
    // Simuler la distribution proportionnelle
    if ($action === 'simuler-proportionnelle') {
        
        // Récupérer les besoins triés
        $stmt = $pdo->query("
            SELECT 
                bs.id,
                bs.quantite_initiale,
                bs.quantite_restante,
                b.libelle as besoin_libelle,
                cb.libelle as categorie_libelle,
                v.libelle as ville_libelle,
                r.libelle as region_libelle,
                b.prix_unitaire,
                bs.date
            FROM besoin_sinistre bs
            JOIN besoin b ON bs.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            JOIN ville v ON bs.id_ville = v.id
            JOIN region r ON v.id_region = r.id
            WHERE bs.quantite_restante > 0
            ORDER BY bs.quantite_restante ASC
        ");
        $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculer le total des besoins
        $stmt = $pdo->query("SELECT SUM(quantite_restante) AS total_besoins FROM besoin_sinistre WHERE quantite_restante > 0");
        $total_besoins = $stmt->fetch(PDO::FETCH_ASSOC)['total_besoins'];
        
        // Calculer le stock total disponible
        $stmt = $pdo->query("SELECT SUM(quantite) AS stock_total FROM stock_bngrc WHERE quantite > 0");
        $stock_total = $stmt->fetch(PDO::FETCH_ASSOC)['stock_total'];
        
        $distribution = [];
        $stock_distribue = 0;
        
        // Calcul proportionnel pour chaque besoin
        foreach ($besoins as $besoin) {
            if ($total_besoins > 0 && $stock_total > 0 && $stock_distribue < $stock_total) {
                // Formule: Part = (besoin / total_besoins) × stock
                $part_calculee = ($besoin['quantite_restante'] / $total_besoins) * $stock_total;
                
                // Prendre seulement la partie entière (floor)
                $part_finale = floor($part_calculee);
                
                // Vérifier que la part ne dépasse pas le besoin réel
                $part_finale = min($part_finale, $besoin['quantite_restante']);
                
                // Vérifier qu'on ne dépasse pas le stock disponible
                $part_finale = min($part_finale, $stock_total - $stock_distribue);
                
                if ($part_finale > 0) {
                    $distribution[] = [
                        'id_besoin_sinistre' => $besoin['id'],
                        'id_besoin_type' => $besoin['id_besoin'],
                        'besoin_libelle' => $besoin['besoin_libelle'],
                        'ville_libelle' => $besoin['ville_libelle'],
                        'quantite_requise' => $besoin['quantite_restante'],
                        'part_calculee' => round($part_calculee, 2),
                        'quantite_allouee' => $part_finale,
                        'quantite_restante_apres' => $besoin['quantite_restante'] - $part_finale,
                        'pourcentage' => round(($besoin['quantite_restante'] / $total_besoins) * 100, 2)
                    ];
                    
                    $stock_distribue += $part_finale;
                }
            }
        }
        
        // Stocker en session
        $_SESSION['distribution_proportionnelle'] = [
            'distribution' => $distribution,
            'total_besoins' => $total_besoins,
            'stock_total' => $stock_total,
            'stock_distribue' => $stock_distribue,
            'stock_restant' => $stock_total - $stock_distribue,
            'besoins_satisfaits' => count(array_filter($distribution, fn($d) => $d['quantite_restante_apres'] == 0))
        ];
        $_SESSION['distribution_type'] = 'proportionnelle';
        
        echo json_encode([
            'success' => true,
            'distribution' => $_SESSION['distribution_proportionnelle']
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
