<?php 
    session_start();
    $file_dir = '../../';
    $company_id = $_SESSION["company_id"];
if(isset($_POST['page'])){ 
    // Include pagination library file 
    include_once $file_dir.'layout/pagination.class.php'; 
     
    // Include database configuration file 
    require_once $file_dir.'layout/dbConfig.php'; 
     
    // Set some useful configuration 
    $offset = !empty($_POST['page'])?$_POST['page']:0; 
    $limit = 10; 
     
    // Set conditions for column sorting 
    $sortSQL = ' ORDER BY idcontrol DESC';
    if(!empty($_POST['coltype']) && !empty($_POST['colorder'])){ 
        $coltype = $_POST['coltype']; 
        $colorder = $_POST['colorder']; 
        $sortSQL = " ORDER BY $coltype $colorder"; 
    } 
     
    // Count of all records 
    $query   = $db->query("SELECT COUNT(*) as rowNum FROM as_auditcontrols WHERE c_id = '$company_id'"); 
    $result  = $query->fetch_assoc(); 
    $rowCount= $result['rowNum']; 
     
    // Initialize pagination class 
    $pagConfig = array( 
        'totalRows' => $rowCount, 
        'perPage' => $limit, 
        'currentPage' => $offset, 
        'contentDiv' => 'dataContainer', 
        'link_func' => 'columnSorting' 
    ); 
    $pagination =  new Pagination($pagConfig); 
 
    // Fetch records based on the offset and limit 
    $query = $db->query("SELECT * FROM as_auditcontrols WHERE c_id = '$company_id' $sortSQL LIMIT $offset,$limit"); 
?> 
    <!-- Data list container --> 
    <table class="table table-striped sortable"> 
    <thead> 
        <tr> 
            <th scope="col" class="sorting" coltype="idcontrol" colorder="">S/N</th>
            <th scope="col" class="sorting" style='width: 30%;' coltype="idcontrol" colorder="">Control</th>
            <th scope="col" class="sorting" coltype="idcontrol" colorder="">Issued On</th>
            <th scope="col" class="sorting" coltype="idcontrol" colorder="">Effectiveness</th>
            <th scope="col" class="sorting" coltype="idcontrol" colorder="">Frequency</th>
            <th scope="col" class="sorting" coltype="idcontrol" colorder="">...</th> 
        </tr> 
    </thead> 
    <tbody> 
        <?php 
        if($query->num_rows > 0){ $i = 0;
            while($item = $query->fetch_assoc()){ $i++;
                switch ($item["con_effect"]) {
                    case 0:
                        $effect="Not selected";
                        break;
                    case 1:
                        $effect="Not effective";
                        break;
                    case 2:
                        $effect="Effective";
                        break;
                }
                function get_Frequency($freq){

                                        if ($freq == 7) {
                                            return "As Required";
                                        } else if ($freq == 1) {
                                            return "Daily Controls";
                                        } else if ($freq == 2) {
                                            return "Weekly Controls";
                                        } else if ($freq == 3) {
                                            return "Fort-Nightly Controls";
                                        } else if ($freq == 4) {
                                            return "Monthly Controls";
                                        } else if ($freq == 5) {
                                            return "Semi-Annually Controls";
                                        } else if ($freq == 6) {
                                            return "Annually Controls";
                                        } else {
                                            return "Un-Assessed";
                                        }
                                    }
                $viewLink = 'audit-details?id='.$item["aud_id"].'" data-toggle="tooltip" title="View Audit" data-placement="right"';
                $editLink = 'edit-audit?id='.$item["aud_id"].'" data-toggle="tooltip" title="Edit Audit" data-placement="right"';
                $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="audit" data-id="'.$item["aud_id"];
                // $downloadLink = 'download?download=audits&id='.$item["aud_id"].'" data-toggle="tooltip" title="Download Audit" data-placement="left"';
                $downloadLink = 'javascript:void(0);" data-toggle="modal" data-target="#exportModal" export-data="audit" export-id="'.$item["aud_id"];
        ?> 
            <tr> 
                <td><?php echo $i; ?></td>
                <td><?php echo ucwords($item["con_control"]); ?></td>
                <td><?php echo date("m/d/Y", strtotime($item["con_date"])); ?></td>
                <td><?php echo ucwords($effect); ?></td>
                <td><?php echo get_Frequency($item["con_frequency"]); ?></td>
                <td>
                    <a href="<?php echo $viewLink; ?>" class="action-icons btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i></a>
                    <a href="<?php echo $editLink; ?>" class="action-icons btn btn-info btn-action mr-1"><i class="fas fa-edit"></i></a>
                    <a href="<?php echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>
                    <a href="<?php echo $downloadLink; ?>" class="export__data action-icons btn btn-success btn-action "><i class="fas fa-download"></i></a>
                </td>
            </tr> 
        <?php 
            } 
        }else{ 
            echo '<tr><td colspan="6">No Records found...</td></tr>'; 
        } 
        ?> 
    </tbody> 
    </table> 
     
    <!-- Display pagination links --> 
    <?php echo $pagination->createLinks(); ?> 
<?php 
} 
?>