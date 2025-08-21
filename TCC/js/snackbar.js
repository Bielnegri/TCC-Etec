function showSnackBar(textMessage) {
    // Pega a DIV da snackbar
    var x = document.getElementById("snackbar");

    // Addiciona o texto na snackbar
    x.innerHTML = textMessage;

    // Adiciona o style 'show' na DIV da snackbar
    // para podermos visualizar a mensagem
    x.className = "show";

    // Depois de 5 segundos remove o style "show" da snackbar para esconde-la
    setTimeout(function(){ 
        x.className = x.className.replace('show', '');
    }, 5050);
}