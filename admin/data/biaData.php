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
    $sortSQL = ' ORDER BY idbia DESC'; 
    if(!empty($_POST['coltype']) && !empty($_POST['colorder'])){ 
        $coltype = $_POST['coltype']; 
        $colorder = $_POST['colorder']; 
        $sortSQL = " ORDER BY $coltype $colorder"; 
    } 
     
    // Count of all records 
    $query   = $db->query("SELECT COUNT(*) as rowNum FROM as_bia WHERE c_id = '$company_id'"); 
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
    $query = $db->query("SELECT * FROM as_bia WHERE c_id = '$company_id' $sortSQL LIMIT $offset,$limit"); 
?> 
    <!-- Data list container --> 
    <table class="table table-striped sortable"> 
                        <thead> 
                            <tr> 
                                <th scope="col" class="sorting" coltype="idbia" colorder="">S/N</th>
                                <th scope="col" class="sorting" coltype="idbia" colorder="">Activity</th>
                                <th scope="col" class="sorting" coltype="idbia" colorder="">Priority</th>
                                <th scope="col" class="sorting" coltype="idbia" colorder="">Impact</th>
                                <th scope="col" class="sorting" coltype="idbia" colorder="">Time</th>
                                <th scope="col" class="sorting" coltype="idbia" colorder="">...</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php 
                            if($query->num_rows > 0){ $i = 0;
                                while($item = $query->fetch_assoc()){ $i++;
                                    $viewLink = '?id='.$item["bia_id"].'" data-toggle="tooltip" title="View BIA" data-placement="right"';
                                    $editLink = 'edit-bia?id='.$item["bia_id"].'" data-toggle="tooltip" title="Edit BIA" data-placement="right"';
                                    $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="BIA" data-id="'.$item["bia_id"];
                            ?> 
                                <tr> 
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo ucwords($item['bia_activity']); ?></td>
                                    <td><?php echo ucwords($item['bia_priority']); ?></td>
                                    <th><?php echo ucwords($item["bia_impact"]); ?></th>
                                    <td><?php echo ucwords($item["bia_time"]); ?></td>
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