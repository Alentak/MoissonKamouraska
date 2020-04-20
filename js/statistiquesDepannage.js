$(document).ready(function () {

    poids = 0;

    prixMoyen = 3.03;

//---FONCTION AFFICHAGE DU TABLEAU-------------------------------------------------------------------------------------------- 

    function affichageTableau(poids){
        $('#tabHistorique tbody').empty();
        
        $('#tabHistorique').append('<tr><td>'+ poids +'</td><td><input type="number" id="prixMoyen" class="form-control" value="' + prixMoyen + '"></td><td></td></tr>');
        $('tbody td:eq(2)').text(Math.round(poids*$('#prixMoyen').val()*100)/100);
        $('span').text("Dépannage en " + $('#yearpicker  option:selected').text());
    }

//---

    $("body").on('input','input[id="prixMoyen"]',function() {
        $('tbody td:eq(2)').text(Math.round(poids*$('#prixMoyen').val()*100)/100);
    });


//---PERMET D'ALIMENTER LA LISTE DE SELECTION DES ANNEES-------------------------------------------------------------------------------------------- 

    now = new Date();

    if (now.getMonth()+1>3) {
        $('#yearpicker').append($('<option />').val(now.getFullYear()).html(now.getFullYear()));
    }
    for (i = now.getFullYear()-1; i > 1990; i--)
    {
        $('#yearpicker').append($('<option />').val(i).html(i));
    }

//---JSON-------------------------------------------------------------------------------------------- 

    $.post(
        'php/getStatistiquesDepannage.php', 
        {
            Annee : $('#yearpicker option:selected').text()
        },
        function(data){
            poids = parseInt(data);
            affichageTableau(poids);
        },
        'text');
    
//---PERMET DE GENERER TABLEAU EN FONCTION DE l'ANNEE SELECTIONNEE-------------------------------------------------------------------------------------------- 

    $("#yearpicker").on('change', function() {
        $.post(
            'php/getStatistiquesDepannage.php', 
            {
                Annee : $('#yearpicker option:selected').val()
            },
            function(data){
                poids = parseInt(data);
                affichageTableau(poids);
            },
            'text');
      });


//---EXPORT CSV--------------------------------------------------------------------------------------------  

    $("#btnExportTab").click(function () {
        var CSV = 'Poids;Prix;Moyen \r\n';    
        
        var row = "";
        
        row += $('tbody td:eq(0)').text() + ';';
        row += $('#prixMoyen').val() + ';';
        row += $('tbody td:eq(2)').text() + ';';

        CSV += row.slice(0, row.length - 1) + '\r\n';
            
        var fileName = "ExportTableauDepannage";
        var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);

        var link = document.createElement("a");    
        link.href = uri;

        link.style = "visibility:hidden";
        link.download = fileName + ".csv";

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});