function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
$('.card-body.card-c').click(function(){
    $('.button_section-c').show();
    var id = $(this).attr('data-id');
    var db_user_plan_id = $('#db_user_plan_id').val();
    var db_user_role_id = $('#db_user_role_id').val();
    var planname = $('#planname_'+id).val();
    var team_count          =   $('#team_count').val();
    var user_plan_type = $('#user_plan_type').val();
    if($(this).hasClass('mb-ic')) {
        //deselect
        $('#user_plan_id').val('0');
        $('#user_role_id').val('0');
        $('.submit_plan').html('Continue with the plan');
        $('.submit_plan').show();
    }else{
        if(user_plan_type == 'register'){
            $('#user_plan_id').val(id);
            var role = $('#role_'+id).val();
            $('#user_role_id').val(role);
            $('#user_status').val('1');
            if(role == '4'){
                $('#myModal').show();
            }
            $('.submit_plan').show();
            $('.submit_plan').html('Continue with the '+planname+' plan');
        }else{
           if((id != db_user_plan_id && id != 0) || (db_user_role_id != 4 && id == 0)){
            //selected
                $('#user_plan_id').val(id);
                var role = $('#role_'+id).val();
                $('#user_role_id').val(role);
                $('#user_status').val('1');
                if(role == '4'){
                    $('#myModal').show();
                }
                $('.submit_plan').show();
                $('.submit_plan').html('Continue with the '+planname+' plan');
            }else{
                $('#user_plan_id').val('0');
                $('#user_role_id').val('0');
                $('.submit_plan').hide();
            }
        }
    }
});
$('.ok_btn').click(function(){
    var team_count =$('#team_count').val();
    var user_role_id =$('#user_role_id').val();
    if(user_role_id == '4' && team_count == '0'){
        alert('Please enter no. of teams!');
        $('#team_count').focus();
        return false;
    }else{
        $('#myModal').hide();
    }
});
$('.cancel_btn').click(function(){
    $('#team_count').val(0);
    $('#myModal').hide();
});

