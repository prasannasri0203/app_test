$(document).ready(function(){
    $(".role_type").change(function(){
        var roletype = $( ".role_type option:selected" ).text();
        if(roletype=='Trial'){
           $('input[name="plan_type"]').val('Free');
           $('.trail_col').addClass('content_hide');
           $('.paid_col').removeClass('content_hide');
        }else{
            $('input[name="plan_type"]').val('Paid');
            $('.trail_col').removeClass('content_hide');
            $('.paid_col').addClass('content_hide');
        }
    });

    var roletype = $( ".role_type option:selected" ).text();
    if(roletype=='Trial'){
        $('input[name="plan_type"]').val('Free');
        $('.trail_col').addClass('content_hide');
        $('.paid_col').removeClass('content_hide');
     }else{
         $('input[name="plan_type"]').val('Paid');
         $('.trail_col').removeClass('content_hide');
         $('.paid_col').addClass('content_hide');
     }
    
    $(".subscription_filter").click(function(){
        var planname =  $('input[name="filter_plan_name"]').val();
        var plantype =  $('select[name="filter_plan_type"]').children("option:selected").val();
        var status   =  $('select[name="filter_status"]').children("option:selected").val();
        var basedon  =  $('select[name="filter_based_on"]').children("option:selected").val();
        var roletype =  $('select[name="roletype"]').children("option:selected").val();
        var url      =  $('input[name="base_url"]').val();

        window.location.href = url+'/super-subscription-plan?planname='+planname+'&plantype='+plantype+'&status='+status+'&roletype='+roletype;
    });


    // $('.pagination_pre_btn').click(function(){
    //     var baseurl	 =	$('input[name="base_url"]').val();
    //     var url      = window.location.href;
    //     var page     = url.split('?');
    //     var exist    = page[1].includes('page');
    //     if(exist==true){
    //         var pagenumber  =   page[1].split('=');
    //         if(pagenumber[1]>1){
    //             var reqpage =  parseInt(pagenumber[1])-parseInt(1);
    //             window.location.href = baseurl+'/super-subscription-plan?page='+reqpage;
    //         }else{
    //             window.location.href = baseurl+'/super-subscription-plan';
    //         } 
    //     }
    // });
});


    function deletesubscription(thisval){
        var deleteid    =   $(thisval).attr('data-delete-id');
        if(confirm('Are You Sure You want delete the Subscription Plan?')){
			var baseurl				=	$('input[name="base_url"]').val();
			var redirecturl			=	baseurl+'/deletesubscription/'+deleteid;
			window.location.href	=	redirecturl;
		}
    } 
      $(".textarea_stl")
    .bind("dragover", false)
    .bind("dragenter", false)
    .bind("drop", function(e) {
        this.value = e.originalEvent.dataTransfer.getData("text") ||
            e.originalEvent.dataTransfer.getData("text/plain");
        
        $("span").append("dropped!");

    return false;
}); 