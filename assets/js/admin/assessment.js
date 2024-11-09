function refreshTreatment(){
    $("#fh4nfve").load(" #fh4nfve > *");
    //$("#f94jf0k").html("<a href='../customs/new-control' class='btn btn-sm btn-primary' id='fn4h9nf' style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>");
}

//switch button func

// $("#risk").change(function (e) {
//   var riskValue = $("#risk").val();
//   if (riskValue == "0") {
//       $(".hazard_empty").show();
//       $("#hazard_div").html('');
//       $("#risk-description").val('');
      
//       $("#control_selctor").html('Select Risk Above To Get Recommended Control!!');
//   } else {
//     $("#get_hazard").val();
//     $("#get_hazard").val(riskValue);
//     $("#get_desc").val();
//     $("#get_desc").val(riskValue);
    
//     $("#risk_val").val();
//     $("#risk_val").val(riskValue);
                        
//     $("#getControls").submit();
    
//     $("#getHazard").submit();
//     $("#getDescription").submit();
//   }
// });

$("#getDescription").submit(function (event) {
  event.preventDefault();

  var formValues = $(this).serialize();
  $("#risk-description").val('Fetching Description...');
  $.post("../ajax/assessment", {
    getDescription: formValues,
  }).done(function (data) {
    $("#risk-description").val(data);
    setTimeout(function () {
      $("#getDescription input").val("");
    }, 0);
  });
});

$("#getHazard").submit(function (event) {
  event.preventDefault();

  var formValues = $(this).serialize();
  $("#hazard_div").html('Fetching Hazards...');
  $(".hazard_empty").hide();
  $.post("../ajax/assessment", {
    getHazard: formValues,
  }).done(function (data) {
    $("#hazard_div").html(data);
    $(".hazard_empty").hide();
    $(".hazard").show();
    setTimeout(function () {
      $("#getHazard input").val("");
    }, 0);
  });
});

$("#getRisk").submit(function (event) {
  // alert('first first stop!');
  event.preventDefault();

  var formValues = $(this).serialize();

  $.post("../ajax/assessment", {
    getRisk: formValues,
  }).done(function (data) {
    $("#hazard_div").html(data);
    $(".hazard_empty").hide();
    $("#testId").html(data);
    $(".hazard").show();
    setTimeout(function () {
      $("#getRisk input").val("");
    }, 0);
    // alert(data);

    // alert('second stop!');
  });
});

$("#addTreatment").submit(function (event) {
  // alert('first first stop!');
  event.preventDefault();

  var formValues = $(this).serialize();

  $.post("../ajax/assessment", {
    addTreatment: formValues,
  }).done(function (data) {
    $(".close").click();
    // alert(data);
    // $("#testId").html(data);
    setTimeout(function () {
      $("#custom-treatment-input").val("");
      // $("#existing_tr").load(" #existing_tr > *");
      $("#treatments").load(" #treatments > *");
    }, 1000);

    // alert('second stop!');
  });
});

$("#delTreatment").submit(function (event) {
  // alert('first first stop!');
  event.preventDefault();

  var formValues = $(this).serialize();

  $.post("../ajax/assessment", {
    delTreatment: formValues,
  }).done(function (data) {
    $(".close").click();
    // alert(data);
    // $("#testId").html(data);
    setTimeout(function () {
      // $("#custom-treatment-input").val('');
      // $("#existing_ct").load(" #existing_ct > *");
      $("#treatments").load(" #treatments > *");
    }, 1000);

    // alert('second stop!');
  });
});

$("#addControl").submit(function (event) {
  // alert('first first stop!');
  event.preventDefault();

  var formValues = $(this).serialize();

  $.post("../ajax/assessment", {
    addControl: formValues,
  }).done(function (data) {
    $(".close").click();
    // alert(data);
    // $("#testId").html(data);
    setTimeout(function () {
      $("#custom-control-input").val("");
      // $("#existing_ct").load(" #existing_ct > *");
      $("#controls").load(" #controls > *");
    }, 1000);

    // alert('second stop!');
  });
});

