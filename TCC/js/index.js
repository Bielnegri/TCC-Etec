// Aguarda o carregamento da página e define os eventos
// a serem monitorados
document.addEventListener("DOMContentLoaded", function(e) {
    // Verifica se há alguma mensagem passada na url
    getCodMsg();
    //Recupera a data atual
    let data = getDataAtual();
    document.getElementById('data').innerHTML = data;

    //Criando a possibilidade de entrar usando a tecla enter
    document.onkeydown=function(){
        if(window.event.keyCode=='13'){
            document.getElementById("entrar").click();
        }
    }

    // Quando usuário clicar em entrar será feito todos os passo abaixo
    document.getElementById("entrar").onclick = function() {   
        
        let fields = [{
            value: document.getElementById('usuario').value, 
            regex: /^[a-zA-Z 0-9ÁáÃâÂãÉéÊêÍíÔôÓóÕõçÇ \-\_]*$/g, 
            message: 'Campo Usuário em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('senha').value, 
            regex: /^[A-Za-z0-9 `\-=[\];\~#\\,./!@#$%:&*()_+{}|<>?]*$/g, 
            message: 'Campo Senha em branco ou com caracteres inválidos'
        }];
        
        submitDados(
            fields, 
            'Autenticando...',
            new FormData(document.getElementById('formLogin')),
            'backend/login.php', 
            function(){
                location.href = 'menu.php';
            }
        );

    };
});