$(document).ready(function () {
    jObjGroupe = [];

    jObjHistorique = [];

    tableauChart = [];

    var chart;

//---FONCTION POUR AFFICHER L'AXE DES ORDONNEES A LA BONNE ECHELLE----------------------------------------------------------------------------------------

function getMax(arr, prop) {
    var max;
    for (var i=0 ; i<arr.length ; i++) {
        if (!max || parseInt(arr[i][prop]) > parseInt(max[prop]))
            max = arr[i];
    }
    return max;
}

//---FONCTION AFFICHAGE DU TABLEAU-------------------------------------------------------------------------------------------- 

function affichage(p_Json){
    poidstotal = 0;
    prixtotal = 0;
    $('#tabHistorique tbody').empty();
    if (p_Json.length<1) {
        $('#tabHistorique').append('<tr><td></td><td>Aucune données</td><td></td><td></td></tr>');
    }
    else{
        for (let index = 0; index < p_Json.length; index++) {
            $('#tabHistorique').append('<tr><td>'+p_Json[index].tpr_libelle+'</td><td>'+p_Json[index].nb+'</td><td>'+p_Json[index].Poids+'</td><td>'+Math.round(p_Json[index].prix*100)/100 +'</td></tr>')
            poidstotal += parseFloat(p_Json[index].Poids,10)*parseFloat(p_Json[index].nb,10);
            prixtotal += parseFloat(p_Json[index].prix,10);
        }
        $('#groupeSelect').text("Groupe : "+$('#optionsGroupe  option:selected').text());
        $('#dateSelect').text("Date : "+$('#optionsDate').val());
        $('#btnExportTab').css({"display" : "block"});
    }

    $('#pdsTotal').text("Poids total : "+ Math.round(poidstotal*100)/100);
    $('#prixTotal').text("Prix total : "+Math.round(prixtotal*100)/100);
}

//---FONCTION AFFICHAGE DU GRAPHIQUE-------------------------------------------------------------------------------------------- 

function dessinnerGraph(){
    chart = new CanvasJS.Chart("chartContainer",{
        animationEnabled: true,
        title: {
            text: "Denrées reçues "+$('#optionsGroupe  option:selected').text()    
        },
        axisX:{
            interval:1,      
            labelAngle: -40
          },
        axisY:{
            viewportMaximum: getMax(tableauChart,"y").y * 0.1 + parseFloat(getMax(tableauChart,"y").y)
        },
        dataPointWidth: 20,
        data: [              
        {
            type: "column",
            dataPoints: tableauChart
        }
        ]
    })

    chart.render();
}

//---BOUTON IMPRESSION DU GRAPHIQUE----------------------------------------------------------------------------------------

$('#printChart').click(function () {
    chart.print();
})

//---JSON----------------------------------------------------------------------------------------

    $.ajax({
        url: 'php/getGroupe.php',
        success: function(data){ 
            jObjGroupe = JSON.parse(data);

            for (let i = 0; i < jObjGroupe.length; i++) 
            {
                $('#optionsGroupe').append('<option value="' + jObjGroupe[i].GRO_ID + '">' + jObjGroupe[i].GRO_LIBELLE + '</option>');
            }
        },
        async: false
    });

//---AFFICHAGE DU MODAL----------------------------------------------------------------------------------------

    $('#optionsModal').modal('show');

//---VALIDATION OPTIONS-------------------------------------------------------------------------------------------- 

    $('#optionsForm').submit(function (e) { 
        e.preventDefault();

        tableauChart = [];

        $.post(
            'php/getStatistiquesArrivage.php', 
            {
                GRO_ID : $('#optionsGroupe option:selected').val(),
                LOT_Date : $('#optionsDate').val()
            },
            function(data){
                jObjHistorique = JSON.parse(data);
                for (let index = 0; index < jObjHistorique[1].length; index++) {
                    tableauChart.push({label: jObjHistorique[1][index].Date, y: parseFloat(jObjHistorique[1][index].Poids), indexLabel: "{y}"});
                }
                affichage(jObjHistorique[0]);
                dessinnerGraph();
                $('#optionsModal').modal('hide');
                $("#optionsForm")[0].reset();
                $('#printChart').css({"display" : "block"});
                $('#btnExportChart').css({"display" : "block"});
            },
            'text');
    });

//---EXPORT CSV-------------------------------------------------------------------------------------------- 

    $("#btnExportChart").click(function () {
        var CSV = 'Poids;Mois \r\n';    
        
        for (var i = 0; i < tableauChart.length; i++) {
            var row = "";
            
            row += jObjHistorique[1][i].Date + ';' + jObjHistorique[1][i].Poids;

            CSV += row + '\r\n';
        }

        var fileName = "ExportGraphiqueArrivage";
        var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);

        var link = document.createElement("a");    
        link.href = uri;

        link.style = "visibility:hidden";
        link.download = fileName + ".csv";

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    $("#btnExportTab").click(function () {
        var CSV = 'Type de produit;Nombre;Poids;Prix \r\n';    
        
        for (var i = 0; i < jObjHistorique[0].length; i++) {
            var row = "";
            
            row = jObjHistorique[0][i].tpr_libelle + ";" + jObjHistorique[0][i].HIS_NbProduit + ";" + jObjHistorique[0][i].HIS_PoidsUnitaire + ";" + Math.round(jObjHistorique[0][i].prix*100)/100;

            CSV += row + '\r\n';
        }

        var fileName = "ExportTableauArrivage";
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