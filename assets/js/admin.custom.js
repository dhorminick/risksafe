"use strict";


$("#swal-custom-1").click(function () {
  swal(
    "RiskSafe Help",
    'You add treatments to your risk by selecting existing treatment from your treatments library or you can create your own custom treatment by typing it into the text field below and clicking on "+Add" button.!',
    "info"
  );
});
$("#swal-custom-2").click(function () {
  swal(
    "RiskSafe Help",
    'RiskSafe Control management comes in three phases, RiskSafe recommended controls, your saved controls, and page specific controls. Select your desired control or add a custom with the form below.',
    "info"
  );
});
$("#likeli_conseq").click(function () {
  swal(
    "RiskSafe Help",
    'An automated assessment of the specified risk, calculated by averaging the selected risk consequence and likelihood.',
    "info"
  );
});

$(document).ready(function () {
    //refresh function
    $("#f93nfo0").click(function (e) {
      $("#fh4nfve").load(" #fh4nfve > *");
    });
    //refresh treatment
    $("#f93nfo1").click(function (e) {
      $("#fh4nfvf").load(" #fh4nfvf > *");
    });
    
        // $("#btn-append-custom-control").click(function () {
        //   var plusOne = "<input type='text' class='form-control' placeholder='Enter custom control description...' style='margin-top:5px;' name='custom-control[]'>";   
        //     $('#add-customs-control').append(plusOne); 
        // });
        
        // $("#btn-append-custom-treatment").click(function () {
        //   var plusOne = "<input type='text' class='form-control' placeholder='Enter custom treatment description...' style='margin-top:5px;' name='custom-treatment[]'>";   
        //     $('#add-customs-treatment').append(plusOne); 
        // });
        
        // $("#btn-remove-custom-control").click(function () {
        //     var page = $('#add-customs-control');
        //     page.removeChild(page.lastElementChild);
        // });
        
            var maxFieldTreatment = 20; //Input fields increment limitation
            var addButtonTreatment = $('#btn-append-custom-treatment'); //Add button selector
            var wrapperTreatment = $('#add-customs-treatment'); //Input field wrapperTreatment
            var fieldHTMLTreatment = '<div style="display:flex;justify-content:center;align-items:center;"><input type="text" class="form-control" placeholder="Enter Custom Treatment Description..." style="margin-top:5px;" name="custom-treatment[]"  required/><buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>'; //New input field html 
            var x_Treatment = 1; //Initial field counter is 1
            
            // Once add button is clicked
            $(addButtonTreatment).click(function(){
                //Check maximum number of input fields
                if(x_Treatment < maxFieldTreatment){ 
                    x_Treatment++; //Increase field counter
                    $(wrapperTreatment).append(fieldHTMLTreatment); //Add field html
                }else{
                    alert('A maximum of '+maxFieldTreatment+' fields are allowed to be added. ');
                }
            });
            
            // Once remove button is clicked
            $(wrapperTreatment).on('click', '.remove_button_t', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x_Treatment--; //Decrease field counter
            });
            
            
            //controls
            var maxField = 20; //Input fields increment limitation
            var addButton = $('#btn-append-custom-control'); //Add button selector
            var wrapper = $('#add-customs-control'); //Input field wrapper
            var fieldHTML = '<div style="display:flex;justify-content:center;align-items:center;"><input type="text" class="form-control" placeholder="Enter Custom Control Description..." style="margin-top:5px;" name="custom-control[]" required /><buttton class="btn btn-sm btn-primary remove_button" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>'; //New input field html 
            var x = 1; //Initial field counter is 1
            
            // Once add button is clicked
            $(addButton).click(function(){
                //Check maximum number of input fields
                if(x < maxField){ 
                    x++; //Increase field counter
                    $(wrapper).append(fieldHTML); //Add field html
                }else{
                    alert('A maximum of '+maxField+' fields are allowed to be added. ');
                }
            });
            
            // Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x--; //Decrease field counter
            });
        
        
    });