$('.submit_plan').click(function(){
    var user_id             =   $('#user_id').val();
    var user_plan_id        =   $('#user_plan_id').val();
    var user_role_id        =   $('#user_role_id').val();
    var team_count          =   $('#team_count').val();
    var baseurl             =   $('#base_url').val();
    var user_status         =   $('#user_status').val();
    var db_user_role_id = $('#db_user_role_id').val();
    var user_plan_type = $('#user_plan_type').val();
    // if(user_plan_type == 'register'){
    //     var planuser = 1;
    // }else{
        if(user_role_id == '4'){//Enterpriser
            var planuser = 1;
        }else{
            var planuser = 2;
        }
    // }
    if(user_role_id == 2){
        alert('Select any subscription plan which is suitable to you!');
        return false;
    }else{
        if(user_role_id == '4' && team_count == '0'){
            alert('Please enter no. of teams!');
            $('#myModal').show();
            $('#team_count').focus();
            return false;
        }
        if(user_role_id == '1' || user_role_id == '3'){
            $('.loader').html('Payment is processing...');
        }else{
            $('.loader').html('Processing...');
        }
        $('#divLoading').show();
        $.ajax({
            url: baseurl+'/updateplan-values', 
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
            data: { 
                user_id: user_id, 
                user_plan_id : user_plan_id,
                user_role_id:user_role_id,
                team_count:team_count,
                planuser:planuser
            } , 
            success: function(result){
                $('#divLoading').hide();
                if(result=='Not Updated'){
                    alert('Something went wrong!');
                    return false;
                    // window.location.href = baseurl+'/plan-setting';
                }else{
                    if(user_role_id == '4' && db_user_role_id == '4'){
                        window.location.href = baseurl+'/plan-setting/1';
                    }else if(user_role_id == '4' && db_user_role_id != '4'){
                        //alert('You have changed plan as enterpriser successfully. Once admin approved your request,you can login again');
                        window.location.href = baseurl+'/user-logout/3';
                    }else{
                        window.location.href = baseurl+'/user-plan-preview/'+user_id;
                    }
                }
            }
        });
    }
});
var taxvals =[];
var taxvals_name =[]; 
$('.coupon_apply').click(function(){
    var coupon_code = $('.coupon_code').val();
    var user_id =$('#user_id').val();
    var user_plan_id =$('#user_plan_id').val();
    var baseurl = $('#base_url').val();
    var total_amt_with_tax = $('#total_amt_with_tax').val(); 
    var total_amt = $('#total_amt').val();
    $('.remove_coupon').hide();
    $('#coupon_err').html('');
    $('#coupon_amt_value').val(0);
    $('#coupon_type').val(0);
    $('#percent_val').hide();
    if(coupon_code == ''){
        $('#coupon_err').html('Enter the coupon code!');
        $('.coupon_code').focus();
        return false;
    }else{
        $.ajax({
            url: baseurl+'/user-coupon-apply', 
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
            data: { 
                code: coupon_code, 
                user_plan_id : user_plan_id,
                user_id:user_id,
                total_amt:total_amt
            } , 
            success: function(response){                
                if(response['status'] == '1'){
                    var total_amount = response['total_amt'];
                    $('.coupon_amt').html('CAD '+response['coupon_amt']);
                    $('#coupon_amt_value').val(response['coupon_amt']);
                    $('.total_cad').html('CAD '+total_amount);
                    $('#coupon_id').val(response['coupon_id']);
                    $('#total_amt').val(total_amount);
                    if(response['type'] == 'discount'){
                        $('#percent_val').show();
                        $('#percent_val').html('('+response['discount']+'%)');
                    }                   
                    $('.remove_coupon').show();
                    $('#coupon_type').val(response['type']);                    
                    addTaxAmt(taxvals,taxvals_name);
                }else if(response['status'] == '2' ||  response['status'] == '3'){
                    if(response['status'] == '2'){
                        $('#coupon_err').html('Coupon code has been expired!');
                    }else{
                        $('#coupon_err').html('You can not use this coupon code now!');
                    }
                    $('#percent_val').html('');
                    $('.coupon_amt').html('CAD 0.00');
                    $('#total_amt').val(response['plan_amt']);
                    $('.total_cad').html('CAD '+response['plan_amt']);
                    $('#coupon_id').val('0');
                    $('.coupon_code').val('');
                    $('.coupon_code').focus();
                    addTaxAmt(taxvals,taxvals_name);                    
                    return false;
                }else if(response['status'] == '4'){
                    $('#coupon_err').html('You can not use this coupon code again!');
                    $('#percent_val').html('');
                    $('.coupon_amt').html('CAD 0.00');
                    $('#total_amt').val(response['plan_amt']);
                    $('.total_cad').html('CAD '+response['plan_amt']);
                    $('#coupon_id').val('0');
                    $('.coupon_code').val('');
                    $('.coupon_code').focus();
                    addTaxAmt(taxvals,taxvals_name);
                    return false;
                }else if(response['status'] == '5'){
                    $('#coupon_err').html('Coupon amount is greater than your plan amount!');
                    $('#percent_val').html('');
                    $('.coupon_amt').html('CAD 0.00');
                    $('#total_amt').val(response['plan_amt']);
                    $('.total_cad').html('CAD '+response['plan_amt']);
                    $('#coupon_id').val('0');
                    $('.coupon_code').val('');
                    $('.coupon_code').focus();
                    addTaxAmt(taxvals,taxvals_name);
                    return false;
                }else{
                    $('#coupon_err').html('Invalid coupon code!');
                    $('#percent_val').html('');
                    $('.coupon_amt').html('CAD 0.00');
                    $('#total_amt').val(response['plan_amt']);
                    $('.total_cad').html('CAD '+response['plan_amt']);
                    $('#coupon_id').val('0');
                    $('.coupon_code').val('');
                    $('.coupon_code').focus();
                    addTaxAmt(taxvals,taxvals_name);
                    return false;
                }
            }
        })
    }
})
$('.remove_coupon').click(function(){
    $('#coupon_type').val(0);
    $('.remove_coupon').hide();
    var total_amt =$('#plan_amount').val();
    var coupon_amt_value = $('coupon_amt_value').val();
    $('#coupon_amt_value').val(0);
    $('#percent_val').html('');
    $('.coupon_amt').html('CAD 0.00');
    $('#total_amt').val(total_amt);
    $('.total_cad').html('CAD '+total_amt);
    $('#coupon_id').val('0');
    $('.coupon_code').val('');
    $('.coupon_code').focus();
    addTaxAmt(taxvals,taxvals_name);
    return false;
});
$('.stripesubmit').click(function(){       
    //payemnt details  
    var fname =$('.firstname').val();
    var lname =$('.lastname').val();
    var name = fname+''+lname;
    var line1 =$('.line1').val();
    var city =$('.city').val();
    var state =$('.state').val();
    var country =$('.country').val();
    var postal_code =$('.postal_code').val();
    var cardno =$('.card-number').val();
    var cvv =$('.card-cvc').val();
    var expirymon =$('.card-expiry-month').val();
    var expiryyr =$('.card-expiry-year').val();
    var total_amt =$('#total_amt').val();
    //alert(total_amt);
    if(name == '' || line1 == '' || city == '' || state == '' || country == '' || postal_code == '' || cardno == '' || cvv == '' || expirymon == '' || expiryyr == ''){
        $('.error').show();
        $('.error').find('.alert').text('Please make sure you have entered all details in Billing Information and Card Details');
        return false;
    }else{
        $('.loader').html('Payment is processing...');
        $('#divLoading').show();
        Stripe.setPublishableKey($('#stripeKey').val());
        Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
          }, stripeResponseHandler);
    }        
    
});
function stripeResponseHandler(status, response) {
    if (response.error) {
        $('#divLoading').hide();
        $('.error').show();
        $('.error').find('.alert').text(response.error.message);
    } else {
        // $('#divLoading').show();
        var baseurl = $('#base_url').val();
        var token = response['id'];
        $('#stripeToken').val(token);
        var coupon_id = $('#coupon_id').val();
        var user_id =$('#user_id').val();
        var user_plan_id =$('#user_plan_id').val();
        var plan_amt = $('#plan_amount').val();
        var total_amt =$('#total_amt').val();
        var user_role_id =$('#user_role_id').val();
        var stripetoken =$('#stripeToken').val();
        var tax_percentage = $('#tax_percent').val();
        var fname =$('.firstname').val();
        var lname =$('.lastname').val();
        var name = fname+''+lname;
        var line1 =$('.line1').val();
        var city =$('.city').val();
        var state =$('.state').val();
        var country =$('.country').val();
        var postal_code =$('.postal_code').val();
        if(stripetoken != ''){ 
            $.ajax({
                url: baseurl+'/user-payment', 
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
                data: { 
                    coupon_id: coupon_id, 
                    user_plan_id : user_plan_id,
                    user_id:user_id,
                    user_role_id : user_role_id,
                    plan_amt:plan_amt,
                    total_amt:total_amt,
                    stripetoken:stripetoken,
                    tax_percentage:tax_percentage,
                    username:name,
                    line1:line1,
                    city:city,
                    state:state,
                    country:country,
                    postal_code:postal_code,
                },
                success: function(response){  
                    $('#divLoading').hide();  
                    console.log(response);
                    if(response['status'] == '1'){
                        window.location.href = baseurl+'/plan-setting/1';
                    }else if(response['status'] == '2'){
                        if(response['msg'] != ''){
                            $('.error').show();
                            $('.error').find('.alert').text(response['msg']);
                        }
                        return false;
                    }else{
                        alert('Something went wrong!');
                        return false;
                    }
                }
            });
        }
    }
}
function shareChart(id){
    $("#chart_id").val(id);
    $('#shareuseremail').val('');
    var baseurl = $('#baseurl').val();
    $.ajax({
        url: baseurl+'/share-chart-exist', 
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
        data: { 
            chart_id:id
        },
        success: function(response){ 
            if(response != 'none'){
                var users = " user's";
                $('#shared_detail_cnt').show();
                $('#shared_detail_cnt').html('Shared with '+response['cnt']+users +'. <a href="#" onclick="shared_user_detail()"  data-toggle="modal" data-target="#shared_detail">Check to view list</a>');
            }else{
                $('#shared_detail_cnt').hide();
            }
        }
    });
    var userurl = baseurl+'/user-lists'; 
    getSubUsers(id,userurl);
}
function shared_user_detail(){
    $('#exampleModal').hide();
    $('.modal-backdrop').hide();
    var chart_id = $("#chart_id").val();
    var baseurl = $('#baseurl').val();
    $.ajax({
        url: baseurl+'/share-chart-exist', 
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
        data: { 
            chart_id:chart_id
        },
        success: function(response){ 
            if(response != 'none'){
                var shareData ='<table style="width: 100%;"><thead><tr><th>Collabrators</th><th>Status</th><th></th></tr></thead><tbody>';
                $.each(response['emails'], function (key, value) {
                    shareData+='<input type="hidden" id="key_email'+key+'" value="'+response['useremails'][key]+'"><input type="hidden" id="key_chart'+key+'" value="'+chart_id+'">';
                    if(response['dbstatus'][key] == 1){
                        var vstatus = 'Viewed';
                        var resend = '-';
                    }else{var vstatus = 'Pending';var resend = '<a href="#" onclick="resend_invitation('+key+')">Resend Invitation</a>';}
                    shareData+='<tr><td>'+value+'</td><td>'+vstatus+'</td><td>'+resend+'</td></tr>';
                });
                shareData+='</<tbody></table>';
                $('#shared_list').html('');
                $('#shared_list').append(shareData);
            }else{
                $('#shared_list').html('');
            }
        }
    });
}
function resend_invitation(key){
    var email = $('#key_email'+key).val();
    var chart_id = $('#key_chart'+key).val();
    var baseurl = $('#baseurl').val();
    $.ajax({
        url: baseurl+'/reshare-chart', 
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
        data: { 
            chart_id:chart_id,
            email:email
        },
        success: function(response){ 
            if(response == '1'){
                alert('Reshared successfully');
            }else{
                alert('Something went wrong');
            }
        }
    });
}
function statePercentage(){

    var state_id= $('.state').val();
    var total = $('#plan_amount').val();
    var coupon_amt = $('#coupon_amt_value').val();
    var baseurl = $('#baseurl').val();   
    var tot_percent_amt =0; 
    var tot_tax_percent =0;
    var stype='';
    $.ajax({
        url: baseurl+'/get-tax', 
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
        data: { 
            state_id:state_id,
        },
        success: function(response){ 
            taxvals =[];
            taxvals_name =[];
            if(response !='0'){
                $.each(response, function (key, value) {
                    if(value.gst != 0) {
                        taxvals.push(value.gst);
                        taxvals_name.push("GST");
                    }
                    if(value.pst != 0) { 
                        taxvals.push(value.pst);
                        taxvals_name.push("PST");
                    }
                    if(value.hst != 0) { 
                        taxvals.push(value.hst);
                        taxvals_name.push("HST"); 
                    }
                    if(value.qst != 0) { 
                        taxvals.push(value.qst);
                        taxvals_name.push("QST"); 
                    }
                    tot_tax_percent+=parseFloat(value.gst)+parseFloat(value.pst)+parseFloat(value.hst)+parseFloat(value.qst);                    
                });
                $('#tax_percent').val(tot_tax_percent);
                addTaxAmt(taxvals,taxvals_name);
            }else{
                $('#tax_percent').val(0);
                document.getElementById("tot-o").innerHTML = '<span id="tax_val">(0%)</span><span id="tax_percentage" class="coupon_amt1"> CAD 0.00';                    
                addTaxAmt(taxvals,taxvals_name);
            }
        }
    });
}
function addTaxAmt(taxvals,taxvals_name){
        var total = $('#plan_amount').val();
        var coupon_type =$('#coupon_type').val();
        var coupon_amt = $('#coupon_amt_value').val();
        var tax_percent = $('#tax_percent').val();
        //var percentage_amt = total*(tax_percent/100);
        var sub_total='';
        if(coupon_type == 'price'){
            //var total_val =(parseFloat(total)+parseFloat(percentage_amt)-parseFloat(coupon_amt)).toFixed(2);
            $('.coupon_amt').html('CAD '+coupon_amt); 
            sub_total=(parseFloat(total)-parseFloat(coupon_amt)).toFixed(2);
            document.getElementById("tot-percentamt").innerHTML = 'CAD '+sub_total; 
            var percentage_amt =(sub_total*tax_percent)/100;
            var total_val =(parseFloat(sub_total)+parseFloat(percentage_amt)).toFixed(2);
        }else if(coupon_type == 'discount'){
            var taxtotal = ((parseFloat(total)*parseFloat(coupon_amt))/100).toFixed(2);
            $('.coupon_amt').html('CAD '+taxtotal);  
            sub_total=(parseFloat(total)-parseFloat(taxtotal)).toFixed(2);
            document.getElementById("tot-percentamt").innerHTML = 'CAD '+sub_total;
            var percentage_amt =(sub_total*tax_percent)/100;
            var total_val =(parseFloat(sub_total)+parseFloat(percentage_amt)).toFixed(2);
        }else{
            $('.coupon_amt').html('CAD 0.00'); 
            sub_total= parseFloat(total).toFixed(2);
            document.getElementById("tot-percentamt").innerHTML = 'CAD '+parseFloat(total).toFixed(2);
            var percentage_amt =(sub_total*tax_percent)/100;
            var total_val =(parseFloat(sub_total)+parseFloat(percentage_amt)).toFixed(2);
        }     
        $('.total_cad').html('CAD '+total_val);
        $('#total_amt').val(total_val);
        $('#tot-o').empty();
        if(taxvals.length > 0){
            var state_tax='<table style="width: 100%;" class="state_tax_tbl"><tbody>';
            $.each(taxvals, function (key2, value2) {
                var tax_amt = parseFloat(value2);
                var per_amt = sub_total*(tax_amt/100);
                state_tax+='<tr><td class="tax_td"><span id="tax_val">'+taxvals_name[key2]+'('+tax_amt+'%)</span></td><td class="tax_amt_td"><span id="tax_percentage" class="coupon_amt1"> CAD ' +per_amt.toFixed(2)+'</td>';
            });
            state_tax+='</<tbody></table>';
            document.getElementById("tot-o").innerHTML=state_tax;
        }else{
            document.getElementById("tot-o").innerHTML = '<span id="tax_val">(0%)</span><span id="tax_percentage" class="coupon_amt1"> CAD 0.00';
        }
    }
