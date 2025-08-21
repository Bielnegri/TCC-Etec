// Aguarda o carregamento da página e define os eventos
// a serem monitorados
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se há alguma mensagem passada na url
    getCodMsg();
    // Recupera o código passado por Querystring
    let searchParams = new URLSearchParams(window.location.search);
    let codigo=searchParams.get('codigo');

    if(codigo>0){
        // Se o código for maior que zero desabilita a alteração
        // do campo usuario
        document.getElementById('usuario').readOnly = true;
    }else{
        // Se for igual a zero limpa os campos do formulário
        // e habilita o campo usuário
        document.getElementById('formCadUsuarios').reset();
        document.getElementById('usuario').readOnly = false;
    }
    
    /// Quando usuário clicar em entrar será feito todos os passo abaixo
    document.getElementById("cadastrar").onclick = function() {
        // Objeto com o campos a serem verificados no submit do formulário
        // contém o valor do campo, padrão regex e mensagem a ser
        // exibida em caso de ser inválido
        let fields = [{
            value: document.getElementById('nome').value, 
            regex: /^[a-zA-Z ÁáÃâÂãÉéÊêÍíÔôÓóÕõçÇ]*$/g, 
            message: 'Campo Nome em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('usuario').value, 
            regex: /^[a-zA-Z 0-9ÁáÃâÂãÉéÊêÍíÔôÓóÕõçÇ \-\_]*$/g, 
            message: 'Campo Usuário em branco ou com caracteres inválidos'
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
            new FormData(document.getElementById('formCadUsuarios')),
            'backend/usuarios_cad.php', 
            function(response){
                location.href = "listUsuarios.php?codMsg="+response.message;
            }
        );
    };
});