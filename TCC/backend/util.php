<?php
    function cleanCampo($campo){
        $pattern = '/[^0-9a-zA-Z\-\.\/:_,@<>()ÁáÃâÂãÉéÊêÍíÔôÓóÕõçÇ;\s]+?|--|\'|#|$|¨|%|"|\bdrop\b|\bdelet\b|\bhaving\b|\binsert\b|\bselect\b|\btruncate\b|\bupdate\b|xp_|\bshutdown\b|\b or \b|\b and \b/';
        $replacement = '';
        return preg_replace($pattern, $replacement, $campo);
    }

    function getStatus($status){
        $textStatus="";
        switch ($status) {
            case 1:
                $textStatus="Finalizado";
              break;
            case 2:
                $textStatus="Pago";
              break;
            case 3:
                $textStatus="Despachado";
              break;
            case 4:
                $textStatus="Entregue";
                break;
            case 5:
                $textStatus="Cancelado";
                break;
            default:
                $textStatus="Em edição";
        }
        return $textStatus;
    }
?>