$('#example11' ).tagList('create', {
 tagValidator : function( emailid ) {
    // email address
    var emailPat = /^[A-Za-z]+[A-Za-z0-9._]*@[A-Za-z0-9]+\.[A-Za-z.]*[A-Za-z]+$/;
    return emailPat.test( $.trim( emailid ) );
  }
});
$( '#example11' ).on( 'tagadd', function( $event, tagText, opStatus, message ) {
  if( opStatus === 'success' ) {
    var mails = $('#shareuseremail').val();
    var baseurl = $('#baseurl').val();
    var chart_id= $('#chart_id').val();
    var box = $('#shareuseremail');
    $.ajax({
        url: baseurl+'/check-share-chart', 
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
        data: { 
            email: tagText,
            chart_id:chart_id
        },
        success: function(response){ 
            if(response == 'exists'){
                alert('Already shared with this user,please remove this Email ID');
                return false;
            }else{
                if(mails == ''){
                    $('#shareuseremail').val(tagText);
                }else{
                    var attrbox = $('#shareuseremail').val();
                    var arr = attrbox.split(',');
                    if($.inArray( tagText, arr) > -1){
                        alert('This Email ID has been added already');
                        // $('#example11' ).on('tagremove', tagText );
                        return false;
                    }else{
                        box = box.val(box.val()+','+tagText);
                    }
                }
            }
        }  
    });
    
    
  } else if( opStatus === 'failure' ) {
    alert( 'Email \'' + tagText + '\' could not be added' );
  }
});
$( '#example11' ).on( 'tagremove', function( $event, tagText ) {
    var box = $('#shareuseremail').val();
    var arr = box.split(',');
    arr = $.grep(arr, function(value) {
      return value != tagText;
    });
    var ss = arr.toString();
    var attrbox = $('#shareuseremail');
    $('#shareuseremail').val('');
    if(attrbox.val() == ''){
        attrbox = attrbox.val(attrbox.val()+ss);
    }else{
        attrbox = attrbox.val(attrbox.val()+','+ss);
    }
    // console.log( 'Tag \'' + tagText + '\' removed' );
});
$('#team_user_id').on('change',function(){
    var baseurl = $('#baseurl').val();
    var id = $(this).val();
    $.ajax({
        url: baseurl+'/get-subuser', 
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
        data: { 
            user_id:id
        },
        success: function(response){ 
            admins ='';
            editors ='';
            approvers ='';
            viewers ='';
            admins += '<option value="">Select  Admin</option>';  
            for (var x = 0; x < response['admins'].length; x++) {
                admins += '<option value="' + response['admins'][x]['id'] + '">' + response['admins'][x]['name'] + '</option>';                        
            }
            $('#admins').html(admins);
            editors += '<option value="">Select  Editor</option>';  
            for (var x = 0; x < response['editors'].length; x++) {
                editors += '<option value="' + response['editors'][x]['id'] + '">' + response['editors'][x]['name'] + '</option>';                        
            }
            $('#editors').html(editors);
            approvers += '<option value="">Select  Approver</option>';  
            for (var x = 0; x < response['approvers'].length; x++) {
                approvers += '<option value="' + response['approvers'][x]['id'] + '">' + response['approvers'][x]['name'] + '</option>';                        
            }
            $('#approvers').html(approvers);
            viewers += '<option value="">Select  Viewer</option>';  
            for (var x = 0; x < response['viewers'].length; x++) {
                viewers += '<option value="' + response['viewers'][x]['id'] + '">' + response['viewers'][x]['name'] + '</option>';                        
            }
            $('#viewers').html(viewers);
        }  
    });
})

