<?php

define('DB_HOST'        , "LAPTOP-NBOVNS4I\SQLEXPRESS");
define('DB_USER'        , "");
define('DB_PASSWORD'    , "");
define('DB_NAME'        , "ws");
define('DB_DRIVER'      , "sqlsrv");
define('_PROCEDURE_'    , "PROCEDURE");
define('_EXPERT_'       , "EXPERT");
define('_REL_'          , "Rel");

/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/

require_once "./connection_sqlserver2.php";

extract($_REQUEST);

function getColunas($Conexao, $tableName) {

    try{
        $query     = $Conexao->query("SELECT * FROM sys.columns WHERE object_id = object_id('$tableName')");
        $colunas   = $query->fetchAll();
    }catch(Exception $e){

        echo "<strong style='color: red'>[EXCEPTION::function getColunas]</strong><br />";
        echo "<pre>";
        var_dump(preg_replace('/[eE][rR][rR][oO][rR]/','<strong>Error</strong>', $e->getMessage()));
        echo "</pre>";
        exit;
    }

    return $colunas;

}

function getTableName($idfield, $string) {

    $idfield = explode(".", trim($idfield))[0]; //r.* = r
    //$string  = "Request r INNER JOIN Status s ON s.IDStatus = r.StatusID INNER JOIN Status s ON s.IDStatus = r.StatusID";

    $pattern = "/([a-zA-Z0-9])*(\ )($idfield){1}/";
    $strclear = preg_replace('/(\s|\n|\r\n|\t)/i',' ', $string);
    $matches = array();
    $tmpresults = preg_match($pattern, $strclear, $matches);

    /*echo "<pre>";
    var_dump($matches, $strclear, $pattern);
    echo "</pre>";*/

    if(isset($matches[0])) {
        return explode(" ", trim($matches[0]))[0];
    }

    return false;

}

function queryResultNormalize($cols, $rows) {

    if(count($cols) == 0 || count($rows) == 0) {
        return false;
    }

    $resultNormalized = array();
    $i = 0;

    foreach ($rows as $r) {
        //echo "<p>";print_r($r);echo "</p><br />";
        foreach($cols as $c) {

            //echo "<p>";print_r($c);echo "</p><br />";
            //echo "<p>";print_r($r[$c]);echo "</p><br />";

            if(strstr(trim($c), '.') == true) {
                $y = explode(".", $c)[0].'.';
                $c = explode(".", $c)[1];
                $x = '>>>';
            } else {
                $y = '';
                $x = '';
            }

            if(array_key_exists($c, $r) ||  isset($r[$c])) {
                //echo "<p>";echo($y.$x.$c.":".$r[$c]);echo "</p>";
                $resultNormalized[$i][$y.$c] = $r[$c];
            }
        }

        $i++;
    }

    //echo "<pre>";print_r($resultNormalized);echo "</pre>";exit;
    return $resultNormalized;
}

function utf8Encoding($results) {

    if(count($results) == 0) {
        return false;
    }

    //UTF8
    foreach ($results as $key => $value) {
        foreach ($value as $k => $v) {
            $results[$key][$k] = utf8_encode($v);
        }
    }

    return $results;
}

function getColunasExpert($results_data) {

    if(count($results_data) == 0) {
        return false;
    }

    $colunas = [];

    foreach ($results_data as $key => $value) {

        /*echo "<pre>";
        var_dump($key, $value);
        var_dump($value);
        echo "</pre>";*/
        foreach ($value as $k => $v) {
            /*echo "<pre>";
            var_dump($k, $v);
            echo "</pre>";*/
            $colunas[] = $k;

        }

        break;

    }

    return $colunas;

}

