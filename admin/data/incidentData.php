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
    $sortSQL = ' ORDER BY idincident DESC'; 
    if(!empty($_POST['coltype']) && !empty($_POST['colorder'])){ 
        $coltype = $_POST['coltype']; 
        $colorder = $_POST['colorder']; 
        $sortSQL = " ORDER BY $coltype $colorder"; 
    } 
     
    // Count of all records 
    $query   = $db->query("SELECT COUNT(*) as rowNum FROM as_incidents WHERE c_id = '$company_id'"); 
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
    $query = $db->query("SELECT * FROM as_incidents WHERE c_id = '$company_id' $sortSQL LIMIT $offset,$limit"); 
?> 
    <!-- Data list container --> 
    <table class="table table-striped sortable"> 
                        <thead> 
                            <tr> 
                                <th scope="col" class="sorting" coltype="idincident" colorder="">S/N</th>
                                <th scope="col" class="sorting" coltype="idincident" colorder="">Title</th>
                                <th scope="col" class="sorting" coltype="idincident" colorder="">Team or Department</th>
                                <th scope="col" class="sorting" coltype="idincident" colorder="">Status</th>
                                <th scope="col" class="sorting" coltype="idincident" colorder="">Priority</th>
                                <th scope="col" class="sorting" coltype="idincident" colorder="">Date</th>
                                <th scope="col" class="sorting" coltype="idincident">...</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php 
                            if($query->num_rows > 0){ $i = 0;
                                while($item = $query->fetch_assoc()){ $i++;
                                    $viewLink = '?id='.$item["in_id"].'" data-toggle="tooltip" title="View Incident" data-placement="right" class="action-icons btn btn-primary btn-action mr-1"';
                                    $editLink = 'edit-incident?id='.$item["in_id"].'" data-toggle="tooltip" title="Edit Incident" data-placement="right" class="action-icons btn btn-info btn-action mr-1"';
                                    $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="insident" data-id="'.$item["in_id"];
                            ?> 
                                <tr> 
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo ucwords($item['in_title']); ?></td>
                                    <td><?php echo ucwords($item['in_team']); ?></td>
                                    <th><?php echo ucwords($item["in_status"]); ?></th>
                                    <td><?php echo ucwords($item["in_priority"]); ?></td>
                                    <td><?php echo date("m/d/Y", strtotime($item["in_date"])); ?></td>
                                    <td>
                                        <a href="<?php echo $viewLink; ?>" class="action-icons btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i></a>
                                        <a href="<?php echo $editLink; ?>" class="action-icons btn btn-info btn-action mr-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?php echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>
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