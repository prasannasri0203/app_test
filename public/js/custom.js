//sideSubmenu
  $('.multi_submenu a').click(function () {
    $('ul.submenu_bar').slideToggle();
    $('.multi_submenu').toggleClass('openActive');
  });
  function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
function isDecimalNumber(evt, element) {

    var charCode = (evt.which) ? evt.which : evt.keyCode

    if (
        (charCode != 45 || $(element).val().indexOf('-') != -1) &&      // Check minus and only once.
        (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // Check dot and only once.
        (charCode < 48 || charCode > 57))
        return false;

    return true;
}    