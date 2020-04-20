$(document).ready(function () {

    jObjGroupe = [];

    jObjHistorique = [];

    var chart;

    tableauChart = [];

//---FONCTION PARAMETRES DU GRAPHIQUE ET CREATION-------------------------------------------------------------------------------------------- 

function dessinnerGraph(p_tableau){
    chart = new CanvasJS.Chart("chartContainer",{
        animationEnabled: true,
        title: {
            text: ""   
        },
        legend:{
            horizontalAlign: "right",
            verticalAlign: "center",
            fontSize: 20
        },
        data: [              
        {
            type: "pie",
            showInLegend: true,
            indexLabelFontSize: 18,
            toolTipContent: "{name}: <strong>{y} %</strong>",
            indexLabel: "{y} %",
             
            dataPoints: p_tableau
        }
        ]
    })
    chart.render();
}

//---FONCTION AFFICHAGE DU GRAPHIQUE-------------------------------------------------------------------------------------------- 

    function affichageChartMRC(){
        if (jObjHistorique.length!=0) {
            tableauChart = [];
            for (let index = 0; index < jObjHistorique.length; index++) {
                tableauChart.push({ y: parseFloat($('#tabHistorique tbody tr:eq('+index+') td:eq(3)').text()), name: jObjHistorique[index][0].MRC_Libelle  })
            }
            dessinnerGraph(tableauChart);
        }
    }

//---FONCTION AFFICHAGE DU TABLEAU-------------------------------------------------------------------------------------------- 

    function affichageTableau(p_Json){
        if (p_Json.length==0) {
            $('#printChart').css({"display" : "none"});
            $('#btnExportChart').css({"display" : "none"});
            $('#btnExportTab').css({"display" : "none"});
        }
        else
        {
            $('#printChart').css({"display" : "block"});
            $('#btnExportChart').css({"display" : "block"});
            $('#btnExportTab').css({"display" : "block"});
        }
        $('#tabHistorique tbody').empty();
        PoidsTotal = 0;
        PrixTotal = 0;
        PourcentageTotal=0;
        for (let index = 0; index < p_Json.length; index++) {
            $('#tabHistorique').append('<tr etat="repli" name="trclick"><td>'+p_Json[index][0].MRC_Libelle+'</td><td>'+p_Json[index][0].PoidsTotal+'</td><td>'+p_Json[index][0].PrixTotal+'</td><td></td></tr>');
            PoidsTotal += parseFloat(p_Json[index][0].PoidsTotal,10);
            PrixTotal += parseFloat(p_Json[index][0].PrixTotal,10);
        }

        for (let index = 0; index < p_Json.length; index++) {
            $('#tabHistorique tbody tr:eq('+index+') td:eq(3)').text(Math.round(p_Json[index][0].PoidsTotal*100/PoidsTotal*100)/100);
            PourcentageTotal += Math.round(p_Json[index][0].PoidsTotal*100/PoidsTotal*100)/100;
        }

        $('#tabHistorique').append('<tr class="total"><td>Grand total</td><td>'+ Math.round(PoidsTotal * 100) / 100 +'</td><td>'+ Math.round(PrixTotal * 100) / 100 +'</td><td>'+Math.round(PourcentageTotal*10)/10+'</td></tr>');

        $('span').text("Denrées distribuées en " + $('#yearpicker  option:selected').text());
    }

//---PERMET DE RECUPERER LES LIGNES SECONDAIRES DEPUIS LE JSON--------------------------------------------------------------------------------------------

    function getLignes(p_libelle){
        i=0;
        while (i < jObjHistorique.length && jObjHistorique[i][0].MRC_Libelle != p_libelle) {
            i++;
        }
        arrayMrc = jObjHistorique[i];
        return arrayMrc;
    }

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
        'php/getStatistiquesDistribution.php', 
        {
            Annee : $('#yearpicker option:selected').text()
        },
        function(data){
            jObjHistorique = JSON.parse(data);
            affichageTableau(jObjHistorique);
            affichageChartMRC();
        },
        'text');

    $('#printChart').click(function () {
        chart.title.set("text", "Denrées distribuées en " + $('#yearpicker  option:selected').text());
        chart.print();
        chart.title.set("text", "");
    })
    
//---PERMET DE GENERER TABLEAU ET GRAPHIQUE EN FONCTION DE l'ANNEE SELECTIONNEE-------------------------------------------------------------------------------------------- 

    $("#yearpicker").on('change', function() {
        $.post(
            'php/getStatistiquesDistribution.php', 
            {
                Annee : $('#yearpicker option:selected').val()
            },
            function(data){
                jObjHistorique = JSON.parse(data);
                affichageTableau(jObjHistorique);
                chart.destroy();
                affichageChartMRC();
            },
            'text');
      });

//---PERMET DE GENERER LES LIGNES SECONDAIRES-------------------------------------------------------------------------------------------- 

      $("body").on( "click","#tb tr[name='trclick']", function(){
        value = $(this).find('td:eq(0)').text();
        var lignes;
        lignes = getLignes(value);
        $('tr[name="test"]').remove();

        if ($(this).attr("etat") == "repli") { 
            PoidsTotal = 0;
            PrixTotal = 0;
            for (let index = lignes.length-1; index > 0 ; index--) {
                $('<tr class="table-borderless" name="test"><td>'+lignes[index].GRO_LIBELLE+'</td><td>'+lignes[index].PoidsTotal+'</td><td>'+lignes[index].PrixTotal+'</td><td></td></tr>').insertAfter($(this).closest('tr'));
                PoidsTotal += parseFloat(lignes[index].PoidsTotal,10);
                PrixTotal += parseFloat(lignes[index].PoidsTotal,10);
            }
            $('tbody tr').attr("etat","repli");
            $(this).attr("etat","depli");

            tableauChart = [];
            for (let index = 1; index < lignes.length; index++) {
                tableauChart.push({ y: Math.round(lignes[index].PoidsTotal*100/PoidsTotal*100)/100, name: lignes[index].GRO_LIBELLE  })
            }
            dessinnerGraph(tableauChart);
        }
        else
        {
            $('tbody tr').attr("etat","repli");
            affichageChartMRC();
        }
    });

//---EXPORT CSV--------------------------------------------------------------------------------------------  
    
    $("#btnExportChart").click(function () {
        var CSV = 'MRC;Pourcentage \r\n';    
        
        for (var i = 0; i < tableauChart.length; i++) {
            var row = "";
            
            row += tableauChart[i].name + ';' + tableauChart[i].y;

            CSV += row + '\r\n';
        }

        var fileName = "ExportGraphiqueDistribution";
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
        var CSV = 'Groupe;Poids;Prix \r\n';    
        
        for (var i = 0; i < jObjHistorique.length; i++) {
            
            for (let index = 0; index < jObjHistorique[i].length; index++) {
                
                var row = "";

                for (var j in jObjHistorique[i][index]) {
                    row += jObjHistorique[i][index][j]+ ';';
                }
                
                CSV += row.slice(0, row.length - 1) + '\r\n';
            }
        }

        var fileName = "ExportTableauDistribution";
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