function shareChart2(id){
    $("#chart_id").val(id);
    $('#role-shareuseremail').val('');
    var baseurl = $('#baseurl').val();
    $.ajax({
        url: baseurl+'/role-user/role-share-chart-exist', 
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
        data: { 
            chart_id:id
        },
        success: function(response){ 
            if(response != 'none'){
                var users = " user's";
                $('#shared_detail_cnt').show();
                $('#shared_detail_cnt').html('Shared with '+response['cnt']+users +'. <a href="#" onclick="shared_user_detail2()"  data-toggle="modal" data-target="#shared_detail2">Check to view list</a>');
            }else{
                $('#shared_detail_cnt').hide();
            }
        }
    });
    var userurl = baseurl+'/role-user/team-sub-user-list'; 
    getSubUsers(id,userurl);
}
function shared_user_detail2(){
    $('#exampleModal').hide();
    $('.modal-backdrop').hide();
    var chart_id = $("#chart_id").val();
    var baseurl = $('#baseurl').val();
    $.ajax({
        url: baseurl+'/role-user/role-share-chart-exist', 
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
        data: { 
            chart_id:chart_id
        },
        success: function(response){ 
            if(response != 'none'){
                var shareData ='<table style="width: 100%;"><thead><tr><th>Collabrators</th><th>Status</th><th></th></tr></thead><tbody>';
                $.each(response['emails'], function (key, value) {
                    shareData+='<input type="hidden" id="key_email'+key+'" value="'+response['useremails'][key]+'"><input type="hidden" id="key_chart'+key+'" value="'+chart_id+'">';
                    if(response['dbstatus'][key] == 1){
                        var vstatus = 'Viewed';
                        var resend = '-';
                    }else{var vstatus = 'Pending';var resend = '<a href="#" onclick="resend_invitation2('+key+')">Resend Invitation</a>';}
                    shareData+='<tr><td>'+value+'</td><td>'+vstatus+'</td><td>'+resend+'</td></tr>';
                });
                shareData+='</<tbody></table>';
                $('#shared_list').html('');
                $('#shared_list').append(shareData);
            }else{
                $('#shared_list').html('');
            }
        }
    });
}
function resend_invitation2(key){
    var email = $('#key_email'+key).val();
    var chart_id = $('#key_chart'+key).val();
    var baseurl = $('#baseurl').val();
    $.ajax({
        url: baseurl+'/role-user/role-reshare-chart', 
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
        data: { 
            chart_id:chart_id,
            email:email
        },
        success: function(response){ 
            if(response == '1'){
                alert('Reshared successfully');
            }else{
                alert('Something went wrong');
            }
        }
    });
}
function plan_desc(val) { 
 var plan_desc = $('#plan_desc_'+val).val();
 $("#plandescription").html(plan_desc);     
}
function getSubUsers(id,baseurl){
    $('#role-shareuseremail').html('');
    $.ajax({
        url: baseurl, 
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
        data: { 
            chart_id:id
        },
        success: function(response){ 
            users ='';
            if(response.length > 0){
                for (var x = 0; x < response.length; x++) {
                    users += '<option value="' + response[x]['email'] + '">' + response[x]['email'] + '</option>';                        
                }
                $('#role-shareuseremail').html(users);
            }
        }
    });
}