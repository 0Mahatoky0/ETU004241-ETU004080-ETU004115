<?php

use app\models\DonModel;

$model = new DonModel(Flight::db());

var_dump($model->insertDon(1,40,"ONG 1","2025-01-01")); 

?>