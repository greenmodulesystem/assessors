$(document).on("click", ".savedue", function () {
  var id = $(this).data("id");
  var dayVal = $(this).closest(".col-md-6").find(".dayval").val();
  var monthVal = $(this).closest(".col-md-6").find(".monthval").val();

  // Check if values are retrieved correctly
  console.log("Day Value:", dayVal);
  console.log("Month Value:", monthVal);

  $.post({
    url: baseUrl + "due_date/save",
    data: {
      ID: id,
      day: dayVal,
      month: monthVal,
    },
    success: function (response) {
      // Uncomment the following line if you want to reload the page after successful save
      // document.location.reload(true);
      console.log("Save Successful");
    },
    error: function (xhr, status, error) {
      console.error("An error occurred while saving the due date:", error);
      alert("An error occurred while saving the due date.");
    },
  });
});
