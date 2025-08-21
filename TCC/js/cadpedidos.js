//Variavel global para guardar os itens do pedido
listItemsPedido = [];
//Variaveis globais do items na tela
var dialogPesquisaCliente, dialogPesquisaProduto, tbodyCliente, tbodyProduto;
tbodyItemsPedido = document.getElementById("tbodyItemsPedido");
// Aguarda o carregamento da página e define os eventos
// a serem monitorados
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se há alguma mensagem passada na url
    getCodMsg();

    //Pegando alguns elementos da pagina assim que tudo estiver pronto
    document.getElementById("overlay").style.display = "none";
    dialogPesquisaCliente = document.getElementById("dialog-pesquisa-cliente");
    dialogPesquisaProduto = document.getElementById("dialog-pesquisa-produto");
    tbodyCliente = document.getElementById("tbodyClienteSearch");
    tbodyProduto = document.getElementById("tbodyProdutoSearch");
    tbodyItemsPedido = document.getElementById("tbodyItemsPedido");

    // Quando usuário clicar em entrar será feito todos os passo abaixo
    document.getElementById("cadastrar").onclick = function() {
        
        //Criando o objeto Json do itens do Pedido
        listItemsPedido = criarlistItemsPedido(tbodyItemsPedido);

        // Objeto com o campos a serem verificados no submit do formulário
        // contém o valor do campo, padrão regex e mensagem a ser
        // exibida em caso de ser inválido
        let fields = [
            {
                value: document.getElementById('formapagamento').value, 
                regex: /^[0-9]*$/g, 
                message: 'Campo Forma Pagamento em branco ou com caracteres inválidos'
            },
        ];

        if(listItemsPedido.length<1){
            showSnackBar("É necessário Produtos no pedido.");
            return false;
        }

        //Pegando os dados do formulário
        var dados = new FormData(document.getElementById('formCadPedidos'));
        //Adicionando os itens do Pedido no formato JSON
        dados.append('itemsPedido', JSON.stringify(listItemsPedido));
        // Chama a função submit de dados do formulário
        // passando os parametros
        // fields: campos para validação,
        // mensagem de bloqueio da tela,
        // dados do formulário,
        // e função a ser executado em caso de sucesso.
        submitDados(
            fields, 
            'Salvando...',
            dados,
            'backend/pedidos_cad.php', 
            function(response){
                location.href = "listPedidos.php?codMsg="+response.message;
            }
        );
    };

    //Abre a tela de pesquisa de cliente
    document.getElementById("cliente").onclick = function() {
        showDialogPesquisa(dialogPesquisaCliente);
    }
    
    //Abre a tela de pesquisa do produto
    document.getElementById("btnItemsPedido").onclick = function() {
        showDialogPesquisa(dialogPesquisaProduto);
    }

    //Faz a busca de Cliente
    document.getElementById("pesquisarCliente").onclick = function(){    
        //Limpa as linhas do grid
        cleanTbody(tbodyCliente);
        //Adiciona uma linha informando que está realizando a pesquisa
        insereRowPesquisando('regBrancoSearchCliente', tbodyCliente);          
        // Pegando os dados do form
        var dados = new FormData(document.getElementById('formPesquisaCliente'));
        // enviando os dados do form 
        submitDados(
            [], 
            '',
            dados,
            'backend/searchclientes.php', 
            function(response){
                //Pega a resposta com os registros encontrados
                var rows = JSON.parse(response.data);
                //Limpa as linhas do grid
                cleanTbody(tbodyCliente);
                //Percorrendo os clientes retornados e incluindo 
                //A linha com os dados na tabela de clientes
                rows.forEach(function(reg){
                    //Inserindo linhas na tabela de clientes
                    createRowsClientes(reg, dialogPesquisaCliente, tbodyCliente);
                });
                //Limpando dados do form
                document.getElementById('formPesquisaCliente').reset();
            },
            function(response){
                //Limpa as linhas do grid
                cleanTbody(tbodyCliente);
                //Inseri uma linha com a informação de registro não encontrado
                insereRowNaoEncontrado('regBrancoSearchCliente', tbodyCliente);
                //Limpando dados do form
                document.getElementById('formPesquisaCliente').reset();
            }
        );
    }

    //Pesquisa os produtos
    document.getElementById("pesquisarProduto").onclick = function(){   
        //Limpando a linhas da tabela
        cleanTbody(tbodyProduto);
        //Insere uma linha com a informação que está realizando a pesquisa
        insereRowPesquisando('regBrancoSearchProduto', tbodyProduto);           
        // Pegando os dados do form
        var dados = new FormData(document.getElementById('formPesquisaProduto'));
        // enviando os dados do form
        submitDados(
            [], 
            '',
            dados,
            'backend/searchprodutos.php', 
            function(response){
                //Pegando os registros retornados
                 var rows = JSON.parse(response.data);
                //Limpando as linhas da tabela
                cleanTbody(tbodyProduto);
                //Percorrendo os registro e incluindo as linhas com os dados
                rows.forEach(function(reg){
                    //Incluindo uma linha na tabela
                    createRowsProdutos(reg, dialogPesquisaProduto, tbodyProduto, tbodyItemsPedido);
                });
                //Limpando dados do form
                document.getElementById('formPesquisaProduto').reset();
            },
            function(response){
                //Nao encontrando nada
                //Limpa as linhas da grid
                cleanTbody(tbodyProduto);
                //Inclui uma linha informando que nao encontrou registros
                insereRowNaoEncontrado('regBrancoSearchProduto', tbodyProduto);
                //Limpando dados do form
                document.getElementById('formPesquisaProduto').reset();
            }
        );
    }
    //Fechando a janela de pesquisa e limpando a tabela de clientes
    document.getElementById("closeClienteSearch").onclick = function(){
        fechaDialog(dialogPesquisaCliente);
        cleanTbody(tbodyCliente);
    };

    //Fechando a janela de pesquisa e limpando a tabela de produto
    document.getElementById("closeProdutoSearch").onclick = function(){
        fechaDialog(dialogPesquisaProduto);
        cleanTbody(tbodyProduto);
    };
});


