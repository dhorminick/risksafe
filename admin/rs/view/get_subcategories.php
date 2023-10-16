<?php
include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/applicable.php');

$db = new db();
$conn = $db->connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["categoryId"]) && !empty($_POST["categoryId"])) {
    $categoryId = $_POST["categoryId"];
    $query = "SELECT * FROM aml_subcategories WHERE category_id = " . $categoryId;
    $result = mysqli_query($conn, $query);
    $options = '<option value="">Select Subcategory</option>';
    while ($row = mysqli_fetch_assoc($result)) {
      $options .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
    }
    echo $options;
  }

  if($row['id']=="1"){
    ?>
    <script>
          $("subcategory-fields").show();

    </script>
    <?php
  }
}
?>
