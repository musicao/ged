 
$(document).ready(function(){
    $('#cpf').inputmask("999.999.999-99");
    
      $('#telefone').inputmask('(99) 9999[9]-9999');
     
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });
    
    $(function () {
            $('#selEstado').on('change', function () {
                var num = this.value;
                var serviceBase = window.location.origin + '/ged/assets/js/app/api/v1';
                 
                $.ajax({
                    type: 'GET',
                    url: serviceBase + '/cidades',
                    data: {estado: num, respostaAjax: true},
                    dataType: 'json',
                    beforeSend: function () {
                        $('#load').show();
                    },
                    success: function (data) {
                        $('#load').hide();
                        
                        if (data) {
                            $('#selCidade').empty();
                            for (var prop in data) {
                                $('#selCidade').append(
                                        $('<option></option>').val(data[prop].id).html(data[prop].nome));
                            }
                        }
                    },
                    error: function (data) {
                        $('#load').hide();
                    }
                });

            });

        }); 

     
});

 