function showDialogPesquisa(dialog) {
    dialog.style.display = "block";
}

function cleanTbody(tBody){
    var rowsTbody = tBody.rows.length;
    for(var i = 0; i < rowsTbody; i++){
        tBody.deleteRow(rowsTbody[i]); 
    }
}

function insereRowPesquisando(fieldClass, tBody){
    //Adiciona uma linha informando que está realizando a pesquisa
    //Definindo a coluna
    var row =  '<td class="regBranco '+fieldClass+'"><img src="images/loading.gif" width="20" height="20" style="vertical-align:middle;margin:0px 5px">Pesquisando...</td>';
    //Cria a linha na tabela
    var newrow = tBody.insertRow(tBody.rows.length);
    newrow.innerHTML = row;
}

function insereRowNaoEncontrado(fieldClass, tBody){
    //Adiciona uma linha informando que está realizando a pesquisa
    //Definindo a coluna
    var row =  '<td class="regBranco '+fieldClass+'">Nenhum registro encontrado.</td>';
    var newrow = tBody.insertRow(tBody.rows.length);
    newrow.innerHTML = row;
}

function createRowsClientes(reg, dialogPesquisaCliente, tbodyCliente){
    //Insere uma linha na tabela
    var newrow = tbodyCliente.insertRow(tbodyCliente.rows.length);
    //Definindo uma acao quando clicar na linha da tabela
    //Essa acao pega o registro e envia para atualizar os 
    //dados do cliente no formulario
    newrow.addEventListener('click',function(){updateFieldsCliente(reg, dialogPesquisaCliente, tbodyCliente);}, false);
    //Definindo as colunas da linha
    var row = '<td class="textRight">' + reg.codigo + '</td>'
            + '<td>'+ reg.nome + '</td>'
            + '<td class="textRight">' + reg.cpf + '</td>'
            + '<td class="textRight">' + reg.telefone +'</td>';
    //Inserindo as colunas na linha
    newrow.innerHTML = row;
}

