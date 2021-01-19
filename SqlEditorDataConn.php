<?php

class SqlEditorDataConn
{
    private $servidor;
    private $banco;
    private $porta;
    private $tabela;
    private $usuario;
    private $senha;
    private $inputSql;
    private $pdolib;

    public function __construct()
    {
        $fileconf = "config.dat";
        $linesconf = file($fileconf);

        $this->servidor = explode(":", trim($linesconf[0]))[1];
        $this->banco = explode(":", trim($linesconf[1]))[1];
        $this->porta = explode(":", trim($linesconf[2]))[1];
        $this->tabela = explode(":", trim($linesconf[3]))[1];
        $this->usuario = explode(":", trim($linesconf[4]))[1];
        $this->senha = explode(":", trim($linesconf[5]))[1];
        $this->inputSql = explode(":", trim($linesconf[6]))[1];
        $this->pdolib = explode(":", trim($linesconf[7]))[1];
    }

    public function sqlEditorDataConn()
    {
        return json_encode([
            'servidor' => $this->servidor,
            'banco' => $this->banco,
            'porta' => $this->porta,
            'tabela' => $this->tabela,
            'usuario' => $this->usuario,
            'senha' => $this->senha,
            'inputSql' => $this->inputSql,
            'pdolib' => $this->pdolib
        ]);
    }
}

?>