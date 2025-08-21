// Mensagens do Sistema
const systemMessages = [
    {id: 1, message: 'Você não tem permisssão.'},
    {id: 2, message: 'Código Inválido.' },
    {id: 3, message: 'Problema conexão com o banco de dados.' },
    {id: 4, message: 'Registro não encontrado.' },
    {id: 5, message: 'Cadastrado com sucesso.' },
    {id: 6, message: 'Alterado com sucesso.' },
    {id: 7, message: 'Excluído com sucesso.' }
]

const diasSemana = [
    {id: 0, nome: 'Domingo'},
    {id: 1, nome: 'Segunda-feira'},
    {id: 2, nome: 'Terça-feira'},
    {id: 3, nome: 'Quarta-feira'},
    {id: 4, nome: 'Quinta-feira'},
    {id: 5, nome: 'Sexta-feira'},
    {id: 6, nome: 'Sábado'}
]

const meses = [
    {id: 0, nome: 'Janeiro'},
    {id: 1, nome: 'Fevereiro'},
    {id: 2, nome: 'Março'},
    {id: 3, nome: 'Abril'},
    {id: 4, nome: 'Maio'},
    {id: 5, nome: 'Junho'},
    {id: 6, nome: 'Julho'},
    {id: 7, nome: 'Agosto'},
    {id: 8, nome: 'Setembro'},
    {id: 9, nome: 'Outubro'},
    {id: 10, nome: 'Novembro'},
    {id: 11, nome: 'Dezembro'}
]

function isPromise(obj) {
    return (
        !!obj &&
        (typeof obj === "object" || typeof obj === "function") &&
        typeof obj.then === "function"
    );
}

// Verifica se há algum codigo de mensagem
// passada na url para exibição
function getCodMsg(){
    let searchParams = new URLSearchParams(window.location.search);
    if(searchParams.has('codMsg') && searchParams.get('codMsg')>0){
        let param = searchParams.get('codMsg');
        let error = systemMessages.find(item => item.id == param);
        if(error != undefined){
            showSnackBar(error.message);
        }
    }
}

// Função para ir para outra página
function goPage(page){
    window.location.href = page;
}

// Função verifica se o campo está válido de acordo
// com um padrão 
function validaCampos(fields){
    // Iterar sobre o objeto fields
    for (let i = 0; i < fields.length; i++) {
        // pega um único field
        let field = fields[i];
        // Verifica se o valor do field é nulo 
        // ou em branco
        // ou se ele obedece ao padrã Regex
        // e nesses casos exibe uma mensagem
        // para o usuário
        if(field.value===null || field.value=="" || field.regex.test(field.value) == false)
        {
            showSnackBar(field.message);
            return false;
        } 
    }

    return true;
}

// Função para submeter dados do formuário para o backend
function submitDados(fields, blockMessage, dados, url, successFunction, failFunction = null){
    //Valida os campos do formulario
    if(validaCampos(fields)==false){
        return false;
    };
    // se ha um texto de bloqueio de tela então
    // realiza o bloqueio
    if(blockMessage && blockMessage !== ''){
        // informando o texto do overlay
        document.getElementById("textOverlay").innerHTML = blockMessage;
        // exibindo o overlay
        document.getElementById("overlay").style.display = "block";
    }
        
    // enviando os dados do form              
    fetch(url, {
        method: "POST",
        body: dados
    })
    // Clona o objeto response e tenta converter para Json
    // Se não conseguir retorna uma promisse com o response em texto
    .then(response => response.clone().json().catch(() => Promise.reject(response.text())))
    // Retornou um Json válido então processa a resposta
    .then(response => {
        //retorno for bem sucedido envia para tela de menu
        if(response.status){
            // executa a função de sucesso
            // passada como parametro
            // detalhe que pode existir a
            // necessidade de compartilhar informações
            // dessa função com a função que é recebida
            // no parametro
            successFunction(response);
        }else{
            if(failFunction){
                failFunction(response);
            }else{
                // Esconde a overlay
                document.getElementById("overlay").style.display = "none";
                // se houver algum erro mostra a snackbar
                // define o texto da snackbar de acordo
                // com o que retornou do backend
                let error = systemMessages.find(item => item.id == response.message);
                if(error != undefined){
                    showSnackBar(error.message);
                }else{
                    showSnackBar(response.message);
                }
            }
        }
    }) 
    .catch(err => {
        // Processa a promisse e mostra o texto
        err.then(err =>{
            // Esconde a overlay
            document.getElementById("overlay").style.display = "none";
            // Mostra a mensagem com o erro
            showSnackBar(err);
        });
    });
    
    return true;
}

