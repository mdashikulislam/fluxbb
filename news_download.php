<?php
define('PUN_ROOT', dirname(__FILE__).'/');
require PUN_ROOT.'include/common.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $today = date('Y-m-d H:i:s');
    $query = "INSERT INTO news_download ( user_id,created) VALUES ('$id','$today' )";
    $insert = $db->query($query);
    if (@$insert){
        die(json_encode([
            'status'=>true
        ]));
    }else{
        die(json_encode([
            'status'=>false
        ]));
    }
}