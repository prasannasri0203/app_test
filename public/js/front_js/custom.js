$(document).ready(function(){ 

  $('.card-body.card-c').click(function(){
    $('.navbar').toggleClass('hide');
  });

  $('.card-body.card-c').click(function(){
    var db_user_plan_id = $('#db_user_plan_id').val();
    var id = $(this).attr('data-id');
    var db_user_role_id = $('#db_user_role_id').val();
    var user_plan_type = $('#user_plan_type').val();
    // alert(db_user_plan_id);
    // alert(id);
    if($(this).hasClass('mb-ic')) {
     $(this).removeClass('mb-ic');
    }
    else{
        $('.card-body.card-c').removeClass('mb-ic');
        if(user_plan_type == 'register'){
          $(this).addClass("mb-ic");
        }else{
          if((id != db_user_plan_id && id != 0) || (db_user_role_id != 4 && id == 0)){
            $(this).addClass("mb-ic");
          }
        }
    }
      
   });
  $(".flow_chart_part").click(function(){
    if($(this).hasClass("active")){
        $(this).removeClass("active");
    }
    else{
        $(".flow_chart_part").removeClass("active");
        $(this).addClass("active");
    }
  });
  setBackgroundColor();
});

//sideSubmenu
$('.multi_submenu a').click(function () {
$('ul.submenu_bar').slideToggle();
$('.multi_submenu').toggleClass('openActive');
});
$('.close').on('click',function(){
    $('#thememsg').hide();
})
function setBackgroundColor(){

  var code = localStorage.getItem("background_color");
  var font = localStorage.getItem("font_color");
  console.log(code+'color');
  $('.main_part').css('background-color',code+'99');
  $('h2.recent_flow').css('color',font);
  $('h4.Acc_Setting.as').css('color',font);
  $('h4.Acc_Setting.pl').css('color',font);
  $('h4.Acc_Setting.as').css('border-bottom','2px solid '+font);
  $('h4.Acc_Setting.pl').css('border-bottom','2px solid '+font);
  $('#wrapper .left-navn .list-group li.active > a').css('background-color',code);
//   $('#wrapper .left-navn .list-group li.active .navbra-nav a').css('background-color',unset);
//   $('#wrapper .left-navn .list-group li.active .navbra-nav li.active a').css('background-color',unset);
//   $('#wrapper .left-navn .list-group li.active .navbra-nav li a').css('background-color',unset);
  $('#wrapper .Upgrade_btn').css('background-color',code);
  $('#wrapper button .badge').css('background-color',code);
  $('#wrapper .XA_text').css('background-color',code);

  
//   $('#wrapper .left-navn .list-group li:hover.active').css('background-color',code);
  $('#wrapper .left-navn .list-group li.navbra-nav li hover a').css('background-color',code);
  $('#wrapper .left-navn .list-group li.active a').css('border-left',code);
  $('#wrapper .left-navn .list-group li a').css('border-left',code);

  $('#wrapper .flow_chart_part.active a').css('background-color',code);
$('#wrapper .flow_chart_part.active .navbra-nav li a').css('background-color','unset');
$('#wrapper .left-navn .list-group li.flow_chart_part .navbra-nav li.active a').css('color',code);

//   $('#wrapper .left-navn .list-group li').mouseover(function(){
//       $(this).find('a').css('background-color',code);
//   });



//   $('div.theme_popup .modal-content .modal-footer .btn').mouseover(function(){
//       $(this).css('background-color',code);
//   });
    
//   $('#wrapper .left-navn .list-group li').mouseleave(function(){
//       $(this).find('a').css('background-color','#fff');
//   });

//   $('#wrapper .left-navn .list-group li .navbra-nav li').mouseleave(function(){
//     $(this).find('a').css('background-color',unset);
// });

//   $('#wrapper .left-navn .list-group li.active').mouseleave(function(){
//       $(this).find('a').css('background-color',code);
//   });

//   $('#wrapper .left-navn .list-group li.active .navbra-nav li').mouseleave(function(){
//     $(this).find('a').css('background-color',unset);
// });

//   $('.blue_btn').css('background-color',code);
//   $('.white_btn').css('border','1px solid '+code);
//   $('div.theme_popup .modal-content .modal-footer .btn').mouseleave(function(){
//       $(this).css('background-color','#fff');
//   });


// $('#wrapper .flow_chart_part.active .navbra-nav li a').css('background-color','unset');

  if(!code)
  {    

    $('#wrapper .left-navn .list-group li.active a').css('border-left','#008CC5');
  $('#wrapper .left-navn .list-group li a').css('border-left','#008CC5');

  $('#wrapper .flow_chart_part.active a').css('background-color','#008CC5');
$('#wrapper .flow_chart_part.active .navbra-nav li a').css('background-color','unset');
$('#wrapper .left-navn .list-group li.flow_chart_part .navbra-nav li.active a').css('color','#008CC5');
    // $('#wrapper .left-navn .list-group li.active a').css('background-color','#008CC5');
    // $('#wrapper .Upgrade_btn').css('background-color','#008CC5');
    // $('#wrapper button .badge').css('background-color','#008CC5');
    // $('#wrapper .XA_text').css('background-color','#008CC5');
    // $('#wrapper .left-navn .list-group li:hover.active').css('background-color','#008CC5');
    // $('#wrapper .left-navn .list-group li.hover a').css('background-color','#008CC5');
    // $('#wrapper .left-navn .list-group li.hover .navbra-nav li a').css('background-color','unset');
    // $('#wrapper .left-navn .list-group li.active a').css('border-left','#008CC5');
    // $('#wrapper .left-navn .list-group li a').css('border-left','#008CC5');

    // $('#wrapper .left-navn .list-group li').mouseover(function(){
    //     $(this).find('a').css('background-color','#008CC5');
    // });

    // $('div.theme_popup .modal-content .modal-footer .btn').mouseover(function(){
    //     $(this).css('background-color','#008CC5');
    // });
      
    // $('#wrapper .left-navn .list-group li').mouseleave(function(){
    //     $(this).find('a').css('background-color','#fff');
    // });
    // $('#wrapper .left-navn .list-group li.active').mouseleave(function(){
    //     $(this).find('a').css('background-color','#008CC5');
    // });

    // $('#wrapper .left-navn .list-group li .navbra-nav li').mouseover(function(){
    //     $(this).find('a').css('background-color','unset');
    // });
    // $('#wrapper .left-navn .list-group li .navbra-nav li').mouseleave(function(){
    //     $(this).find('a').css('background-color','unset');
    // });
  }

  

}
function setDefaultBackgroundColor(){
  $('.main_part').css('background-color','#f6fbfd');
  $('h2.recent_flow').css('color','#202020c7');
  $('h4.Acc_Setting.as').css('color','#202020c7');
  $('h4.Acc_Setting.pl').css('color','#202020c7');
  $('h4.Acc_Setting.as').css('border-bottom','2px solid '+'#202020c7');
  $('h4.Acc_Setting.pl').css('border-bottom','2px solid '+'#202020c7');

  $('#wrapper .left-navn .list-group li.active a').css('background-color','#008CC5');
  $('#wrapper .Upgrade_btn').css('background-color','#008CC5');
  $('#wrapper button .badge').css('background-color','#008CC5');
  $('#wrapper .XA_text').css('background-color','#008CC5');
  $('#wrapper .left-navn .list-group li:hover.active').css('background-color','#008CC5');
  $('#wrapper .left-navn .list-group li.hover a').css('background-color','#008CC5');
  $('#wrapper .left-navn .list-group li.active a').css('border-left','#008CC5');
  $('#wrapper .left-navn .list-group li a').css('border-left','#008CC5');
  $('#wrapper .flow_chart_part.active a').css('background-color','#008CC5');
//   $('#wrapper .flow_chart_part.active .navbra-nav a').css('background-color',unset);

  $('#wrapper .left-navn .list-group li').mouseover(function(){
      $(this).find('a').css('background-color','#008CC5');
  });
 

  $('div.theme_popup .modal-content .modal-footer .btn').mouseover(function(){
      $(this).css('background-color','#008CC5');
  });
  
    
  $('#wrapper .left-navn .list-group li').mouseleave(function(){
      $(this).find('a').css('background-color','#fff');
  });

  

  $('#wrapper .left-navn .list-group li.active').mouseleave(function(){
      $(this).find('a').css('background-color','#008CC5');
  });
}

$('.export_btn').on('click',function(){ 
   $('#export_val').val('1');
}); 
  var manage_statustype = $( ".manage_status" ).val(); 

  
   if(manage_statustype=='1'){ 
           $('.role').addClass('content_hide'); 
        }else if(manage_statustype=='2'){ 
            $('.role').removeClass('content_hide'); 
        }else{
          $('.role').addClass('content_hide'); 
        }

$('.role_user_btn').on('click',function(){ 
   var manage_statustype = $( ".manage_status" ).val();  
        if(manage_statustype=='1'){ 
           $('.role').addClass('content_hide'); 
        }else if(manage_statustype=='2'){ 
            $('.role').removeClass('content_hide'); 
        }
}); 

  $(".manage_status").change(function(){
        var manage_status_type = $( ".manage_status" ).val(); 
        if(manage_status_type=='1'){ 
           $('.role').addClass('content_hide'); 
        }else if(manage_status_type=='2'){ 
            $('.role').removeClass('content_hide'); 
        }
  });