if($acao=='execSql'){

    //echo "acao: ".$servidor."-".$banco."-".$porta."-".$tabela."-".$usuario."-".$senha."-";

    $Conexao = Conexao::getConnection($servidor, $banco, $porta, $usuario, $senha);
    //$Conexao = Conexao::getConnection();

    if($tabela == _PROCEDURE_) {

        echo "<strong>[PROCEDURE]</strong><br /><pre>".$inputSql."</pre>";

    } elseif($tabela == 'Rel') {

        //Quando informado que a consulta possui relacionamentos: JOIN's

        //Limpeza da query
        $querystring = preg_replace('/(\s|\n|\r\n|\t)/i',' ', $inputSql);
        //echo "<pre>".$querystring."</pre><br />";

        $pattern = "/^SELECT/";
        $matches = array();
        $results = preg_match($pattern, $querystring, $matches);
        //echo "<pre>".var_dump($matches)."</pre><br />";

        if($results == 0 || count($matches) == 0) {
            echo "<strong>[QUERY INVALIDA]</strong><br /><pre>".$inputSql."</pre><strong>A Query deve iniciar com um SELECT</strong>";
            exit;
        }

        $pattern = "/^(SELECT)(\ TOP*|\ )(.*)(\ )(FROM)/";
        $matches = array();
        $results = preg_match($pattern, $querystring, $matches);
        //echo "<pre>".var_dump($matches)."</pre><br />";

        if($results == 0 || count($matches) == 0) {
            echo "<strong>[QUERY INVALIDA]</strong><br /><pre>".$inputSql."</pre><strong>Verifique erros de sintaxe em sua Query</strong>";
            exit;
        }

        $fields = preg_replace('/^([0-9])+\ |^\(([0-9])+\)\ ||^\(\ ?([0-9])+\ ?\)\ /', '', trim($matches[3]));
        $getfields = explode(",", $fields);
        //echo "<pre>".var_dump($getfields)."</pre><br />";

        //Quando informado que se trata de um relacionamento
        foreach ($getfields as $f) {
            if(strstr(trim($f), '.*') == true) {

                //Descobrir a qual tabela pertence o campo
                $tmppattern = "/(FROM)(\ )(.*)(\ )(WHERE)/";
                $tmpmatches = array();
                $tmpresults = preg_match($tmppattern, $querystring, $tmpmatches);
                /*echo "<pre>TMP-RESULTS:";
                var_dump($tmpmatches);
                echo "</pre><br />";*/
                //echo $tmpmatches[3];
                $tableName = getTableName($f, $tmpmatches[3]);

                //Listar os campos da tabela relacionada
                $cols = getColunas($Conexao, $tableName);
                foreach ($cols as $k => $v) {
                    $colunas[] = $v['name'];
                    //echo " [".$v['name']."] ";
                }

            } else {
                $colunas[] = trim($f);
            }
        }

//        echo "<pre>";
//        var_dump($colunas);
//        echo "</pre>";

        //echo "<strong>[QUERY OK]</strong><br /><pre>".$inputSql."</pre>";
        //exit;

    } elseif($tabela == _EXPERT_) {

        try{

            $query   = $Conexao->query($inputSql);
            $results = $query->fetchAll(PDO::FETCH_ASSOC);

        }catch(Exception $e){

            echo "<strong style='color: red'>[EXCEPTION::EXPERT]</strong><br />";
            echo "<pre>";
            var_dump(preg_replace('/[eE][rR][rR][oO][rR]/','<strong>Error</strong>', $e->getMessage()));
            echo "</pre>";
            exit;

        }

    } else {

        //Quando informado nome de uma tabela
        $cols = getColunas($Conexao, $tabela);
        foreach ($cols as $k => $v) {
            $colunas[] = $v['name'];
            //echo " [".$v['name']."] ";
        }

        /*echo "<pre>";
        var_dump($colunas);
        echo "</pre>";*/

        //echo "<strong>[TESTE OK]</strong><br /><pre>".$inputSql."</pre>";
        //exit;

    }

    if($tabela != _EXPERT_) {

        try {

            $query = $Conexao->query($inputSql);
            $results = $query->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {

            echo "<strong style='color: red'>[EXCEPTION]</strong><br />";
            echo "<pre>";
            var_dump(preg_replace('/[eE][rR][rR][oO][rR]/', '<strong>Error</strong>', $e->getMessage()));
            echo "</pre>";
            exit;

        }

    }

    if(count($results) == 0) {

        try {

            $query   = $Conexao->query($inputSql);

            while($query->nextRowset()) {
                $results[] = $query->fetchAll(PDO::FETCH_ASSOC);
            }
            $query->closeCursor();

            if(count($results) == 0){

                $rsp = json_encode([
                    'results' => 'Nada encontrado'
                ]);

                echo $rsp;
                exit;
            }

        } catch (Exception $e) {

            echo "<strong>FORCE</strong><strong style='color: red'>[EXCEPTION]</strong><br />";
            echo "<pre>";
            var_dump(preg_replace('/[eE][rR][rR][oO][rR]/','<strong>Error</strong>', $e->getMessage()));
            echo "</pre>";
            exit;
        }
    }

    if($tabela == 'Rel') {
        $results = queryResultNormalize($colunas, $results);
    }

    if($tabela != _PROCEDURE_ && $tabela != _EXPERT_) {

        $results = utf8Encoding($results);

/*        //UTF8
        foreach ($results as $key => $value) {
            foreach ($value as $k => $v) {
                $results[$key][$k] = utf8_encode($v);
            }
        }*/

        $rsp = json_encode([
            'colunas' => $colunas,
            'results' => $results
        ]);

        echo $rsp;
        exit;

    } else {

        if($tabela != _EXPERT_) {

            echo "<strong>[RESPONSE]</strong><br /><pre>";
            var_dump($results);
            echo "</pre>";
            exit;

        } else {

            $colunas = getColunasExpert($results);
            $results = utf8Encoding($results);

            $rsp = json_encode([
                'colunas' => $colunas,
                'results' => $results
            ]);

            echo $rsp;
            exit;
        }

    }
}
