"use strict";
$(document).ready(function (e) {
  $(".contact-popup").hide();
});

$(".contact-popup-opener").click(function () {
  $(".contact-popup").toggle(400);
});


$("body").click(function () {
  $(".search-main").hide();
  //$(".search_cover").hide();
});
$("#btn_cancel").click(function () {
  window.history.back();
});
$("#btn_cancel").html('<i class="fas fa-times-circle"></i> Cancel')


$("#fnd939sn").keyup(function (e) {
            var q = $(this).val();
            if (q !== "") {
                
                $("#q").val(q);
                if (q.length >= 2) {
                    $(".search-main").show();
                    //$(".search_cover").show();
                    $("#search").submit();
                }else{
                    $(".search-main").html('');
                    $(".search-main").hide();
                    //$(".search_cover").hide();
                    $(".search-main").css("margin-top", "0px");
                }
                // alert('first stop!');
            } else {
                //$(".search_cover").hide();
                $(".search-main").hide();
                $("#q").val('');
                $(".search-main").html('');
                $(".search-main").css("margin-top", "0px");
            }
        });

        $("#search").submit(function (e) {
            //event.preventDefault();
            //alert('semi stop!');
            e.preventDefault();

            var formValues = $(this).serialize();

            $.post("/ajax/search", {
                search: formValues,
            }).done(function (data) {
                $(".search-main").css("margin-top", "10px");
                $(".search-main").html(data);
                // alert('second stop!');
            });
        });
        
$("#file_opener").on("click", function () {
          $("#file_main").click();
        });
        
        $("#file_main").on("change", function () {
            for (let i = 0; i < this.files.length; i++) {
                $(".file_name").html(this.files[i].name);
            }
        });
        
        $("#delete-users").click(function () {
            var user = $(this).attr('user');
            var email = $(this).attr('email');

            if (email && user && email !== null && user !== null || email && user && email !== '' && user !== '') {
                $("#userEmail").text('');
                $("#formEmail").val('');
                $("#formId").val('');
                $("#userEmail").text(email);
                $("#formEmail").val(email);
                $("#formId").val(user);
            } else {
                alert('Error 402');
                window.location.refresh;
                
            }
        });
        $("#del").submit(function (event) {
            event.preventDefault();
            var formValues = $(this).serialize();

            $.post("../ajax/users", {
                deleteUser: formValues,
            }).done(function (data) {
              $(".close").click();
              $(".res").addClass('show');
              $(".res").html(data);
              $("#users").load(" #users > *");
            });
        });
        
        $(".export__data").click(function(e) { 
            var id = $(this).attr('export-id');
            var data = $(this).attr('export-data');
            if (id == '' || !id || id == null || data == '' || !data || data == null) {
                alert('Error 402!!');
                window.location.reload(true);
            } else {
                $("#export-id").val(id);
                $("#export-data").val(data);
            }
        });
        $("#export_file_type").change(function(e) { 
            var file_type = $(this).val();
            if (file_type == '' || !file_type || file_type == null) {
                alert('Error 402!!');
                window.location.reload(true);
            } else {
                $("#export-type").val(file_type);
                // file__type = file_type.toUpperCase();
                $(".export_type").html(file_type);
            }
        });