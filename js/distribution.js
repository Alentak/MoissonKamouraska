$(document).ready(function () {

    jObjInventaire = [];

    poidsTotal = 0;

    prixTotal = 0;

    //---FONCTION--------------------------------------------------------------------------------------------

    function pastille(pdate, pNbJour) {
        oneDay = 24 * 60 * 60 * 1000;
        pdate = new Date(pdate);
        datejour = new Date();
        pdate.setHours(0, 0, 0);
        datejour.setHours(0, 0, 0);
        diffDays = Math.round(Math.abs((pdate.getTime() - datejour.getTime()) / (oneDay))) + 1;

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

    function Indice() {
        if (jObjInventaire[0].length > 0 && jObjInventaire[1].length > 0) {
            idP = $('#nomProduit option:selected').val();
            idG = $('#Groupe option:selected').val();

            PrioG = 0;
            pourcent = 0;
            i = 0;
            while (i < jObjInventaire[0].length && jObjInventaire[0][i].CLI_ID != idG) {
                i++;
            }
            PrioG = jObjInventaire[0][i].PRI_ID;

            i = 0;
            while (i < jObjInventaire[1].length && jObjInventaire[1][i][0].id != idP) {
                i++;
            }
            pourcentMoisson = parseInt(jObjInventaire[1][i][0].PourcentMoisson, 10);
            pourcent = parseInt(jObjInventaire[1][i][0].Prio[PrioG - 1], 10);
            nbIndice = parseInt(jObjInventaire[1][i][0].NbProduit, 10);
            $('#indiceProduit').text(Math.round(pourcent * (nbIndice - (nbIndice * pourcentMoisson / 100))) / 100);
        }

    }

    function caption(p_poids, p_prix) {
        $("#pdsTotal").text("Poids Total : " + Math.round(p_poids * 100) / 100);
        $("#prixTotal").text("Prix Total : " + Math.round(p_prix * 100) / 100);
    }

    function getLignes(p_libelle) {
        i = 0;
        while (i < jObjInventaire[1].length && jObjInventaire[1][i][0].tpr_Libelle != p_libelle) {
            i++;
        }
        arrayProduit = jObjInventaire[1][i];
        return arrayProduit;
    }

    function calculPoidsPrix() {
        retour = false;
        nbSaisi = $('#nbProduit').val();
        nomProduit = $('#nomProduit option:selected').text();
        nbLot = 0;

        Lignes = getLignes(nomProduit);

        nbLot = Lignes[0].NbProduit;
        prixU = Lignes[0].TPR_Prix;

        nbLot = parseInt(nbLot, 10);
        nbDLC = 0;
        poidsTot = 0;

        if (nbSaisi <= nbLot) {
            retour = true;
            for (let index = 1; index < Lignes.length; index++) {
                if (nbSaisi <= parseInt(Lignes[index].LOT_NbProduit, 10)) {

                    poidsTot += nbSaisi * parseFloat(Lignes[index].LOT_POIDS) / nbLot;
                }
                else {
                    nbSaisi -= parseInt(Lignes[index].LOT_NbProduit, 10);
                    poidsTot += parseInt(Lignes[index].LOT_NbProduit, 10) * parseFloat(Lignes[index].LOT_POIDS) / nbLot;
                }
            }
        }

        $('#PrixProduit').text(Math.round(prixU * nbSaisi *  10) / 10);
        $('#PoidsProduit').text(Math.round(poidsTot * 10) / 10);

        return retour;
    }

    function remplirListeGroupe() {
        $('#Groupe').empty();

        for (let index = 0; index < jObjInventaire[0].length; index++) {

            tailleFamille = jObjInventaire[0][index].CLI_TAILLEFAMILLE;

            if(tailleFamille == 1)
                tailleFamille = "Petite famille";
                else if(tailleFamille == 2)
                    tailleFamille = "Moyenne famille";
                        else
                            tailleFamille = "Grande famille";


            $('#Groupe').append('<option value="' + jObjInventaire[0][index].CLI_ID + '">' + jObjInventaire[0][index].CLI_NOM + " " + jObjInventaire[0][index].CLI_PRENOM + " / " + tailleFamille + '</option>')
        }
    }


    //---DATE JOUR-----------------------------------------------------------------------------------

    now = new Date();
    today = now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);

    $('#dateDistri').val(today);

    //---JSON----------------------------------------------------------------------------------------

    $.ajax({
        url: "php/getInventaire.php",
        async: false,
        success: function (response) {
            jObjInventaire = JSON.parse(response);
        }
    });

    //---LISTE DEROULANTE----------------------------------------------------------------------------

    for (let index = 0; index < jObjInventaire[1].length; index++) {
        $('#nomProduit').append('<option value="' + jObjInventaire[1][index][0].id + '">' + jObjInventaire[1][index][0].tpr_Libelle + '</option>')
    }

    remplirListeGroupe();

    for (let i = 0; i < jObjInventaire[2].length; i++) {

        $('#MRCGroupe').append('<option value="' + jObjInventaire[2][i].MRC_ID + '">' + jObjInventaire[2][i].MRC_Libelle + '</option>')

    }

    //---INDICE---------------------------------------------------------------------------------------------
    Indice();

    $("#Groupe").on('change', function () {
        Indice();
    });

    $("#nomProduit").on('change', function () {
        if ($('#Groupe option:selected').text() != 'Destinataire') {
            Indice();
            calculPoidsPrix();
        }
    });

    //---SUPPRIMER LIGNE--------------------------------------------------------------------------------------

    $("body").on("click", "#tabDistrib td[name='supp'] i", function () {
        $(this).parent().parent().remove();
        poidsTotal -= $(this).parent().prev().prev().text();
        prixTotal -= $(this).parent().prev().text();

        caption(poidsTotal, prixTotal);
    });

    //---INVENTAIRE--------------------------------------------------------------------------------------------

    for (let i = 0; i < jObjInventaire[1].length; i++) {
        ligne = jObjInventaire[1][i][0];
        $('#tbInv').append('<tr etat="repli"><td><img class="pastille" src=""/></td><td name="Produit">' + ligne.tpr_Libelle + '</td><td>' + ligne.NbProduit + '</td><td>' + ligne.Date + '</td></tr>');
    }

    $('#tbInv tr').click(function () {
        value = $(this).find('td[name="Produit"]').text();

        Lignes = getLignes(value);

        if ($(this).attr("etat") == "repli") {
            for (let index = Lignes.length - 1; index > 0; index--) {
                $('<tr class="table-borderless" name="' + value + '"><td></td><td class="gauche"><img class="pastille" src="' + pastille(Lignes[index].LOT_DLC, Lignes[0].NbJourAlerte) + '" alt="pastille"/></td><td><span>' + Lignes[index].LOT_NbProduit + '</span></td><td>' + Lignes[index].LOT_DLC + '</td></tr>').insertAfter($(this));
            }
            $(this).attr("etat", "depli");
        }
        else {
            $('tr[name="' + value + '"]').remove();
            $(this).attr("etat", "repli");
        }
    });

    //---PASTILLE-----------------------------------------------------------------------------------------

    $('#tbInv tr').each(function () {
        value = $(this).find('td[name="Produit"]').text();
        Lignes = getLignes(value);
        dlc = $('td:eq(3)', this).text();
        dlc = pastille(dlc, Lignes[0].NbJourAlerte);
        $('td:eq(0) > img', this).attr('src', dlc);
    });

    //---VALIDER LA LIGNE------------------------------------------------------------------------------------------

    $('#distribForm').submit(function (event) {
        event.preventDefault();

        if (calculPoidsPrix()) {
            poidsTotal += parseFloat($("#PoidsProduit").text());
            prixTotal += parseFloat($("#PrixProduit").text());

            caption(poidsTotal, prixTotal);

            $("#tbodyDis").append('<tr><td>' + $("#nomProduit option:selected").text() + '</td> <td>' + $("#nbProduit").val() + '</td><td>' + $('#indiceProduit').text() + '</td><td>' + $("#PoidsProduit").text() + '</td> <td>' + $("#PrixProduit").text() + '</td><td name="supp"><i class="fas fa-trash-alt fa-2x"></i></td></tr>');

            $("#distribForm")[0].reset();
            $("#PoidsProduit").text("0");
            $("#PrixProduit").text("0");
        }
        else {
            $('#validationModal').modal('show');
            $('h4').text('Erreur');
            $('#validationTextModal').text('Le nombre de produit saisi est supérieur au nombre présent dans l\'inventaire');
        }

    });

    //---MODIF POIDS/PRIX------------------------------------------------------------------------------------------- 

    $('#nbProduit').on("input", function () {
        calculPoidsPrix();
    });

    //---VALIDATION--------------------------------------------------------------------------------------------

    $('#soumettreForm').submit(function (event) {
        event.preventDefault();

        if ($("#tbodyDis tr").length > 0) {
            jObjOrdre = [];
            $("#tbodyDis tr").each(function () {
                nbSaisi = $('td:eq(1)', this).text();
                libProduit = $('td:eq(0)', this).text();

                nbLot = 0;

                Lignes = getLignes(libProduit);
                idProd = Lignes[0].id;
                nbLot = Lignes[0].NbProduit;

                nbLot = parseInt(nbLot, 10);
                nbDLC = 0;
                compteur = 1;

                //On veut générer les ordres sql en fonction du nombre saisit
                //Il y a plusieurs lots différents, si on saisit un nombre inférieur à un lot il faut update, si il est égal il faut delete
                //Si le nombre est supérieur à un lot et entame un autre lot il faut faire un delete et un update
                //Il faut aussi génerer l'ordre d'insertion dans l'historique
                while (nbSaisi > 0) {
                    //On récupere le nombre de produit dans le premier lot
                    nbDLC = parseInt(Lignes[compteur].LOT_NbProduit, 10);
                    // Si c'est supérieur ou égale on delete
                    if (nbSaisi >= nbDLC) {
                        nbTemporaire = nbDLC;
                        SqlOrdre1 = "delete from t_lot where lot_id=" + Lignes[compteur].LOT_ID;
                        //Si c'est supérieur il faut bien déduire du nombre de lot saisi
                        if (nbSaisi > nbDLC) {
                            nbSaisi -= nbDLC;
                        }
                        else {
                            nbSaisi = 0;
                        }
                    }
                    else { // Si il est inférieur on fait un update
                        nbTemporaire = nbSaisi;
                        nbordre = nbDLC - nbSaisi;
                        SqlOrdre1 = "Update t_lot set LOT_NbProduit=" + nbordre + " where lot_id=" + Lignes[compteur].LOT_ID;
                        nbSaisi = 0;
                    }
                    //Ordre d'insertion dans l'historique
                    SqlOrdre2 = "insert into t_historique(TPR_ID,CLI_ID,HIS_PoidsUnitaire,HIS_DLC,HIS_NbProduit,HIS_TypeEchange,HIS_Date) VALUES(" + idProd + "," + $("#Groupe").val() + "," + Lignes[compteur].LOT_POIDS / parseInt(Lignes[0].NbProduit, 10) * nbTemporaire + ",'" + Lignes[compteur].LOT_DLC + "'," + nbTemporaire + ",'D','" + $("#dateDistri").val() + "')";

                    //On push les ordres
                    jObjOrdre.push(SqlOrdre1);
                    jObjOrdre.push(SqlOrdre2);
                    compteur++; // On incrémente le compteur pour que le lot suivant soit sélectionné durant la prochaine boucle
                }
            });
            jObjOrdre = JSON.stringify(jObjOrdre);
            $.post(
                'php/setDistribution.php',
                {
                    jObjOrdre: jObjOrdre
                },
                function (data) {

                },
                'text'
            );

            $('#validationModal').modal('show');
            $('h4').text('Succcès');
            $('#validationTextModal').text('La distribution a bien été ajoutée');
        }
        else {
            $('#validationModal').modal('show');
            $('h4').text('Erreur');
            $('#validationTextModal').text('Veuillez ajouter au moins un produit pour la distribution');
        }

    });

    //---APPARITION MODAL-------------------------------------------------------------------------------------

    $('#ajoutGroupe').click(function () {
        $('h4').text('Ajout d\'un groupe');
        $('#validerGroupeModal').text("Ajouter");
    });

    $('#modifGroupe').click(function () {
        $('h4').text('Modification d\'un groupe');
        $('#validerGroupeModal').text("Modifier");

        i = 0;
        while (i < jObjInventaire[0].length && jObjInventaire[0][i].CLI_ID != $("#Groupe option:selected").val()) {
            i++;
        }

        $('#nomGroupe').val(jObjInventaire[0][i].GRO_LIBELLE);

        $("#Priorite").prop('selectedIndex', jObjInventaire[0][i].PRI_ID - 1);
        $('#MRCGroupe').prop('selectedIndex', jObjInventaire[0][i].MRC_ID - 1);
    });

    //---MODAL VALIDATION-------------------------------------------------------------------------------------------

    $('#groupeModal').submit(function (event) {
        event.preventDefault();

        if ($('#validerGroupeModal').text() == 'Ajouter') {
            $.post(
                'php/setGroupe.php',
                {
                    nomGroupe: $('#nomGroupe').val(),
                    Priorite: $('#Priorite option:selected').val(),
                    MRCGroupe: $('#MRCGroupe').val()
                },
                function (data) {
                    jObjInventaire[0] = JSON.parse(data);
                    remplirListeGroupe();
                },
                'text');
        }
        else {
            index = $('#Groupe').prop('selectedIndex');

            i = 0;
            while (i < jObjInventaire[0].length && jObjInventaire[0][i].CLI_NOM != $('#Groupe option:selected').text()) {
                i++;
            }
            IDGroupe = jObjInventaire[0][i].CLI_ID;

            $.post(
                'php/setGroupe.php',
                {
                    ID: IDGroupe,
                    nomGroupe: $('#nomGroupe').val(),
                    Priorite: $('#Priorite option:selected').val(),
                    MRCGroupe: $('#MRCGroupe').val()
                },
                function (data) {
                    jObjInventaire[0] = JSON.parse(data);
                    remplirListeGroupe();
                    $("#Groupe").prop('selectedIndex', index);
                    Indice();
                },
                'text');
        }
        $('#groupeModal').modal('hide');
    });

    $('#groupeModal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    });

    $('#validationModal').on('hidden.bs.modal', function () {
        if ($("#tbodyDis tr").length > 0) {
            location.reload();
        }
    });
});