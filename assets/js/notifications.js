$(function () {
  /* We create a click event for notifications, but you can modify easily this event to fit your needs */
  //$(".notification").click(function (e) {

  if ($("#notification").hasClass("notification")) {

    //function (e) {
    //e.preventDefault();

    /**** INFO MESSAGE TYPE ****/
    if ($("#notification").data("type") == 'info') {
      jNotify(
        $("#notification").data("message"), {
        HorizontalPosition: $("#notification").data("horiz-pos"),
        VerticalPosition: $("#notification").data("verti-pos"),
        ShowOverlay: $("#notification").data("overlay") ? $("#notification").data("overlay") : false,
        TimeShown: $("#notification").data("timeshown") ? $("#notification").data("timeshown") : 5000,
        OpacityOverlay: $("#notification").data("opacity") ? $("#notification").data("opacity") : 0.5,
        MinWidth: $("#notification").data("min-width") ? $("#notification").data("min-width") : 250
      });
    }

    /**** SUCCESS MESSAGE TYPE ****/
    else if ($("#notification").data("type") == 'success') {
      jSuccess(
        $("#notification").data("message"), {
        HorizontalPosition: $("#notification").data("horiz-pos"),
        VerticalPosition: $("#notification").data("verti-pos"),
        ShowOverlay: $("#notification").data("overlay") ? $("#notification").data("overlay") : false,
        TimeShown: $("#notification").data("timeshown") ? $("#notification").data("timeshown") : 5000,
        OpacityOverlay: $("#notification").data("opacity") ? $("#notification").data("opacity") : 0.5,
        MinWidth: $("#notification").data("min-width") ? $("#notification").data("min-width") : 250
      });
    }

    /**** ERROR MESSAGE TYPE ****/
    else if ($("#notification").data("type") == 'error') {
      jError(
        $("#notification").data("message"), {
        HorizontalPosition: $("#notification").data("horiz-pos"),
        VerticalPosition: $("#notification").data("verti-pos"),
        ShowOverlay: $("#notification").data("overlay") ? $("#notification").data("overlay") : false,
        TimeShown: $("#notification").data("timeshown") ? $("#notification").data("timeshown") : 5000,
        OpacityOverlay: $("#notification").data("opacity") ? $("#notification").data("opacity") : 0.5,
        MinWidth: $("#notification").data("min-width") ? $("#notification").data("min-width") : 250
      });
    }
  }


  /****  Example with Callback Function  ****/
  $("#notif-callback").click(function (e) {
    e.preventDefault();
    jNotify(
      '<i class="fa fa-info-circle" style="color:#00A2D9;padding-right:8px"></i> You have successfully clicked on the notification button. Congratulation!', {
      HorizontalPosition: 'right',
      VerticalPosition: 'bottom',
      ShowOverlay: false,
      TimeShown: 3000,
      onClosed: function () {
        alert('I am a function called when notif is closed !')
      }
    });
  });

});