// Função Mostra a tela de confirmação
function showDialogExclusao(dialog, text,codigo,urlDelete,urlList) {
    // Define o texto a ser exibido na tela
    dialogtext.innerHTML = text;
    // Mostra a tela
    dialog.style.display = "block";
    // Define o que o botão conforma deve fazer
    btnConfirma.onclick = function(){confirm(dialog,codigo,urlDelete,urlList)};
}

// Função do botão cancelar da tela de confirmação
// fecha a tela de confirmação
function fechaDialog(dialog) {
    dialog.style.display = "none";
}

// Função de Confirmação da Exclusão
function confirm(dialog,codigo,urlDelete,urlList) {
    // Esconde a tela de confirmação
    dialog.style.display = "none";
    // Pegando os dados do form
    var dados = new FormData();
    dados.append("codigo", codigo);
    // enviando os dados do form  
    submitDados(
        [], 
        "Excluindo...",
        dados,
        urlDelete, 
        function(response){
            // define o texto da snackbar de acordo
            // com o que retornou do backend
            goPage(urlList+'?codMsg='+response.message);
        }
    );
}

function getDataAtual(){
    let data = new Date();
    let diaSem = data.getDay();
    let Nmes = data.getMonth();
    let Ndia = data.getDate();
    let ano = data.getFullYear();

    let dia = diasSemana.find(item => item.id == diaSem)
    let mes = meses.find(item => item.id == Nmes)

    let dataText = "Dia do acesso: " + dia.nome +", " + Ndia + " de " + mes.nome + " de " + ano;

    return dataText;
}

function fnAllowNumbersAndDotKey(input, event, dot) 
{
    var charCode = (event.which) ? event.which : event.keyCode;
   
    if (charCode == 46) 
    {
        if (!dot)
        {
            return false;
        }
        //only one dot (.) allow
        if (input.value.indexOf('.') === -1)
        {
            return true;
        } 
        else 
        {
            return false;
        }
    } 
    else 
    {   
        if (charCode > 31 && (charCode < 48 || charCode > 57))
        {
            return false;
        }
    }
    return true;
}

//Chama a mascara para telefone e celular
function mascararTelefoneCelular(event){
    let input = event.target;
    input.value = mascaraTelefoneCelular(input.value);
}
//Mascara o campo no formato telefone e celular
function mascaraTelefoneCelular(value){
    if (!value) return "";
    value = value.replace(/\D/g,'');
    value = value.replace(/(\d{2})(\d)/,"($1) $2");
    value = value.replace(/(\d)(\d{4})$/,"$1-$2");
    return value;
}

//Chama a mascara para o CEP
function mascararCep(event){
    let input = event.target;
    input.value = mascaraCep(input.value);
}
//Mascara do CEP  
function mascaraCep(value){
    if (!value) return "";
    value = value.replace(/\D/g,'');
    value = value.replace(/(\d{5})(\d)/,'$1-$2');
    return value;
}

//Chama a mascara para o CPF
function mascararCpf(event){
    let input = event.target;
    input.value = mascaraCpf(input.value);
}
//Mascara da CPF
function mascaraCpf(value) {
    if (!value) return "";
    value = value.replace(/\D/g,'');
    value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g,"\$1.\$2.\$3\-\$4");
    return value;
}

//Chama validacao do CPF
function verificarCpf(event){
    let input = event.target;
    if(!validarCpf(input.value)){
        showSnackBar('CPF inválido');
        input.value = "";
        input.focus();
        return false;
    }
    return true;
}
//Funca para verificar validade do CPF
function validarCpf(cpf) {	
	cpf = cpf.replace(/[^\d]+/g,'');	
	if(cpf == '') return false;	
	// Elimina CPFs invalidos conhecidos	
	if (cpf.length != 11 || 
		cpf == "00000000000" || 
		cpf == "11111111111" || 
		cpf == "22222222222" || 
		cpf == "33333333333" || 
		cpf == "44444444444" || 
		cpf == "55555555555" || 
		cpf == "66666666666" || 
		cpf == "77777777777" || 
		cpf == "88888888888" || 
		cpf == "99999999999")
			return false;		
	// Valida 1o digito	
	add = 0;	
	for (i=0; i < 9; i ++)		
		add += parseInt(cpf.charAt(i)) * (10 - i);	
		rev = 11 - (add % 11);	
		if (rev == 10 || rev == 11)		
			rev = 0;	
		if (rev != parseInt(cpf.charAt(9)))		
			return false;		
	// Valida 2o digito	
	add = 0;	
	for (i = 0; i < 10; i ++)		
		add += parseInt(cpf.charAt(i)) * (11 - i);	
	rev = 11 - (add % 11);	
	if (rev == 10 || rev == 11)	
		rev = 0;	
	if (rev != parseInt(cpf.charAt(10)))
		return false;		
	return true;   
}