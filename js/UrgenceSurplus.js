$(document).ready(function () {

    //Permet d'ajouter une distribution d'urgence ou de surplus de denrées

    var now = new Date();
    var today = now.getFullYear()  + '-' + ('0' + (now.getMonth()+1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);
    
    $('#Date').val(today);

    $('#UrgenceSurplusForm').submit(function(event)
    {
        event.preventDefault();

        $.post(
            'php/setUrgenceSurplus.php', 
            {
                type : $("#typeDistrib option:selected").val(),
                date : $("#Date").val(),
                poids : $("#Poids").val()
            },

            function(data){ 

            },
            'text'
        );

        $('#validationModal').modal('show');
        $('h4').text('Succcès');
        $('#validationTextModal').text('La distribution a bien été ajoutée');

        $("#UrgenceSurplusForm")[0].reset();
        $('#Date').val(today);
    });
});