function createRowsProdutos(reg, dialogPesquisaProduto, tbodyProduto, tbodyItemsPedido){
    var newrow = tbodyProduto.insertRow(tbodyProduto.rows.length);
    //Definindo a acao ao clicar na linha
    //acao ira inserir o produto selecionado na tabela de itens do pedido
    newrow.addEventListener('click',function(){updateItemsPedido(reg, dialogPesquisaProduto, tbodyProduto, tbodyItemsPedido);}, false);
    //Definindo as colunas do produto
    var row = '<td class="textRight">' + reg.codigo + '</td>'
            + '<td>'+ reg.nome + '</td>'
            + '<td class="textRight">' + reg.descricao + '</td>'
            + '<td class="textRight">' + reg.quantidade +'</td>'
            + '<td class="textRight">' + reg.valor +'</td>';
    //Inserindo as colunas na linha
    newrow.innerHTML = row;
}

//Atualiza os campos do formulario com as informacoes
// do cliente
function updateFieldsCliente(reg, dialogPesquisaCliente, tbodyCliente){
    //Pegando os campos do formulario que se referem ao cliente
    //e colocando os valores vindos da tela de pequisa de clientes
    document.getElementById("cliente").value = reg.codigo;
    document.getElementById("nome").value = reg.nome;
    document.getElementById("cpf").value = reg.cpf;
    document.getElementById("telefone").value = reg.telefone;
    document.getElementById("cep").value = reg.cep;
    document.getElementById("rua").value = reg.rua;
    document.getElementById("numero").value = reg.numero;
    document.getElementById("complemento").value = reg.complemento;
    document.getElementById("bairro").value = reg.bairro;
    document.getElementById("cidade").value = reg.cidade;
    document.getElementById("uf").value = reg.estado;
    //Fecha a tela de pesquisa clientes
    fechaDialog(dialogPesquisaCliente);
    //Limpa a tabela de pesquisa de clientes
    cleanTbody(tbodyCliente);
}

//Inclui o produto na tabela de itens do pedido
function updateItemsPedido(item, dialogPesquisaProduto, tbodyProduto, tbodyItemsPedido){
    valorProduto = item.valor;
    valorDesconto = 0;
    valorTotal = valorProduto;
    //Verifica se o produto ja nao foi adicionado a lista
    /*if(verificaItemsPedido(tbodyItemsPedido, item.codigo)){
        fechaDialog(dialogPesquisaProduto);
        cleanTbody(tbodyProduto);
        showSnackBar('Produto já adicionado ao Pedido.');
        return;
    }*/
    //Inclui a linha na tabela
    var newrow = tbodyItemsPedido.insertRow(tbodyItemsPedido.rows.length);
    //Definindo as colunas e seus valores    
    var row = '<td class="textRight">' + item.codigo + '</td>'
            + '<td>'+ item.nome + '</td>'
            + '<td class="textRight">' + valorProduto +'</td>'
            + '<td ><input type="text" onkeypress="return fnAllowNumbersAndDotKey(this, event, false);" onchange="verificaEstoque(this);" value="1" onkeyup="calculaDesconto(this);" /></td>'
            + '<td class="textRight">' + valorProduto +'</td>'
            + '<td ><input type="text" onkeypress="return fnAllowNumbersAndDotKey(this, event, true);" value="0" onkeyup="calculaDesconto(this);" /></td>'
            + '<td class="textRight textRed">' + valorDesconto +'</td>'
            + '<td class="textRight textBold textBlue">' + valorTotal +'</td>'
            + '<td class="textCenter"><i class="ico-excluir" role="img" onclick="deleteRow(this, this.parentNode.parentNode.parentNode);"></i></td>'
            + '<td class="hidden">0</td>'
            + '<td class="hidden">incluir</td>'
            + '<td class="hidden">1</td>';
    //Incluindo as colunas na linha da tabela
    newrow.innerHTML = row;
    //Fecha a tela de pesquisa de produto
    fechaDialog(dialogPesquisaProduto);
    //Limpa a tabela de produtos
    cleanTbody(tbodyProduto);
    //Realiza o caculos do valores do pedido
    calculaValoresForm(tbodyItemsPedido);
}

