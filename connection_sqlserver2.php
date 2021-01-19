<?php

class Conexao
{
    private static $connection;

    private function __construct(){}

    public static function getConnection($servidor='', $banco='', $porta='', $usuario='', $senha='') {

        //echo "class: ".$servidor."-".$banco."-".$porta."-".$usuario."-".$senha."-";

        $domainName = $_SERVER['SERVER_NAME'];
        $pdoConfig  = DB_DRIVER . ":". "Server=" . DB_HOST . ";";
        $pdoConfig .= "Database=".DB_NAME.";";

        $dbname = "{$banco}";
        $idPort = "{$porta}";
        $ipHost = "{$servidor}";
        $user = "{$usuario}";
        $pass = "{$senha}";
        $libDriver = 'dblib';

        try {
            if(!isset($connection)){

                $connection = new PDO("{$libDriver}:host={$ipHost}:{$idPort};dbname={$dbname}", "{$user}", "{$pass}");

                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $connection;
        } catch (PDOException $e) {
            $mensagem = "Drivers disponiveis: " . implode(",", PDO::getAvailableDrivers());
            $mensagem .= "\nErro: " . $e->getMessage();
            //throw new Exception($mensagem);
            echo "<strong style='color: red'>[EXCEPTION::function getConnection]</strong><br />";
            echo "<pre>";
            var_dump(preg_replace('/[eE][rR][rR][oO][rR]|[eE][rR][rR][oO]/','<strong>Error</strong>', $mensagem));
            echo "</pre>";
            exit;
        }
    }
}