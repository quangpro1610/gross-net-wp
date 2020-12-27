jQuery(document).ready(function($) {
    if ($('#salary-form').length) {
        $('#salary-form input[name="salary_option"]').change(function(e) {
            //e.preventDefault();
            var data_1 = $(this).parent().data('1'),
                data_2 = $(this).parent().data('2'),
                data_3 = $(this).parent().data('3');
            $('.top-2 .data-1').text(data_1);
            $('.top-2 .data-2').text(data_2);
            $('.top-2 .data-3').text(data_3);
        });

        $('#salary-form input[name="dong_bao_hiem"]').change(function(e) {
            var dong_bao_hiem_value = $(this).val();
            if (dong_bao_hiem_value == '2') {
                $('#dong_bh_khac').prop('disabled', false);
            } else {
                $('#dong_bh_khac').prop('disabled', true);
                $('#dong_bh_khac').val('');
            }
        });

        $('#salary-form input[type="text"]').simpleMoneyFormat();

        $('#salary-form input[type="submit"]').click(function(e) {
            e.preventDefault();
            var this_v = $(this).attr('name');
            if(this_v == 'net_groos'){
            	$('input[name="my_type"]').val('net2gross');
            }else{
            	$('input[name="my_type"]').val('groos2net');
            }
            $('#salary-form').trigger('submit');
        }); 
        $('#salary-form').submit(function(e) {
            e.preventDefault();
            var flag = false;
            if($('#income').val() == '') {
            	$('#income').parent().addClass('has-error');
            	$('#income').parent().parent().append('<p class="alert alert-danger" style="margin-top: 10px;padding: 5px 10px;">Vui lòng nhập tiền lương</p>');
            	setTimeout(function(){
            		$('.alert').remove();
            	}, 3000);
            } else {
            	flag = true;
            	$('#income').parent().removeClass('has-error');
            }
            if(!$('#dong_bh_khac').is(':disabled') && $('#dong_bh_khac').val() == ''){
            	$('#dong_bh_khac').parent().addClass('has-error');
            	$('#dong_bh_khac').parent().parent().append('<p class="alert alert-danger" style="margin-top: 10px;padding: 5px 10px;">Vui lòng nhập tiền đóng bảo hiểm</p>');
            	setTimeout(function(){
            		$('.alert').remove();
            	}, 3000);
            }else{
            	flag = true;
            	$('#dong_bh_khac').parent().removeClass('has-error');
            }
            if(flag == true){
            	var data_form = $(this).serialize();
                $.ajax({
                    url: ajax_obj.ajax_url,
                    type: 'POST',
                    dataType: 'json',
                    data: data_form,
                    beforeSend: function() {}
                })
                .done(function(response) {
                    $('.table-1').html(response.table_1);
                    $('.table-2').html(response.table_2);
                    $('.table-3').html(response.table_3);
                    $('.table-4').html(response.table_4);
                    $('.table-1>tbody>tr:nth-of-type(2)>td, .table-2>tbody>tr>td, .table-3>tbody>tr>td:nth-of-type(3), .table-4>tbody>tr>td').simpleMoneyFormat();
                })
                .fail(function(response) {
                    console.log(response.messages);
                });
            }
        });
    }
});
