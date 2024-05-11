$(document).ready(function () {
  load_grid();
});

var load_grid = () => {
  $(document).gmLoadPage({
    url: baseUrl + "ledger/grid",
    load_on: "#load-grid",
  });
};

$(document).on("click", ".view-cycle", function () {
  var id = $(this).data("id");
  window.location.href = baseUrl + "ledger/view_cycle/" + id;
});

$(document).on("click", ".cycle-row", function () {
  // alert();
  var id = $(this).data("cycleid");
  window.location.href = baseUrl + "ledger/view_assessment/" + id;
});

$("#search").on("change", function () {
  $.post({
    url: baseUrl + "ledger/search",
    data: {
      search: $(this).val(),
    },
    success: function (response) {
      $("#load-grid").html(response);
    },
  });
});
