$(document).ready(function () {
  $("body").on("submit", "#alert-me-form", function (e) {
    e.preventDefault();

    var form = new FormData(this);
    $.ajax({
      url: moduleUrl, // Use the moduleUrl variable here
      type: "POST",
      dataType: "json",
      data: {
        action: "submitAlertForm",
        productId: form.get("productId"),
        name: form.get("name"),
        email: form.get("email"),
      },
      success: function (response) {
        if (response.success) {
          alert(response.message);
          $("#alert-me-modal").modal("hide");
        } else {
          alert("Error submitting alert");
        }
      },
      error: function () {
        alert("Error submitting alert");
      },
    });
  });
});
