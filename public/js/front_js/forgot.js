$(document).ready(function(){
    $(".Continue_btn").click(function(){

        var otp1 =   $('#otp1').val();
        var otp2 =   $('#otp2').val();
        var otp3 =   $('#otp3').val();
        var otp4 =   $('#otp4').val();
        
        var otp   = otp1+otp2+otp3+otp4;
        $('input[name="otp"]').val(otp);
        return true;
    });

    $("input").keyup(function (e) {
        if (this.value.length == this.maxLength) {
          $(this).next('input').focus();
        }

        if ((e.which == 8 || e.which == 46) && $(this).val() =='') {
            $(this).prev('input').focus();
        }
    });
});