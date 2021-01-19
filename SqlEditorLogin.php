<?php

class SqlEditorLogin
{
    private $name;
    private $pass;

    public function __construct($name, $pass)
    {
        $this->name = $name;
        $this->pass = $pass;
    }

    public function sqlEditorLogin()
    {
        /*AMBIENTE DE DESENVOLVIMENTO: Usuario e Senha Padrão*/
        if(base64_decode($this->name) == "devel" && md5(base64_decode($this->pass)) == "329e179205ab5e347a80c6a878bcdcb9") {
            return true;
        }

        return false;
    }
}

?>