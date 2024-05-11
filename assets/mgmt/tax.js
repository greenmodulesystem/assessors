$(".add").on("click", function () {
  // alert();

  // Find the closest parent row element
  var row = $(this).closest(".row");

  // Find the table within the row
  var table = row.find("table");

  // Get the specific class of the table
  var tableClass = "";
  if (table.hasClass("manufacturers")) {
    tableClass = "manufacturers";
  } else if (table.hasClass("dealers")) {
    tableClass = "dealers";
  } else if (table.hasClass("services")) {
    tableClass = "services";
  } else {
    console.log("Unknown table class");
    return; // Exit the function if the table class is unknown
  }

  var tax = [];
  $(".tax", table).each(function () {
    var taxTxt = $(this).text();
    tax.push(taxTxt);
  });

  var gf = [];
  $(".gross_from", table).each(function () {
    var gfTxt = $(this).text();
    gf.push(gfTxt);
  });

  var gt = [];
  $(".gross_to", table).each(function () {
    var gtTxt = $(this).text();
    gt.push(gtTxt);
  });

  var ID = [];
  $(".id", table).each(function () {
    var id = $(this).val();
    ID.push(id);
  });

  console.log("Table Class:", tableClass);
  console.log("ID:", ID);

  $.ajax({
    type: "POST",
    url: baseUrl + "tax_table/save",
    data: {
      tableClass: tableClass,
      tax: tax,
      ID: ID,
      gf: gf,
      gt: gt,
    },
  }).done(function (result) {
    // Handle the result if needed
    // document.location.reload(true);
  });
});

$(".add-retail").on("click", function () {
  $.ajax({
    type: "POST",
    url: baseUrl + "tax_table/save_retail",
    data: {
      ID: $(this).data("id"),
      gl: Number($("#gross_less").text()),
      gm: Number($("#gross_more").text()),
      tax: Number($("#tax-r").text()),
      tax_excess: Number($("#tax-excess").text()),
    },
  }).done(function (result) {
    // Handle the result if needed
    // document.location.reload(true);
  });
});

$(".save-other").click(function () {
  var rowId = $(this).closest("tr").data("id");
  // Extract data from the specific row
  var percent1 = $(this).closest("tr").find(".percent1").text();
  var percent2 = $(this).closest("tr").find(".percent2").text();

  $.ajax({
    url: baseUrl + "tax_table/save_other",
    method: "POST",
    data: {
      id: rowId,
      percent1: percent1,
      percent2: percent2,
    },
    success: function (response) {
      console.log(response);
    },
    error: function (error) {
      console.error("Error:", error);
    },
  });
});

$(".save-fixed").click(function () {
  var rowId = $(this).closest("tr").data("id");
  var fee = $(this).closest("tr").find(".fee").text();

  $.ajax({
    url: baseUrl + "tax_table/save_fixed",
    method: "POST",
    data: {
      id: rowId,
      fee: fee,
    },
    success: function (response) {
      console.log(response);
    },
    error: function (error) {
      console.error("Error:", error);
    },
  });
});

$(".new-fix").click(function () {

  var cat = $("#category-fixed").val();
  console.log(cat);
  if(cat){
    $.ajax({
      url: baseUrl + "tax_table/save_fixed_new",
      method: "POST",
      data: {
        category:cat,
        description: $("#bline").val(),
        fee: $("#fee-new").val(),
      },
      success: function (response) {
        console.log(response);
        document.location.reload(true);
      },
      error: function (error) {
        console.error("Error:", error);
      },
    });
  }else{
    alert("Select a category");
  }
  
});