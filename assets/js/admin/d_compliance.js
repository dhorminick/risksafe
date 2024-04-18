function columnSorting(page_num) {
  page_num = page_num ? page_num : 0;

  let coltype = "",
    colorder = "",
    classAdd = "",
    classRemove = "";
  $("th.sorting").each(function () {
    if ($(this).attr("colorder") !== "" || $(this).attr("colorder") !== null) {
      coltype = $(this).attr("coltype");
      colorder = $(this).attr("colorder");

      if (colorder == "asc") {
        classAdd = "asc";
        classRemove = "desc";
      } else {
        classAdd = "desc";
        classRemove = "asc";
      }
    }
  });

  $.ajax({
    type: "POST",
    url: "../data/complianceData",
    data: "page=" + page_num + "&coltype=" + coltype + "&colorder=" + colorder,
    beforeSend: function () {
      $(".loading-overlay").show();
    },
    success: function (html) {
      $("#dataContainer").html(html);

      if (coltype !== "" && colorder !== "" && coltype !== null && colorder !== null) {
        $("th.sorting").each(function () {
          if ($(this).attr("coltype") == coltype) {
            $(this).attr("colorder", colorder);
            $(this).removeClass(classRemove);
            $(this).addClass(classAdd);
          }
        });
      }

      $(".loading-overlay").fadeOut("slow");
    },
  });
}

$(function () {
  $(document).on("click", "th.sorting", function () {
    let current_colorder = $(this).attr("colorder");
    $("th.sorting").attr("colorder", "");
    $("th.sorting").removeClass("asc");
    $("th.sorting").removeClass("desc");
    if (current_colorder == "asc") {
      $(this).attr("colorder", "desc");
      $(this).removeClass("asc");
      $(this).addClass("desc");
    } else {
      $(this).attr("colorder", "asc");
      $(this).removeClass("desc");
      $(this).addClass("asc");
    }
    columnSorting();
  });
});
