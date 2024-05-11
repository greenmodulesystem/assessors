$(document).on("keypress", function (e) {
  if (e.which === 13 || e.keyCode === 13) {
      $("#addfee").trigger("click");
  }
});

$("#addfee").on("click", function () {
  if ($("#busID").val() == "" || $("#rate").val()==null || $("#rate").val() == 0) {
    alert("Imake sure da kay wala unod ang fee or businessline");
    return false;
  } else {
    $.ajax({
      type: "POST",
      url: baseUrl + "management/save",
      data: {
        busid: $("#busID").val(),
        rate: $("#rate").val(),
      },
    }).done(function (result) {
      document.location.reload(true);
    });
  }
});
