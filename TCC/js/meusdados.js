// Aguarda o carregamento da página e define os eventos
// a serem monitorados
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se há alguma mensagem passada na url
    getCodMsg();
    /// Quando usuário clicar em entrar será feito todos os passo abaixo
    document.getElementById("alterar").onclick = function() {
        // Objeto com o campos a serem verificados no submit do formulário
        // contém o valor do campo, padrão regex e mensagem a ser
        // exibida em caso de ser inválido
        let fields = [{
            value: document.getElementById('nome').value, 
            regex: /^[a-zA-Z ÁáÃâÂãÉéÊêÍíÔôÓóÕõçÇ]*$/g, 
            message: 'Campo Nome em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('senha').value, 
            regex: /^[A-Za-z0-9 `\-=[\];\~#\\,./!@#$%:&*()_+{}|<>?]*$/g, 
            message: 'Campo Senha em branco ou com caracteres inválidos'
        }];
        // Chama a função submit de dados do formulário
        // passando os parametros
        // fields: campos para validação,
        // mensagem de bloqueio da tela,
        // dados do formulário,
        // e função a ser executado em caso de sucesso.
        submitDados(
            fields, 
            'Salvando...',
            new FormData(document.getElementById('formMeusDados')),
            'backend/meusDados.php', 
            function(response){
                location.href = "listUsuarios.php?codMsg="+response.message;
            }
        );
    };
});