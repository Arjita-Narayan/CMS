// Initialize TinyMCE
tinymce.init({
  selector: "#mytextarea",
  license_key: "gpl",
  readonly: false,
});

$(document).ready(function () {
  // Select All Checkboxes
  $("#selectAllBoxes").click(function () {
    $(".checkBoxes").prop("checked", this.checked);
  });

  // Loading Screen
  const div_box = "<div id='load-screen'><div id='loading'></div></div>";
  $("body").prepend(div_box);

  $("#load-screen")
    .delay(700)
    .fadeOut(600, function () {
      $(this).remove();
    });
});

// Load Online Users
function loadUsersOnline() {
  $.get("functions.php?onlineusers=result", function (data) {
    $(".usersonline").text(data);
  });
}
setInterval(loadUsersOnline, 500);
