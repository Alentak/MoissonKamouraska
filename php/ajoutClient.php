<?php

if(isset($_POST['Valider']))
{
    $tableau = $_POST["donnees"];


    foreach($tableau as $tab)
    {
        if(empty($tab))
        $i = false;
        else
        $i = true;
    }


    if($i == false)
    {
        echo "non";
    }
    else{
        echo "oui";

        echo $tableau['nom'];
    }
}


?>