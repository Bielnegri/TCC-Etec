// Aguarda o carregamento da página e define os eventos
// a serem monitorados
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se há alguma mensagem passada na url
    getCodMsg();
    // Recupera o código passado por Querystring
    let searchParams = new URLSearchParams(window.location.search);
    let codigo=searchParams.get('codigo');

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
            value: document.getElementById('valor').value, 
            regex: /^[0-9.]*$/g, 
            message: 'Campo Valor em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('descricao').value, 
            regex: /^[A-Za-z0-9 `\-=[\];\~#\\,./!@#$%:&*()_+{}|<>?]*$/g, 
            message: 'Campo Descrição em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('quantidade').value, 
            regex: /^[0-9]*$/g, 
            message: 'Campo Quantidade em branco ou com caracteres inválidos'
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
            new FormData(document.getElementById('formCadProduto')),
            'backend/produtos_cad.php', 
            function(response){
                location.href = "listProdutos.php?codMsg="+response.message;
            }
        );
    };
}); 