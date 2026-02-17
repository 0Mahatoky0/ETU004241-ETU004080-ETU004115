<?php
// Test simple de simulation prioritaire
header('Content-Type: application/json');

require_once __DIR__ . '/app/config/bootstrap.php';

session_start();

try {
    $model = new app\models\BNGRCModel();
    $distribution = $model->simulerDistributionPrioritaire();
    
    $_SESSION['distribution_prioritaire'] = $distribution;
    $_SESSION['distribution_type'] = 'prioritaire';
    
    echo json_encode([
        'success' => true,
        'distribution' => $distribution,
        'count' => count($distribution['distribution']),
        'stock_distribue' => $distribution['stock_distribue']
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
