$(document).ready(function () {

    jObjArrivage = [];

    jObjProduitGroupe = [];

    poidsTotal = 0;

    prixTotal = 0;

//---FONCTION--------------------------------------------------------------------------------------------

function remplirListeGroupe()
{
    $('#listeGroupe').empty();

    for (let i = 0; i < jObjProduitGroupe[1].length; i++) 
    {
        $('#listeGroupe').append('<option value="' + jObjProduitGroupe[1][i].GRO_ID + '">' + jObjProduitGroupe[1][i].GRO_LIBELLE + '</option>');
    }
}

function remplirListeProduit()
{
    $('#nomProduit').empty();

    for (let i = 0; i < jObjProduitGroupe[0].length; i++) 
    {
        $('#nomProduit').append('<option value="' + jObjProduitGroupe[0][i].TPR_ID + '">' + jObjProduitGroupe[0][i].TPR_Libelle + '</option>');
    }
}

function calculPoids()
{
    //Poids = nombre de produits * poids unitaire du produit
    $("#PoidsProduit").text(Math.round($("#nbProduit").val() * $("#PUProduit").val()*10)/10);
}

function calculPrix()
{
    i = 0;
    while (i < jObjProduitGroupe[0].length && jObjProduitGroupe[0][i].TPR_ID != $("#nomProduit option:selected" ).val())
    {
        i++;
    }

    //Prix = nombre de produits * prix unitaire du produit
    $("#PrixProduit").text("$"+Math.round($("#nbProduit").val() * jObjProduitGroupe[0][i].TPR_Prix*10)/10);
}

function majPoidsPrix()
{
    $("#pdsTotal").text("Poids Total : " + poidsTotal);
    $("#prixTotal").text("Prix Total : " + prixTotal);
}

//---DATE JOUR-----------------------------------------------------------------------------------

    now = new Date();
    today = now.getFullYear()  + '-' + ('0' + (now.getMonth()+1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);
    
    $('#dateArrivage').val(today);

//---JSON----------------------------------------------------------------------------------------

    $.ajax({
        url: 'php/getProduit.php',
        success: function(data){ 

            jObjProduitGroupe[0] = JSON.parse(data);
            
        },
        async: false
    });

    $.ajax({
        url: 'php/getGroupe.php',
        success: function(data){ 

            jObjProduitGroupe[1] = JSON.parse(data);
            
        },
        async: false
    });

//---LISTE DEROULANTE----------------------------------------------------------------------------

    remplirListeGroupe();

    remplirListeProduit();
    
//---ACTUALISATION POIDS/PRIX----------------------------------------------------------------------------

    //Event à la modification du champ Poids Unitaire
    $('#PUProduit').on("input", function(){
        if($("#nbProduit").val() != "")
        {
            calculPoids();
        }
        if($("#nbProduit").val() != "" && $("#PUProduit").val() != "")
        {   
            calculPrix();
        }
        else
        {
            $("#PrixProduit").text("0");
        }
    })

    //Event à la modification du champ Nb Produits
    $('#nbProduit').on("input", function(){
        if($("#PUProduit").val() != "")
        {
            calculPoids();
        }
        if($("#nbProduit").val() != "")
        {
            calculPrix();
        }
        else    
        {
            $("#PrixProduit").text("0");
        }
    })

    //Event à la modification du type de produit selectionné
    $("#nomProduit").on('change', function() {
        if($("#nbProduit").val() != "" && $("#PUProduit").val() != "")
        {
            calculPrix();
        }
      });

//---SUPPRESSION LIGNE TABLEAU----------------------------------------------------------------------------

    $("body").on( "click", "#tabArriv td[name='supp'] i", function(){
        $(this).parent().parent().remove();

        poidsTotal -= $(this).parent().prev().prev().text();
        prixTotal -= $(this).parent().prev().text();

        majPoidsPrix();
    });

//---VALIDATION AJOUT LIGNE--------------------------------------------------------------------------------------------

    $('#lotForm').submit(function(event) {
        event.preventDefault();

        $("tbody").append('<tr><td>' + $("#nomProduit option:selected" ).text() + '</td> <td>' + $("#DLCProduit" ).val() + '</td> <td>' + $("#nbProduit").val() + '</td> <td>' + $("#PUProduit").val() + '</td> <td>' + $("#PoidsProduit").text() + '</td> <td>' + $("#PrixProduit").text() + '</td><td name="supp"><i class="fas fa-trash-alt fa-2x"></i></td></tr>');

        poidsTotal += parseFloat($("#PoidsProduit").text());
        prixTotal += parseFloat($("#PrixProduit").text());

        majPoidsPrix();

        $("#lotForm")[0].reset();
    });

//---VALIDATION AJOUT ARRIVAGE-------------------------------------------------------------------------------------------- 

    $('#soumettreForm').submit(function(event) {
        event.preventDefault();
        jObjArrivage = [];
        if($("tbody tr").length > 0)
        {
            $("tbody tr").each(function() {
            
                produit = {}
    
                i = 0;
    
                while (i < jObjProduitGroupe[0].length && jObjProduitGroupe[0][i].TPR_Libelle != $('td:eq(0)', this).text()) 
                {
                    i++;
                }
    
                produit ["idType"] = jObjProduitGroupe[0][i].TPR_ID;
                produit ["DLC"] = $('td:eq(1)', this).text();
                produit ["nbProd"] = $('td:eq(2)', this).text();
                produit ["PoidsUnitaire"] = $('td:eq(3)', this).text();
                produit ["idGroupe"] = $("#listeGroupe").val();
                produit ["dateArriv"] = $("#dateArrivage").val();
                
                
                jObjArrivage.push(produit);
            })
            
            jObjArrivage = JSON.stringify(jObjArrivage);
    
            $.post(
                'php/setLot.php', 
                {
                    jObjArrivage : jObjArrivage
                },
    
                function(data){ 
    
                },
                'text'
            );
            
            $('#validationModal').modal('show');
            $('h4').text('Succcès');
            $('#validationTextModal').text('L\'arrivage a bien été ajouté');
            $('table tbody').empty();
            poidsTotal = 0;
            prixTotal = 0;
            majPoidsPrix();
            $("#PoidsProduit").text("0");
            $("#PrixProduit").text("0");
        }
        else
        {
            $('#validationModal').modal('show');
            $('h4').text('Erreur');
            $('#validationTextModal').text('Veuillez ajouter au moins un produit pour l\'arrivage');
        }
    });

//---APPARITION MODAL PRODUIT-------------------------------------------------------------------------------------

    $("#ajoutProduit").click(function(){
        $('h4').text('Ajout d\'un produit');
        $('#validerProduitModal').text("Ajouter");
    });

    $("#modifProduit").click(function(){
        $('h4').text('Modification d\'un produit');
        $('#validerProduitModal').text("Modifier");

        i = 0;
        while (i < jObjProduitGroupe[0].length && jObjProduitGroupe[0][i].TPR_ID != $("#nomProduit option:selected" ).val())
        {
            i++;
        }

        $('#nomProduitModal').val(jObjProduitGroupe[0][i].TPR_Libelle);
        $('#PUProduitModal').val(jObjProduitGroupe[0][i].TPR_Prix);
        $('#P1Produit').val(jObjProduitGroupe[0][i].P1);
        $('#P2Produit').val(jObjProduitGroupe[0][i].P2);
        $('#P3Produit').val(jObjProduitGroupe[0][i].P3);
        $('#PMoisson').val(jObjProduitGroupe[0][i].TPR_PourcentMoisson);
        $('#nbJourAlerte').val(jObjProduitGroupe[0][i].TPR_NbJourAlerte);

    });

//---APPARITION MODAL GROUPE-------------------------------------------------------------------------------------

    $('#ajoutGroupe').click(function() {
        $('h4').text('Ajout d\'un groupe');
        $('#validerGroupeModal').text("Ajouter");
    });

    $('#modifGroupe').click(function() {
        $('h4').text('Modification d\'un groupe');
        $('#validerGroupeModal').text("Modifier");

        i = 0;
        while (i < jObjProduitGroupe[1].length && jObjProduitGroupe[1][i].GRO_ID != $("#listeGroupe option:selected" ).val()) 
        {
            i++;
        }

        $('#nomGroupe').val(jObjProduitGroupe[1][i].GRO_LIBELLE);

        $("#Priorite").prop('selectedIndex', jObjProduitGroupe[1][i].PRI_ID - 1);
    });

//---VALIDATION MODAL-------------------------------------------------------------------------------------------

    $('#produitModalForm').submit(function(event) {
        event.preventDefault();

        if($('#validerProduitModal').text() == 'Ajouter')
        {
            Priorite = [$('#P1Produit').val(),$('#P2Produit').val(),$('#P3Produit').val()];

            $.post(
            'php/setProduit.php', 
            {
                Libelle : $('#nomProduitModal').val(),
                Prix : $('#PUProduitModal').val(),
                Prio : JSON.stringify(Priorite),
                PMoisson : $('#PMoisson').val(),
                nbJourAlerte : $('#nbJourAlerte').val()
            },
            function(data){ 
                jObjProduitGroupe[0] = JSON.parse(data);
                remplirListeProduit();
            },
            'text');
        }
        else
        {
            index = $('#nomProduit').prop('selectedIndex');

            Priorite = [$('#P1Produit').val(),$('#P2Produit').val(),$('#P3Produit').val()];

            i = 0;
            while (i < jObjProduitGroupe[0].length && jObjProduitGroupe[0][i].TPR_Libelle != $('#nomProduit option:selected').text()) 
            {
                i++;
            }

            IDProduit = jObjProduitGroupe[0][i].TPR_ID;
        
            $.post(
            'php/setProduit.php', 
            {
                ID : IDProduit,
                Libelle : $('#nomProduitModal').val(),
                Prix : $('#PUProduitModal').val(),
                Prio : JSON.stringify(Priorite),
                PMoisson : $('#PMoisson').val(),
                nbJourAlerte : $('#nbJourAlerte').val()
            },
            function(data){ 
                jObjProduitGroupe[0] = JSON.parse(data);
                remplirListeProduit();
                $("#nomProduit").prop('selectedIndex',index);
                calculPrix();
            },
            'text');
        }
        $('#produitModal').modal('hide');
    });

    $('#groupeModalForm').submit(function(event) {
    {
        event.preventDefault();

        if($('#validerGroupeModal').text() == 'Ajouter')
        {
            $.post(
            'php/setGroupe.php', 
            {
                nomGroupe : $('#nomGroupe').val()
            },
            function(data){
                jObjProduitGroupe[1] = JSON.parse(data);
                remplirListeGroupe();
                $('#nomGroupe').prop('selectedIndex',index);
            },
            'text');
        }
        else
        {
            index = $('#listeGroupe').prop('selectedIndex');

            i = 0;
            while (i < jObjProduitGroupe[1].length && jObjProduitGroupe[1][i].GRO_LIBELLE != $('#listeGroupe option:selected').text()) {
                i++;
            }

            IDGroupe = jObjProduitGroupe[1][i].GRO_ID;
            
            $.post(
                'php/setGroupe.php', 
                {
                    ID : IDGroupe,
                    nomGroupe : $('#nomGroupe').val()
                },
                function(data){
                    jObjProduitGroupe[1] = JSON.parse(data);
                    remplirListeGroupe();
                    $('#listeGroupe').prop('selectedIndex',index);
                },
                'text');
        }

        $('#groupeModal').modal('hide');
    }   
    });

    $('#produitModal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    });

    $('#groupeModal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    });
    
});