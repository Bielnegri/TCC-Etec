var dialogExclusao, dialogtext, btnConfirma;
// Aguarda o carregamento da página e define os eventos
// a serem monitorados
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se há alguma mensagem passada na url
    getCodMsg();

    // Pega os elementos da tela de confirmação exclusão
    // pelo id
    dialogExclusao = document.getElementById("dialog-excluir"); 
    dialogtext = document.getElementById("dialog-text"); 
    btnConfirma = document.getElementById("confirm");  
    // Evento do click no botão cancelar
    // da tela de confirmação
    document.getElementById("cancel").onclick = function(){fechaDialog(dialogExclusao)};
});