<?php
// Test simple de simulation
header('Content-Type: application/json');

require_once __DIR__ . '/app/config/bootstrap.php';

session_start();

try {
    $model = new app\models\BNGRCModel();
    $distribution = $model->simulerDistribution();
    
    $_SESSION['distribution'] = $distribution;
    
    echo json_encode([
        'success' => true,
        'distribution' => $distribution,
        'count' => count($distribution)
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
