     <span style="color:#09F;"><strong><i class="glyphicon glyphicon-briefcase"></i>&nbsp;&nbsp;Risk Assessment</strong>
    <hr>
    <ul class="nav nav-stacked">
      <ul class="nav nav-stacked collapse in" id="userMenu">
        <li><a href="newassessment.php"><i class="glyphicon glyphicon-briefcase"></i>&nbsp;&nbsp;New Risk Assessment</a></li>
        <li><a href="assessments.php"><i class="glyphicon glyphicon-book"></i>&nbsp;&nbsp;My Risk Assesments</a></li>
        <li><a href="antimonies.php"><i class="glyphicon glyphicon-cd"></i>&nbsp;&nbsp;Anti Money Laundering </a></li>
        <!--<li><a href="#"><i class="glyphicon glyphicon-star"></i>&nbsp;&nbsp;Favorites</a></li>-->
      </ul>
      </li>
    </ul>
    <hr>
    <span style="color:#09F;"><strong><i class="glyphicon glyphicon-off"></i>&nbsp;&nbsp;Compliance Standard</strong>
    <hr>
    <ul class="nav nav-stacked">
      <ul class="nav nav-stacked collapse in" id="userMenu">
       <li><a href="compliances.php"><i class="glyphicon glyphicon-hdd"></i>&nbsp;&nbsp;Compliance Standard</a></li>
        <li><a href="applicables.php"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;&nbsp;Applicable Policy</a></li>
        <li><a href="applicableprocedures.php"><i class="glyphicon glyphicon-tasks"></i>&nbsp;&nbsp;Applicable Procedure</a></li>
      </ul>
      </li>
    </ul>
  <hr>
    <span style="color:#09F;"><strong><i class="glyphicon glyphicon-wrench"></i>&nbsp;&nbsp;Monitoring</strong>
    <hr>
    <ul class="nav nav-stacked">
      <ul class="nav nav-stacked collapse in" id="userMenu">
       <li><a href="audit.php?action=add"><i class="glyphicon glyphicon-edit"></i>&nbsp;&nbsp;New Audit of Control</a></li>
        <li><a href="audits.php"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;&nbsp;My Audits of Control</a></li>
        <li><a href="treatment.php?action=add"><i class="glyphicon glyphicon-plus-sign"></i>&nbsp;&nbsp;New Treatment</a></li>
        <li><a href="treatments.php"><i class="glyphicon glyphicon-paste"></i>&nbsp;&nbsp;My Treatments</a></li>
      </ul>
      </li>
    </ul>
       <hr>
    <span style="color:#09F;"><strong><i class="glyphicon glyphicon-bookmark"></i>&nbsp;&nbsp;Business Continuity</strong>
    <hr>
    <ul class="nav nav-stacked">
      <ul class="nav nav-stacked collapse in" id="userMenu">
       <li> <a href="incidents.php"><i class="glyphicon glyphicon-tags"></i>&nbsp;&nbsp;Incidents</a></li>
        <li> <a href="bias.php"><i class="glyphicon glyphicon-stats"></i>&nbsp;&nbsp;Bussiness Impact Analysis</a></li>
        <li><a href="insurences.php"><i class="glyphicon glyphicon-ok-sign"></i>&nbsp;&nbsp;Insurances</a></li>
      </ul>
      </li>
    </ul>
    <hr>
    <span style="color:#09F;"><strong><i class="glyphicon glyphicon-list"></i>&nbsp;&nbsp;Reports</strong></span>
    <hr>
    <ul class="nav nav-pills nav-stacked">
      <li class="nav-header"></li>
      <li><a href="reports.php"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;&nbsp;Risk Reports</a></li>
      <li><a href="controlreport.php"><i class="glyphicon glyphicon-calendar"></i>&nbsp;&nbsp;Controls Dashboard</a></li>
      <li><a href="incidentreport.php"><i class="glyphicon glyphicon-list"></i>&nbsp;&nbsp;Incident Report</a></li>
      <li><a href="biareport.php"><i class="glyphicon glyphicon-signal"></i>&nbsp;&nbsp;Business Impact Analysis</a></li>
      <li><a href="treatmentreport.php"><i class="glyphicon glyphicon-tasks"></i>&nbsp;&nbsp;Treatment Status Report</a></li>
      
    </ul>
    <hr>
    <span style="color:#09F;"><strong><i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;My Account</strong></span>
    <hr>
    <ul class="nav nav-stacked">
      <li class="nav-header"></li>
      <li><a href="userprofile.php"><i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;My Profile</a></li>
     <?php
     $db = new db();
     $conn = $db->connect();
     $currentUser =  $_SESSION["userid"]; 
     
     $sql = "SELECT role FROM users WHERE iduser = '$currentUser'";
     $result = mysqli_query($conn, $sql);
     
     if (mysqli_num_rows($result) > 0) {
         $row = mysqli_fetch_assoc($result);
         $currentUserRole = $row['role'];
     
         if ($currentUserRole == "superadmin"  ) {
          echo "<li><a href='allusers.php'><i class='glyphicon glyphicon-user'></i>&nbsp;&nbsp;All Users</a></li>";

         }
     }
     ?>      <li><a href="businesscontext.php"><i class="glyphicon glyphicon-modal-window"></i>&nbsp;&nbsp;My Business Context</a></li>
      <li><a href="payments.php"><i class="glyphicon glyphicon-usd"></i>&nbsp;&nbsp;My Payments</a></li>
      <li><a href="instructions.php"><i class="glyphicon glyphicon-list"></i>&nbsp;&nbsp;Instructions</a></li>
      <li><a href="../controller/users.php?action=logout"><i class="glyphicon glyphicon-off"></i>&nbsp;&nbsp;Logout</a></li>
    </ul>
    <br/>