//Funcao para verificar se o produto ja foi
//adicionado ao pedido se ja existe retorna true
//se nao retorna false
function verificaItemsPedido(tbodyItemsPedido, itemNovo){
    var tableRow = tbodyItemsPedido.getElementsByTagName('tr');
    for (var i = 0; i < tableRow.length; i++){
        if(tableRow[i].getElementsByTagName('td')[0].innerHTML == itemNovo){
            return true;
        }
    }
    return false; 
}

//Deleta uma linha da tabela de items do pedido
function deleteRow(btn, tbodyItemsPedido) {
    //Pegando a linha atraves da referencia do botao
    //Necessario voltar os parentes
    var row = btn.parentNode.parentNode;
    //Funcao para verificar a linha excluida e guarda-la
    //pois pode ser necessário trabalhar a informacao no backend
    criarlistItemsPedidoExcluidos(row);
    //Remove a linha da tabela de itens do pedido
    row.parentNode.removeChild(row);
    //Realiza o caculos do valores do pedido
    calculaValoresForm(tbodyItemsPedido);
}

//Adiciona os itens do pedido da tabela itens do pedido
//em uma lista para o backend
function criarlistItemsPedido(tbodyItemsPedido){
    var tableRow = tbodyItemsPedido.getElementsByTagName('tr');
    for (var i = 0; i < tableRow.length; i++){
        var item = new Object();
        item.codigo = tableRow[i].getElementsByTagName('td')[9].innerHTML;
        item.codigoProduto = tableRow[i].getElementsByTagName('td')[0].innerHTML;
        item.quantidade = tableRow[i].getElementsByTagName('td')[3].getElementsByTagName('input')[0].value;
        item.valorUnitario = tableRow[i].getElementsByTagName('td')[2].innerHTML;
        item.valorProdutos = tableRow[i].getElementsByTagName('td')[4].innerHTML;
        item.porcentagemDesconto = tableRow[i].getElementsByTagName('td')[5].getElementsByTagName('input')[0].value;
        item.valorDesconto = tableRow[i].getElementsByTagName('td')[6].innerHTML;
        item.valorTotal = tableRow[i].getElementsByTagName('td')[7].innerHTML;
        item.task = tableRow[i].getElementsByTagName('td')[10].innerHTML;
        item.quantidadeOriginal = tableRow[i].getElementsByTagName('td')[11].innerHTML;
        listItemsPedido.push(item);
    }
    return listItemsPedido;
}

//Calcula o valor do desconto
function calculaDesconto(fieldDesconto){
    //Pega a linha da tabela atraves da referencia do input
    row = fieldDesconto.parentNode.parentNode;
    //Pega os dados da quantidade e valor unitario
    valorUnitario = row.getElementsByTagName('td')[2].innerHTML;
    quantidade = row.getElementsByTagName('td')[3].getElementsByTagName('input')[0].value;
    //Calcula o valor total do produto
    totalProdutos = valorUnitario * quantidade;
    //Altera o valor total do produto na coluna
    row.getElementsByTagName('td')[4].innerHTML = totalProdutos.toFixed(2); 
    //Pega os dados da porcentagem de desconto
    percDesconto = row.getElementsByTagName('td')[5].getElementsByTagName('input')[0].value;
    //Divide a porcentagem por 100 para facilitar o caculo do desconto
    percentagem = percDesconto / 100;
    //Calcula o valor do Desconto
    valorDesconto = totalProdutos * percentagem;
    //Altera o valor do Desconto na colna
    row.getElementsByTagName('td')[6].innerHTML = valorDesconto.toFixed(2);
    //Calcula o valor total do item
    valorTotal = totalProdutos - valorDesconto;
    //Altera o valor total do item
    row.getElementsByTagName('td')[7].innerHTML = valorTotal.toFixed(2);
    //Realiza o caculos do valores do pedido
    calculaValoresForm(row.parentNode);
}

