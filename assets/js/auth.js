var totalHeight = $("#totalHeight").height();
var navHeight = $(".main-navbar").height();
var totalWidth = $("#totalHeight").width();
var footerHeight = $(".fopaei").height();

var calcHeight = totalHeight - footerHeight - navHeight;
if (totalWidth > 575) {
  $(".firoiz").height(calcHeight - 60);
  $(".firoiz").width(totalWidth - 30);
} else {
  $(".firoiz").height(calcHeight);
  $(".firoiz").width(totalWidth);
  $(".fnhsgr8").css({ "margin-right": "-10px", "margin-left": "-10px" });
}

$(".fyeviu").on("click", function () {
  var x = document.getElementById("password");
  if (x.type === "password") {
    $(".fyeviu i").attr("class", "fa fa-eye-slash");
    x.type = "text";
  } else {
    $(".fyeviu i").attr("class", "fa fa-eye");
    x.type = "password";
  }
});
