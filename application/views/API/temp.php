<?php
    //header('Content-Type: application/json; charset=utf-8');
    header('Content-type: text/javascript');
    $result['success'] = true;
    $result['data'] = $jsondata;
    $json = json_encode(
        $result,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );

    echo $json;

 //   $myJsonString = preg_replace('/"([^"]+)"\s*:\s*/', '$1:', $json);


  //  print_r($myJsonString);
?>