//Calcula o valor total de produtos, descontos e total geral do pedido
function calculaValoresForm(tbodyItemsPedido){
    var ValorProdutos = 0;
    var ValorDescontos = 0;
    var ValorTotal = 0;
    //Pega todas as linhas da tabela de itens do pedido
    var tableRow = tbodyItemsPedido.getElementsByTagName('tr');
    //Percorre todas as linhas e soma os valores
    //dos produtos, descontos, e total
    for (var t = 0; t < tableRow.length; t++){
        ValorProdutos += parseFloat(tableRow[t].getElementsByTagName('td')[4].innerHTML);
        ValorDescontos += parseFloat(tableRow[t].getElementsByTagName('td')[6].innerHTML);
        ValorTotal += parseFloat(tableRow[t].getElementsByTagName('td')[7].innerHTML);
    }
    //Altera os valores dos campos do formulario com a somatoria total
    document.getElementById("valorprodutos").value = ValorProdutos.toFixed(2);
    document.getElementById("descontos").value = ValorDescontos.toFixed(2);
    document.getElementById("total").value = ValorTotal.toFixed(2);
}

//Adiciona os itens do pedido excluido da tabela itens do pedido
//em uma lista para o backend
//Obs.: Esse item e necessario, pois na alteracao se um item for
//excluido ele precisa ser enviado ao backend e a lista acima nao
//o conteria
function criarlistItemsPedidoExcluidos(row){          
    if (row.getElementsByTagName('td')[10].innerHTML=='alterar'){
        var item = new Object();
        item.codigo = row.getElementsByTagName('td')[9].innerHTML;
        item.codigoProduto = row.getElementsByTagName('td')[0].innerHTML;
        item.quantidade = row.getElementsByTagName('td')[3].getElementsByTagName('input')[0].value;
        item.valorUnitario = row.getElementsByTagName('td')[2].innerHTML;
        item.valorProdutos = row.getElementsByTagName('td')[4].innerHTML;
        item.porcentagemDesconto = row.getElementsByTagName('td')[5].getElementsByTagName('input')[0].value;
        item.valorDesconto = row.getElementsByTagName('td')[6].innerHTML;
        item.valorTotal = row.getElementsByTagName('td')[7].innerHTML;
        item.task = 'excluir';
        item.quantidadeOriginal = row.getElementsByTagName('td')[11].innerHTML;
        listItemsPedido.push(item);
    }
}

function verificaEstoque(field){
    var row = field.parentNode.parentNode; 
    var codProduto = row.getElementsByTagName('td')[0].innerHTML;
    var quantidadeOriginal = row.getElementsByTagName('td')[11].innerHTML;
    var quantidadeConsultar;
    if(parseInt(quantidadeOriginal)>=parseInt(field.value)){
        return;
    }else{
        quantidadeConsultar = field.value - quantidadeOriginal;
    }
    var dados =  new FormData();
    dados.append('codProduto', codProduto);
    dados.append('quantidade', quantidadeConsultar);
    submitDados(
        [], 
        'Verificando Estoque...',
        dados,
        'backend/verifica_estoque.php', 
        function(response){
            document.getElementById("overlay").style.display = "none";
        },
        function(response){
            document.getElementById("overlay").style.display = "none";
            showSnackBar(response.message);
            field.value = response.quantidade;
        }
    );
}