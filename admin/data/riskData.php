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
    $sortSQL = ' ORDER BY idassessment DESC'; 
    if(!empty($_POST['coltype']) && !empty($_POST['colorder'])){ 
        $coltype = $_POST['coltype']; 
        $colorder = $_POST['colorder']; 
        $sortSQL = " ORDER BY $coltype $colorder"; 
    } 
     
    // Count of all records 
    $query   = $db->query("SELECT COUNT(*) as rowNum FROM as_assessment WHERE c_id = '$company_id'"); 
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
    $_query = $db->query("SELECT * FROM as_assessment WHERE c_id = '$company_id' $sortSQL LIMIT $offset,$limit"); 
?> 
    <!-- Data list container --> 
    <table class="table table-striped sortable"> 
    <thead> 
        <tr> 
            <th scope="col" class="sorting" style='width: 5%;' coltype="idassessment" colorder="">S/N</th>
            <th scope="col" class="sorting" style='width: 23%;' coltype="idassessment" colorder="">Team or Organisation</th>
            <th scope="col" class="sorting" style='width: 23%;' coltype="idassessment" colorder="">Task or Process</th>
            <th scope="col" class="sorting" style='width: 17%;' coltype="idassessment" colorder="">Assessment Type</th>
            <th scope="col" class="sorting" style='width: 10%;' coltype="idassessment" colorder="">Date</th>
            <th scope="col" class="sorting" style='width: 21%;' coltype="idassessment" colorder="">...</th> 
        </tr> 
    </thead> 
    <tbody> 
        <?php 
        if($_query->num_rows > 0){ $i = 0;
                                while($item = $_query->fetch_assoc()){ $i++;
                                    
                                    $as_HasValue = $item["has_values"];
                                    $_editLink = "edit-assessment?id=".$item["as_id"];
                    
                                    $viewLink = 'assessment-details?id='.$item["as_id"].'" data-toggle="tooltip" title="View Assessment" data-placement="right"';
                                    $editLink = $_editLink.'" data-toggle="tooltip" title="Edit Assessment" data-placement="right"';
                                    $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="assessment" data-id="'.$item["as_id"];
                                    // $downloadLink = 'download?download=assessment&file=xls&id='.$item["as_id"].'" data-toggle="tooltip" title="Download Assessment" data-placement="left"';
                                    $downloadLink = 'javascript:void(0);" data-toggle="modal" data-target="#exportModal" export-data="assessment" export-id="'.$item["as_id"];
                            ?> 
                                <tr> 
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo ucwords($item['as_team']); ?></td>
                                    <td><?php echo ucwords($item['as_task']); ?></td>
                                    <th><?php echo ucwords(getIndustryTitle($item["industry"], $con)); ?></th>
                                    <td><?php echo date("m-d-Y", strtotime($item["as_date"])); ?></td>
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