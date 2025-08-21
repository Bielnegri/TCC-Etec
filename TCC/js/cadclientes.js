// Aguarda o carregamento da página e define os eventos
// a serem monitorados
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se há alguma mensagem passada na url
    getCodMsg();
    // Quando usuário clicar em entrar será feito todos os passo abaixo
    document.getElementById("cadastrar").onclick = function() {
        // Objeto com o campos a serem verificados no submit do formulário
        // contém o valor do campo, padrão regex e mensagem a ser
        // exibida em caso de ser inválido
        let fields = [{
            value: document.getElementById('nome').value, 
            regex: /^[a-zA-Z 0-9ÁáÃâÂãÉéÊêÍíÔôÓóÕõçÇ \-\_,./\\]*$/g, 
            message: 'Campo Nome em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('cpf').value, 
            regex: /^[0-9.-]*$/g, 
            message: 'Campo CPF em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('telefone').value, 
            regex: /^[0-9() \-]*$/g, 
            message: 'Campo Telefone em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('pais').value, 
            regex: /^[a-zA-Z]*$/g, 
            message: 'Campo País em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('estado').value, 
            regex: /^[a-zA-ZÁáÃâÂãÉéÊêÍíÔôÓóÕõçÇ \-\_,./\\]*$/g, 
            message: 'Campo Estado em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('cidade').value, 
            regex: /^[a-zA-ZÁáÃâÂãÉéÊêÍíÔôÓóÕõçÇ \-\_,./\\]*$/g, 
            message: 'Campo Cidade em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('bairro').value, 
            regex: /^[a-zA-ZÁáÃâÂãÉéÊêÍíÔôÓóÕõçÇ \-\_,./\\]*$/g, 
            message: 'Campo Bairro em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('rua').value, 
            regex: /^[a-zA-ZÁáÃâÂãÉéÊêÍíÔôÓóÕõçÇ \-\_,./\\]*$/g, 
            message: 'Campo Rua em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('numero').value, 
            regex: /^[0-9]*$/g, 
            message: 'Campo Número em branco ou com caracteres inválidos'
        },
        {
            value: document.getElementById('complemento').value, 
            regex: /^[a-zA-Z0-9ÁáÃâÂãÉéÊêÍíÔôÓóÕõçÇ \-\_,./\\]*$/g, 
            message: 'Campo Complemento em branco ou com caracteres inválidos'
        },
        ];
        // Chama a função submit de dados do formulário
        // passando os parametros
        // fields: campos para validação,
        // mensagem de bloqueio da tela,
        // dados do formulário,
        // e função a ser executado em caso de sucesso.
        submitDados(
            fields, 
            'Salvando...',
            new FormData(document.getElementById('formCadClientes')),
            'backend/clientes_cad.php', 
            function(response){
                location.href = "listClientes.php?codMsg="+response.message;
            }
        );
    };
});