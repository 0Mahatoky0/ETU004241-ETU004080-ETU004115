<?php
// API simple pour contourner Tracy
header('Content-Type: application/json');

require_once __DIR__ . '/app/config/bootstrap.php';

// Vérifier si la session est déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    
    // Simuler la distribution proportionnelle
    if ($_GET['action'] === 'simuler-proportionnelle') {
        $model = new app\models\BNGRCModel();
        $distribution = $model->simulerDistributionProportionnelle();
        
        // Stocker en session
        $_SESSION['distribution_proportionnelle'] = $distribution;
        $_SESSION['distribution_type'] = 'proportionnelle';
        
        echo json_encode([
            'success' => true,
            'distribution' => $distribution,
            'total_alloue' => array_sum(array_column($distribution['distribution'], 'quantite_allouee'))
        ]);
    }
    
    // Valider la distribution proportionnelle
    if ($_GET['action'] === 'valider-proportionnelle') {
        $model = new app\models\BNGRCModel();
        
        if (!isset($_SESSION['distribution_proportionnelle'])) {
            throw new Exception("Aucune simulation proportionnelle en cours à valider");
        }
        
        $distribution = $_SESSION['distribution_proportionnelle'];
        $success = $model->validerDistributionProportionnelle($distribution);
        
        if ($success) {
            unset($_SESSION['distribution_proportionnelle']);
            unset($_SESSION['distribution_type']);
            
            echo json_encode([
                'success' => true,
                'message' => 'Distribution proportionnelle validée avec succès'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Erreur lors de la validation'
            ]);
        }
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
