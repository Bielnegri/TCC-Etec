var dialogStatusPedido, btnConfirma;
// Aguarda o carregamento da página e define os eventos
// a serem monitorados
document.addEventListener('DOMContentLoaded', function() {
    // Pega os elementos da tela de confirmação exclusão
    // pelo id
    dialogStatusPedido = document.getElementById("dialog-status-pedido"); 

    btnConfirmaStatus = document.getElementById("confirmStatus");  
    // Evento do click no botão cancelar
    // da tela de confirmação
    document.getElementById("cancelaStatus").onclick = function(){fechaDialog(dialogStatusPedido)};

});

function showDialogStatus(dialog,codigo,status,urlStatus,urlList) {

    document.getElementById("codigoPedido").value = codigo;
    document.getElementById("selectStatus").value = status;

    // Mostra a tela
    dialog.style.display = "block";

    btnConfirmaStatus.onclick = function(){confirmaStatus(urlStatus,urlList)};
}

// Define o que o botão conforma deve fazer
function confirmaStatus(urlStatus,urlList){
    dialogStatusPedido.style.display = "none";
    submitDados(
        [], 
        'Alterando...',
        new FormData(document.getElementById('formStatusPedido')),
        urlStatus, 
        function(response){
            document.getElementById("overlay").style.display = "none";
            //goPage(urlList+'?codMsg='+response.message);
            // com o que retornou do backend
            let msg = systemMessages.find(item => item.id == response.message);
            if(msg != undefined){
                showSnackBar(msg.message);
            }else{
                showSnackBar(response.message);
            }
            location.reload();
        },
        function(response){
            document.getElementById("overlay").style.display = "none";
            // define o texto da snackbar de acordo
            // com o que retornou do backend
            let msg = systemMessages.find(item => item.id == response.message);
            if(msg != undefined){
                showSnackBar(msg.message);
            }else{
                showSnackBar(response.message);
            }
        }
    );
};