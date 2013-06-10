<?php
class ApiView {
    public function render($content) {
        // header('Content-Type: application/json; charset=utf8');
        print_r($content);
        // echo json_encode($content);
        return true;
    }
} 

?>