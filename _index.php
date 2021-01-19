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
</head>
<body>
    
    <div id="container-geral">

        <div id="topo">
            <input type="button" id="bt-exec-sql-editor" value="Executar" />
            <input type="text" id="servidor" value="" placeholder="Servidor" />
            <input type="text" id="banco" value="" placeholder="Banco" />
            <input type="text" id="porta" value="" placeholder="Porta" />
            <input type="text" id="tabela" value="" placeholder="Tabela" />
            <input type="checkbox" name="sql-relacional" id="sql-relacional" value="Rel" />SQL Relacional
            <input type="text" id="usuario" value="" placeholder="Usuario" />
            <input type="password" id="senha" value="" placeholder="Senha" />
        </div>

        <div id="sql-editor">
            <fieldset>
                <label>Input SQL</label>
                <textarea id="input-sql-editor"></textarea>
            </fieldset>
        </div>

        <div id="resultados">

        </div>

    </div>
    
    <div id="container-footer" class="container-fluid">
        <div class="row">
            <div class="padding-footer"></div>
        </div>
    </div>

    <script type="text/javascript" src="./js/vendor/jquery/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="./js/script.js"></script>
    
</body>
</html>
