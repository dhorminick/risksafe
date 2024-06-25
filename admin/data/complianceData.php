<?php 
    session_start();
    $file_dir = '../../';
    $company_id = $_SESSION["company_id"];
    // Include pagination library file 
    include $file_dir.'layout/pagination.class.php'; 
    
    // Include database configuration file 
    require $file_dir.'layout/dbConfig.php'; 

if(isset($_POST['page'])){ 
     
    // Set some useful configuration 
    $offset = !empty($_POST['page'])?$_POST['page']:0; 
    $limit = 10; 
     
    // Set conditions for column sorting 
    $sortSQL = ' ORDER BY idcompliance DESC'; 
    if(!empty($_POST['coltype']) && !empty($_POST['colorder'])){ 
        $coltype = $_POST['coltype']; 
        $colorder = $_POST['colorder']; 
        $sortSQL = " ORDER BY $coltype $colorder"; 
    } 
     
    // Count of all records 
    $query   = $db->query("SELECT COUNT(*) as rowNum FROM as_compliancestandard WHERE c_id = '$company_id'"); 
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
    $query = $db->query("SELECT * FROM as_compliancestandard WHERE c_id = '$company_id' $sortSQL LIMIT $offset,$limit"); 
?> 
    <!-- Data list container --> 
    <table class="table table-striped sortable"> 
                        <thead> 
                            <tr> 
                                <th scope="col" class="sorting" style='width: 5%;' coltype="idcompliance" colorder="">S/N</th>
                                <th scope="col" class="sorting" style='width: 34%;' coltype="com_compliancestandard" colorder="">Compliance Task or Obligation</th>
                                <th scope="col" class="sorting" style='width: 30%;' coltype="action" colorder="">Action</th>
                                <th scope="col" class="sorting" style='width: 10%;' coltype="frequency" colorder="">Status</th>
                                <th scope="col" class="sorting" style='width: 21%;' coltype="idcompliance" colorder="">...</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php 
                            if($query->num_rows > 0){ $i = 0;
                                while($item = $query->fetch_assoc()){ $i++;
                                    
                                    $viewLink = 'compliance-details?id='.$item["compli_id"].'" data-toggle="tooltip" title="View Compliance" data-placement="right"';
                                    $editLink = 'edit-compliance?id='.$item["compli_id"].'" data-toggle="tooltip" title="Edit Compliance" data-placement="right"';
                                    $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="compliance" data-id="'.$item["compli_id"];
                                    $downloadLink = 'download?download=compliance&file=xls&id='.$item["compli_id"].'" data-toggle="tooltip" title="Download Compliance" data-placement="left"';
                                    
                                    if($item['type'] == 'imported'){$c_control = $item['imported_controls'];}else{$c_control = $item['com_controls'];} if($c_control == '' || $c_control == null){$c_control = 'None Documented!!';}
                                    if($item["co_status"] == null || $item["co_status"] == ''){$item["co_status"] = 'Un-Assessed';}
                            ?> 
                                <tr> 
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo mb_strimwidth(ucwords($item["com_compliancestandard"]), 0, 70, "..."); ?></td>
                                    <th><?php echo mb_strimwidth(ucwords($c_control), 0, 70, "..."); ?></th>
                                    <td><?php echo ucwords($item["co_status"]); ?></td>
                                    <td>
                                        <a href="<?php echo $viewLink; ?>" class="action-icons btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i></a>
                                        <a href="<?php echo $editLink; ?>" class="action-icons btn btn-info btn-action mr-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?php echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>
                                        <a href="<?php echo $downloadLink; ?>" class="action-icons btn btn-success btn-action "><i class="fas fa-download"></i></a>
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