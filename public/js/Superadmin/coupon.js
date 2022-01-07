
function deletecoupon(thisval){
	var deleteid    =   $(thisval).attr('data-delete-id');
	if(confirm('Are You Sure You want delete the Coupon?')){
		var baseurl				=	$('input[name="base_url"]').val();
		var redirecturl			=	baseurl+'/delete-coupon/'+deleteid;
		window.location.href	=	redirecturl;
	}
}
  $(document).ready(function(){
   
    $(".amount_type").change(function(){
        var amounttype = $(this).val();
        if(amounttype=='price'){
            $('.price_col').removeClass('content_hide');
            $('.discount_col').addClass('content_hide');
        }else if(amounttype=='discount'){
            $('.price_col').addClass('content_hide');
            $('.discount_col').removeClass('content_hide');
        }
    });

		var amounttype = $('.amount_type').val();
   		  if(amounttype=='price'){
            $('.price_col').removeClass('content_hide');
            $('.discount_col').addClass('content_hide');
        }else if(amounttype=='discount'){
            $('.price_col').addClass('content_hide');
            $('.discount_col').removeClass('content_hide');
        }
            var startDate = new Date();
            $('.start_datepicker').datepicker({  
            startDate: new Date(),
            changeMonth: true,
            autoclose: true,
            format: 'mm/dd/yyyy'
            }).on('changeDate', function (selected) {
                startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $('.end_datepicker').datepicker('setStartDate', startDate);
            });
            $('.end_datepicker').datepicker({ 
                    startDate: startDate,
                    changeMonth: true,
                    autoclose: true,
                    format: 'mm/dd/yyyy'
            });



  });
  
  $('.export_btn').on('click',function(){  
    $('#export_val').val('1');
  }) 

  