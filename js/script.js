
var domain = "DOMAIN";
var endpointApi = "https://"+domain+"/sqleditor/SqlEditorApi.php";
var endpointSql = "https://"+domain+"/sqleditor/sqlQuery.php";

//TODO: Terminar o controle de acesso das aplicacoes
/*Controle de acesso por aplicacao*/
var appOriginUrl = window.location.href;
var getDomain = appOriginUrl.replace("http://", "").replace("https://", "");
getDomain = getDomain.split("/");
var remoteDomain   = getDomain[0];

//console.log(remoteDomain);

//Mensageiro (Tooltip style)
toastr.options = {
    "closeButton": false, // true/false
    "debug": false, // true/false
    "newestOnTop": false, // true/false
    "progressBar": false, // true/false
    "positionClass": "toast-bottom-right",//toast-bottom-center / toast-top-right / toast-top-left / toast-bottom-right / toast-bottom-left0
    "preventDuplicates": true, //true/false,
    "onclick": null,
    "showDuration": "300", // in milliseconds
    "hideDuration": "1000", // in milliseconds
    "timeOut": "5000", // in milliseconds
    "extendedTimeOut": "1000", // in milliseconds
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

var bt_exec_sql = $("#bt-exec-sql-editor");
var bt_exec_sql_test = $("#bt-exec-sql-editor-test");
var check_sql_expert = $("#sql-expert");
var servidor = $("#servidor");
var banco = $("#banco");
var porta = $("#porta");
var tabela = $("#tabela");
var usuario = $("#usuario");
var senha  = $("#senha");
var inputSql = $("#input-sql-editor");
var resultados = $("#resultados");
var pdolib = $("#pdolib");
var tipo_consulta = "";
var container_tools = $("#container-tools");
var new_request = $("#new-request");

function getDataConection() {

    toastr.info("Carregando...");

    $.ajax({

        type: "GET",
        url: endpointApi,
        data: {acao: "getDataConn"},
        async: false,

        success: function(rsp) {

            var response = JSON.parse(atob(rsp));

            servidor.val(response.servidor);
            banco.val(response.banco);
            porta.val(response.porta);
            tabela.val(response.tabela);
            usuario.val(response.usuario);
            senha.val(response.senha);
            pdolib.val(response.pdolib);
            inputSql.val(response.inputSql);

        },

        error: function(err) {
            toastr.error(err.responseText);
        }

    });
}

function sendRequest() {

    $.ajax({

        type: "GET",
        url: endpointSql,
        data: {
            acao: "execSql",
            servidor: servidor.val(),
            banco: banco.val(),
            porta: porta.val(),
            tabela: tabela.val(),
            usuario: usuario.val(),
            senha: senha.val(),
            inputSql: inputSql.val(),
            pdolib: pdolib.val()
        },
        dataType: "json",

        success: function(rsp) {

            if(rsp.results == "Nada encontrado") {

                toastr.error(rsp.results);

            } else {

                container_tools.fadeOut('slow');
                resultados.fadeIn('slow');
                new_request.show();

                //Header da tabela
                resultados.html("<table class='table table-hover hovered'><tr>");

                $.each(rsp.colunas, function (i, obj) {
                    resultados.append("<th>" + obj + "</th>");
                });
                resultados.append("</tr>");

                //Conteudo da tabela
                $.each(rsp.results, function (i, obj) {

                    resultados.append("<tr>");

                    $.each(obj, function (i, obj) {

                        resultados.append("<td>" + obj + "</td>");

                    });

                    resultados.append("</tr>");

                });

                resultados.append("</table>");
            }

        },

        error: function(err) {
            toastr.error(err.responseText);
        }

    });

}

function apiAuthentication(name, pass) {

    $.post(endpointApi,
        {acao: 'auth', name: btoa(name), pass: btoa(pass)},
        function (resp, textStatus, jqXHR) {
            if (parseInt(atob(resp)) == 1) {
                toastr.success("Logado com sucesso!");
                setTimeout(function(){
                    location.href = 'index.php';
                }, 2000);
            } else {
                toastr.error("Erro: Login Invalido!");
                //window.location.href = 'apilogin.php';
            }
        }
    );
}

$(document).ready(function(){

    new_request.on('click', function(){
        container_tools.fadeIn('slow');
        resultados.fadeOut('slow');
        new_request.hide();
    });

    $("#bt-login").on('click', function(){
        var name = $("#name").val();
        var pass = $("#password").val();
        if(name && pass) {
            apiAuthentication(name, pass);
        } else {
            toastr.error("Erro: Informe os dados para login!");
        }
    });

    check_sql_expert.on('change', function(){
        if(tabela.is(':disabled')) {
            tabela.prop('disabled', false);
            tabela.val('');
        } else {
            tabela.prop('disabled', true);
            tabela.val('EXPERT');
        }
    });

    bt_exec_sql_test.on('click', function() {
        getDataConection();
        setTimeout(function(){
            sendRequest();
        }, 1500);
    });

    bt_exec_sql.on('click', function(){

        if(!inputSql.val() || ! servidor.val() || !banco.val() || !tabela.val() || !porta.val() || !tabela.val() || !usuario.val() || !senha.val()) {
            toastr.error("Informe todos os dados para realizar a query");
            return false;
        }

        sendRequest();
    });
});