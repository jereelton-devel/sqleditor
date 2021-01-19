<?php

session_start();

/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/

if(!isset($_SESSION['sqleditorlogin']) || $_SESSION['sqleditorlogin'] == "") {
    header("location:apilogin.php");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>SQL EDITOR</title>
    <meta name="description" content="DASHBOARD">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="css/styles.css" />

    <link rel="stylesheet" href="js/vendor/toastr/toastr.min.css"/>
</head>
<body>

<div id="container-tools">

    <div id="sql-editor">
        <textarea id="input-sql-editor"></textarea>
    </div>

    <div id="box-paleta">

        <hr />
        <p>Dados da Conexão</p>
        <br />

        <input type="text" id="servidor" value="" placeholder="Servidor" />
        <input type="text" id="banco" value="" placeholder="Banco" />
        <input type="text" id="porta" value="" placeholder="Porta" />
        <input type="text" id="tabela" value="" placeholder="Tabela" />
        <input type="checkbox" name="sql-expert" id="sql-expert" value="EXPERT" />Modo Expert
        <input type="text" id="usuario" value="" placeholder="Usuario" />
        <input type="password" id="senha" value="" placeholder="Senha" />
        <input type="text" id="pdolib" value="" placeholder="Lib PDO" />

        <button class="btn btn-primary" type="button" id="bt-exec-sql-editor" value="Executar">Executar</button>
        <button class="btn btn-danger" type="button" id="bt-exec-sql-editor-test" value="Testar">Testar</button>

        <div id="sqleditor-quit">
            <a id="a-quit" href="logout.php">Sair</a>
        </div>

    </div>

</div>

</div>

<div id="resultados">

</div>

<div id="new-request">
    <button id="bt-new-request"> > </button>
</div>

<div id="container-footer" class="container-fluid">
    <div class="row">
        <div class="padding-footer"></div>
    </div>
</div>

<script type="text/javascript" src="./js/vendor/jquery/jquery-1.11.3.js"></script>
<script src="js/vendor/toastr/toastr.min.js"></script>
<script type="text/javascript" src="./js/script.js"></script>

</body>
</html>