$("#delControl").submit(function (event) {
  // alert('first first stop!');
  event.preventDefault();

  var formValues = $(this).serialize();

  $.post("../ajax/assessment", {
    delControl: formValues,
  }).done(function (data) {
    $(".close").click();
    // alert(data);
    // $("#testId").html(data);
    setTimeout(function () {
      // $("#delControl input").val('');
      // $("#existing_ct").load(" #existing_ct > *");
      $("#controls").load(" #controls > *");
    }, 1000);

    // alert('second stop!');
  });
});

$("#getRating").submit(function (event) {
  // alert('first first stop!');
  event.preventDefault();

  var formValues = $(this).serialize();
  $("#rating").html('Calculating Rating...');
  $.post("../ajax/assessment", {
    getRating: formValues,
  }).done(function (data) {
    // alert(data);
    $("#rating").html(data);
    $(".risk-rating").show();
    setTimeout(function () {
      $("#getRating input").val("");
    }, 0);

    // alert('second stop!');
  });
});

$("#consequence").change(function (e) {
  var riskConsequence = $(this).val();
  var likelihoodValue = $("#likelihood").val();
  if (riskConsequence !== "0" && likelihoodValue !== "0") {
    likelihoodValue = likelihoodValue * 1;
    riskConsequence = riskConsequence * 1;

    $("#get_risk_consequence").val(riskConsequence);
    $("#get_risk_likelihood").val(likelihoodValue);

    $("#getRating").submit();
  } else {
  }
});

$("#likelihood").change(function (e) {
  var riskLikelihood = $(this).val();
  var consequenceValue = $("#consequence").val();

  if (riskLikelihood !== "0" && consequenceValue !== "0") {
    consequenceValue = consequenceValue * 1;
    riskLikelihood = riskLikelihood * 1;

    $("#get_risk_consequence").val(consequenceValue);
    $("#get_risk_likelihood").val(riskLikelihood);

    $("#getRating").submit();
  } else {
  }
});

$("#btn-add-custom-treatment").attr("data-toggle", "empty");
$("#custom-treatment-input").change(function (e) {
  var myInput = $(this).val();
  if (myInput == "" || myInput == null) {
    $("#btn-add-custom-treatment").attr("data-toggle", "empty");
    $("#btn-add-custom-treatment").attr("data-target", "empty");
  } else {
    $("#custom-treatment-text").html(myInput);
    $("#custom-treatment-description").val(myInput);
    $("#btn-add-custom-treatment").attr("data-toggle", "modal");
    $("#btn-add-custom-treatment").attr("data-target", "#add-treatment");
  }
});

$("#btn-add-custom-control").attr("data-toggle", "empty");
$("#custom-control-input").change(function (e) {
  var myInput = $(this).val();
  if (myInput == "" || myInput == null) {
    $("#btn-add-custom-control").attr("data-toggle", "empty");
    $("#btn-add-custom-control").attr("data-target", "empty");
  } else {
    $("#custom-control-text").html(myInput);
    $("#custom-control-description").val(myInput);
    $("#btn-add-custom-control").attr("data-toggle", "modal");
    $("#btn-add-custom-control").attr("data-target", "#add-control");
  }
});

$("#btn-add-custom-control").click(function (e) {
  var btnInput = $("#custom-control-input").val();

  if (btnInput == "" || btnInput == null) {
    $("#custom-control-input").focus();
  } else {
  }
});


$(".del-treats").click(function (e) {
  var data_description = $(this).attr("data-description");
  var data_id = $(this).attr("data-id");

  if (
    data_description == "" ||
    data_id == "" ||
    data_id == null ||
    data_description == null
  ) {
  } else {
    $("#delete-custom-treatment-text").html(data_description);
    $("#delete-custom-treatment-id").val(data_id);
  }
});

$(".del-ctrls").click(function (e) {
  var data_description = $(this).attr("data-description");
  var data_id = $(this).attr("data-id");

  if (
    data_description == "" ||
    data_id == "" ||
    data_id == null ||
    data_description == null
  ) {
  } else {
    $("#delete-custom-control-text").html(data_description);
    $("#delete-custom-control-id").val(data_id);
  }
});
// $("#tests").load('../ajax/assessment?get=tests');
