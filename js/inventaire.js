$(document).ready(function () {

    $.ajax({
        url: "php/getInventaire.php",
        async: false,
        success: function (response) {
            jObjInventaire = JSON.parse(response);
        }
    });

    for (let i = 0; i < jObjInventaire[1].length; i++) {
        ligne = jObjInventaire[1][i][0];
        console.log(ligne);
        $('#tabInv').append('<tr etat="repli"><td><img class="pastille" src=""/></td><td name="Produit">' + ligne.tpr_Libelle + '</td><td>' + ligne.NbProduit + '</td><td>' + ligne.Date + '</td><td>' + ligne.Poids + '</td><td>$' + Math.round(parseFloat(ligne.TPR_Prix, 10) * parseFloat(ligne.NbProduit, 10) * 100) / 100 + '</td></tr>');
    }

    $('tbody tr').each(function () {
        dlc = $('td:eq(3)', this).text();
        value = $(this).find('td[name="Produit"]').text();
        Lignes = getLignes(value);
        dlc = pastille(dlc, Lignes[0].NbJourAlerte);
        $('td:eq(0) > img', this).attr('src', dlc);
    });

    //Affichage des lots correspondant au produit cliqué
    $('tbody tr').click(function () {
        value = $(this).find('td[name="Produit"]').text();
        var lignes;
        lignes = getLignes(value);

        if ($(this).attr("etat") == "repli") {
            for (let index = lignes.length - 1; index > 0; index--) {
                $('<tr class="table-borderless" name="' + value + '"><td></td><td class="gauche"><img class="pastille" src="' + pastille(lignes[index].LOT_DLC, lignes[0].NbJourAlerte) + '" alt="pastille"/></td><td><span>' + lignes[index].LOT_NbProduit + '</span></td><td>' + lignes[index].LOT_DLC + '</td><td>' + Math.round(lignes[index].LOT_POIDS*10)/10 + '</td><td>$' + Math.round(lignes[index].LOT_NbProduit * parseFloat(lignes[0].TPR_Prix, 10) * 100) / 100 + '</td></tr>').insertAfter($(this).closest('tr'));
            }
            $(this).attr("etat", "depli");
        }
        else {
            $('tr[name="' + value + '"]').remove();

            $(this).attr("etat", "repli");
        }
    });

    function pastille(pdate, pNbJour) {
        var oneDay = 24 * 60 * 60 * 1000;
        pdate = new Date(pdate);
        datejour = new Date();
        pdate.setHours(0, 0, 0);
        datejour.setHours(0, 0, 0);
        var diffDays = Math.round(Math.abs((pdate.getTime() - datejour.getTime()) / (oneDay))) + 1;
        var chemin;
        if (diffDays < pNbJour) {
            chemin = "img/prouge.png";
        }
        else {
            if (diffDays < pNbJour * 2) {
                chemin = "img/porange.png";
            }
            else {
                chemin = "img/pverte.png";
            }
        }
        return chemin;
    }

    function getLignes(p_libelle) {
        i = 0;
        while (i < jObjInventaire[1].length && jObjInventaire[1][i][0].tpr_Libelle != p_libelle) {
            i++;
        }
        arrayProduit = jObjInventaire[1][i];
        return arrayProduit;
    }
});