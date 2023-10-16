<?php
    $file_dir = '../../';
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    include '../../layout/variablesandfunctions.php';

    include_once('../rs/model/assessment.php');

if (isset($_REQUEST["response"]) and $_REQUEST["response"] == "true") {
  if ($_REQUEST["action"] == "editdetail") $msg = "Risk updated sucessfully.";
  if ($_REQUEST["action"] == "adddetail") $msg = "Risk added sucessfully. You may continue with your risk assessment.";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"] == "err") {
  $msg = "An error occured, please try again.";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"] == "err") {
  $msg = "An error occured, please try again.";
}

    $assess = new assessment();
    $assess->deleteTmpFields();

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $ass_Id = sanitizePlus($_GET['id']);
        $toDisplay = true;
        $assessment = $assess->getAssessment($ass_Id);

        if (isset($_GET['action']) && isset($_GET['action']) == "editdetail") {
        $edit = true;
        $id = sanitizePlus($_GET['assessment']);
        $datainfo = $assess->getAssessmentDet($id);
        } else {
        $id = -1;
        $edit = false;
        }

    } else {
        $toDisplay = false;
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Assessment Details | </title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require '../../layout/header.php' ?>
        <?php require '../../layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <?php if ($toDisplay == true) { ?>
                <div class="card">
                    <form role="form" id="form" action="../rs/controller/assessment.php">
                        <?php include '../../layout/alert.php'; ?>
                        <div class="form-group">
                            <button type="button" class="btn btn-md btn-primary pull-right" id="btn_viewdet">Switch to Assessment Details</button>
                        </div>
                        <div class="card-header">
                            <h3 class="subtitle">Risk Identification</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Risk</label>
                                <div id="risk_div"></div>
    
                            </div>
                            <div class="form-group">
                                <label>Risk Sub Category</label>
                                <div id="hazard_div"></div>
    
                            </div>
                            <div class="form-group">
                                <label>Risk Description</label>
                                <textarea name="descript" rows="4" class="form-control" placeholder="Enter risk description..." required><?php if ($edit) echo $datainfo["as_descript"]; ?></textarea>
    
                            </div>
                        </div>
                        
                        <div class="card-header">
                            <h3 class="subtitle">Risk Evaluation
                                <a href="#" data-tooltip="Select likelyhood of the risk and risk consequence. Risk rating will be calculated automatically.">
                                    <img src="../img/help_ico.gif" class="help-ico">
                                </a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Likelihood</label>
                                <?php
                                if ($edit) {
                                    echo $assess->listLikelihood($datainfo["as_like"]);
                                } else {
                                    echo $assess->listLikelihood(-1);
                                }
    
                                ?>
                            </div>
                            <div class="form-group">
                                <label>Consequence</label>
                                <?php
                                if ($edit) {
                                    echo $assess->listConsequence($datainfo["as_consequence"]);
                                } else {
                                    echo $assess->listConsequence(-1);
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <label>Risk Rating</label>
                                <div id="rating"></div>
                            </div>
                        </div>
                        
                        
                        <div class="card-header"><h3 class="subtitle">Control actions</h3></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Controls in place<a href="#" data-tooltip="You add controls to your risk by selecting existing control from your controls library or you can create your own custom control by typing it into the text field below and clicking on '+Add' button."><img src="../img/help_ico.gif" / class="help-ico"></a></label>
                                <select name="existing_ct" id="existing_ct" class="form-control" required>
                                    <option value="-1" selected>Select and add an existing control</option>
                                    <?php
                                    echo $assess->listControl($_SESSION["userid"]);
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <div style="display: flex;">
                                    <input style="width:90%;margin-right:10px;" name="control[]" id="control" type="text" class="form-control" placeholder="Enter custom control description...">
                                    <button style="width:10%;" type="button" class="btn btn-sm btn-primary" id="btn_addcontrol">+ Add</button>
                                </div>
                                <div class="custom-controls"></div>
                            </div>
                            <div class="clearfix" id="controls">
                                &nbsp;
                            </div>
                            <div class="form-group">
                                <label>Control Effectiveness</label>
                                <textarea name="effectiveness" rows="4" class="form-control" placeholder="Enter control effectiveness..." required><?php if ($edit) echo $datainfo["as_effect"]; ?></textarea>
    
                            </div>
                            <div class="form-group">
                                <label>Action Type</label>
                                <?php
                                if ($edit) {
                                    echo $assess->listActions($datainfo["as_action"]);
                                } else {
                                    echo $assess->listActions(-1);
                                }
    
                                ?>
    
                            </div>
                        </div>
                        
                        
                        <div class="card-header"><h3 class="subtitle">Treatment Plans</h3></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Treatments<a href="#" data-tooltip="You add treatments to your risk by selecting existing treatment from your treatments library or you can create your own custom treatment by typing it into the text field below and clicking on '+Add' button."><img src="../img/help_ico.gif" / class="help-ico"></a></label>
                                <select name="existing_tr" id="existing_tr" class="form-control" required>
                                    <option value="-1" selected>Select and add an existing treatment</option>
                                    <?php
                                    echo $assess->listTreatmentsLib($_SESSION["userid"]);
                                    ?>
                                </select>
    
                            </div>
                            <div class="form-group">
                                <input style="width:84%;float:left;" name="treatment" id="treatment" type="text" maxlength="255" class="form-control" placeholder="Enter custom treatment description...">
                                <button style="width:15%;float:right;margin-top:0px;" type="button" class="btn btn-sm btn-info" id="btn_addtreatment">+ Add</button>
                            </div>
                            <div class="clearfix" id="treatments">
                                &nbsp;
                            </div>
                            <div class="form-group">
                                <label>Due Date</label>
                                <input name="date" id="date" type="text" maxlength="100" class="form-control readonly" placeholder="Select date..." required readonly style="cursor:pointer;" value="<?php if ($edit) { echo date("m/d/Y", strtotime($datainfo["as_duedate"])); } else { echo date("m/d/Y"); } ?>">
                            </div>
                            <div class="form-group">
                                <label>Action Owner</label>
                                <input name="owner" id="owner" type="text" maxlength="100" class="form-control" placeholder="Enter action owner..." required value="<?php if ($edit) echo $datainfo["as_owner"]; ?>">
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <button type="submit" class="btn btn-md btn-info" id="btn_save">Save Risk</button>
                                <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>

                                <input name="action" type="hidden" value="<?php #echo $_REQUEST["action"]; ?>" />
                                <input name="id" type="hidden" value="<?php if ($edit) echo $id; ?>" />
                                <input name="assessmentId" type="hidden" value="<?php echo $assessment['idassessment']; ?>" />

                            </div>
                        </div>

                    </form>
                </div>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;">Missing Parameters!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            </section>
        </div>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <style>
        textarea{
            min-height: 120px !important;
        }
    </style>
    <script>
        $(document).ready(function(e) {

        <?php
        //picks up data from the db and asigns values to JS variables
            if ($edit) {
            echo 'detid=' . $id . ';';
            echo 'selrisk=' . $datainfo["as_risk"] . ';';
            echo 'selhazard=' . $datainfo["as_hazard"] . ';';
            echo 'cathazard=' . $datainfo["as_risk"] . ';';
            echo 'riskType=' . $assessment["as_type"] . ';';
            } else {
            echo 'detid=-1;';
            echo 'selrisk=-1;';
            echo 'selhazard=-1;';
            echo 'cathazard=-1;';
            echo "selcontrol=-1;";
            echo 'riskType=' . $assessment["as_type"] . ';';
            }

        ?>

        $(function() {
            $("#date").datepicker();
        });

        $("#btn_cancel").click(function(e) {
            $(location).attr("href", "assessment.php?id=<?php echo $assessment['idassessment']; ?>");
        });


        $("#risk_div").load('../rs/controller/assessment.php?action=listrisks&type=' + riskType + '&selected=' + selrisk);
        $("#hazard_div").load('../rs/controller/assessment.php?action=listhazards&cat=' + cathazard + '&selected=' + selhazard);
        $("#treatments").load('../rs/controller/assessment.php?action=listtreat&id=' + detid);
        $("#controls").load('../rs/controller/assessment.php?action=listcontrols&id=' + detid);
        $("#rating").load('../rs/controller/assessment.php?action=rating&likelihood=' + $("#likelihood").val() + '&consequence=' + $("#consequence").val());

        $("#risk_div").change(function(e) {

            $("#hazard_div").load('../rs/controller/assessment.php?action=listhazards&cat=' + $("#risk").val() + '&selected=' + selhazard);

        });

        $("#consequence, #likelihood").change(function(e) {

            $("#rating").load('../rs/controller/assessment.php?action=rating&likelihood=' + $("#likelihood").val() + '&consequence=' + $("#consequence").val());

        });

        $("#btn_addtreatment").click(function(e) {
            if ($("#treatment").val() !== '') {
            $.ajax({
                type: "GET",
                url: "../rs/controller/assessment.php?action=addtreat&descript=" + $("#treatment").val() + '&id=' + detid,
                async: false
            })
            $("#treatments").load('../rs/controller/assessment.php?action=listtreat&id=' + detid);
            $("#treatment").val("");
            }
        });

        $("#btn_addcontrol").click(function(e) {
            if ($("#control").val() !== '') {
                $.ajax({
                    type: "GET",
                    url: "../rs/controller/assessment.php?action=addcontrol&descript=" + $("#control").val() + '&id=' + detid,
                    async: false
                })
                $("#controls").load('../rs/controller/assessment.php?action=listcontrols&id=' + detid);
                $("#control").val("");    
                // alert('works');
            }
        });

        $("#existing_ct").change(function(e) {
            if ($(this).val() !== '-1') {
            $("#control").prop("disabled", true);
            $("#btn_addcontrol").prop("disabled", true);
            $.ajax({
                type: "GET",
                url: "../rs/controller/assessment.php?action=addlibcontrol&id=" + $(this).val() + '&det=' + detid,
                async: false
            })
            // $("#controls").load('../rs/controller/assessment.php?action=listcontrols&id=' + detid);
            // $("#control").val("");
            }
            if ($(this).val() === '-1') {
            $("#control").prop("disabled", false);
            $("#btn_addcontrol").prop("disabled", false);
            }
        });

        $("#existing_tr").change(function(e) {
            if ($("#exisitng_tr").val() !== '-1') {
            $.ajax({
                type: "GET",
                url: "../rs/controller/assessment.php?action=addlibtreat&id=" + $("#existing_tr").val() + '&det=' + detid,
                async: false
            })
            $("#treatments").load('../rs/controller/assessment.php?action=listtreat&id=' + detid);
            $("#treatment").val("");
            $("#existing_tr").val("-1");
            }
        });

        $("#btn_viewdet").click(function(e) {
            $(location).attr("href", "assessment.php?id=<?php echo $assessment['idassessment']; ?>");
        });


        });

        function del(what, id) {

        if (what == "treatment") {
            $.ajax({
            type: "GET",
            url: "../rs/controller/assessment.php?action=deletetreat&id=" + id,
            async: false
            })
            $("#treatments").load('../rs/controller/assessment.php?action=listtreat&id=' + detid);
        }

        if (what == "control") {
            $.ajax({
            type: "GET",
            url: "../rs/controller/assessment.php?action=deletecontrol&id=" + id,
            async: false
            })
            $("#controls").load('../rs/controller/assessment.php?action=listcontrols&id=' + detid);
        }

        }
    </script>

</body>

</html>