<?php

session_start();

header("Access-Control-Allow-Origin: *");

/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/

extract($_REQUEST);

if($acao == 'auth') {

    require_once("./SqlEditorAuth.php");
    require_once("./SqlEditorLogin.php");

    $oAuth = new SqlEditorAuth();

    if($oAuth) {

        //devel, 123mudar$
        $oLogin = new SqlEditorLogin($name, $pass);

        $resp = 'Erro';
        if ($oLogin->sqlEditorLogin()) {
            $resp = 1;
            $_SESSION['sqleditorlogin'] = $name;
        }

    } else {
        $resp = "Acesso Negado";
    }

    echo base64_encode($resp);
    exit;

}

if($acao == 'getDataConn') {

    require_once("./SqlEditorAuth.php");
    require_once("./SqlEditorDataConn.php");

    $oAuth = new SqlEditorAuth();

    if($oAuth) {

        $oData = new SqlEditorDataConn();
        $resp = $oData->sqlEditorDataConn();

    } else {
        $resp = "Acesso Negado";
    }

    echo base64_encode($resp);
    exit;

}

echo base64_encode("false");
exit;

?>

