<?php
function response_creator($meseg, $status_code, $response){
    $json_meseg = json_encode($meseg);
    $response->getBody()->write($json_meseg);
    return $response->withHeader('Content-Type', 'application/json')->withStatus($status_code);
}
?>