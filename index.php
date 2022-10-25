<?php
// INSERT INTO (`title`, `description`) VALUES ('This is first note', 'this is description');
//Database Credentials
$db_server = "localhost";
$db_username = "php";
$db_password = "PhP@123#";
$db_name = "php";

//Create Connection to Database
$db_connect = mysqli_connect($db_server, $db_username, $db_password, $db_name);

$insert = false; $update = false; $delete = false;


if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST['note_no_edit'])){
    //Update the record
    $note_title_edit = $_POST["note_title_edit"];
    $note_desc_edit = $_POST["note_desc_edit"];
    $note_no_edit = $_POST["note_no_edit"];
    
    $sql_edit = "UPDATE `crud app` SET `title` = '$note_title_edit', `description` = '$note_desc_edit' WHERE `crud app`.`sno` = $note_no_edit";

    $result_edit = mysqli_query($db_connect, $sql_edit);
    
    if($result_edit){
      $update = true;
    }
  }
  //Create new record
  else{
    $note_title = $_POST["note_title"];
    $note_desc = $_POST["note_desc"];
    
    $sql_new = "INSERT INTO `crud app` (`title`, `description`) VALUES ('$note_title', '$note_desc')";

    $result = mysqli_query($db_connect, $sql_new);
    
    if($result){
        $insert = true;
      }
    }
  }
  //Delete record
  if(isset($_GET['delete'])){
    $del_no = $_GET['delete'];
    $sql_del = "DELETE FROM `crud app` WHERE `sno` = $del_no";

    $result_del = mysqli_query($db_connect, $sql_del);

    if($result_del){
      $delete = true;
    }
  }
  
  ?>
<?php include 'header.php'; ?>

<?php
    if($insert){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Note has been added successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
    if($update){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Note has been edited successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
    if($delete){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Note has been deleted successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
?>

<div class="row form my-5">

  <h2 class="title">Add a Note</h2>
  <form action="/crud/index.php" method="POST">
    <div class="mb-3">
      <label for="note_title" class="form-label">Note Title</label>
      <input type="text" class="form-control" id="note_title" name="note_title" required>
    </div>
    <div class="mb-3">
      <label for="note_desc" class="form-label">Note Description</label>
      <textarea class="form-control" name="note_desc" id="note_desc" cols="30" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save Note</button>
  </form>
</div>

<div class="container">

  <?php

?>

  <!-- Table -->
  <table id="myTable" class="table">
    <thead>
      <tr>
        <th scope="col">S. No.</th>
        <th scope="col">Title</th>
        <th scope="col">Description</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
          $sql_table_select = "SELECT * FROM `crud app`";
          $result = mysqli_query($db_connect, $sql_table_select);
          $s_no = 1;
          while($row = mysqli_fetch_assoc($result)){
              echo '<tr>
              <th scope="row">'. $s_no .'</th>
              <td>'. $row["title"] .'</td>
              <td>'. $row["description"] .'</td>
              <td><button class="edit_btn btn btn-primary btn-sm" id="'.$row["sno"].'">Edit</button> 
                  <button class="del_btn btn btn-primary btn-sm" id="d'.$row["sno"].'">Delete</button>
              </td>
              </tr>';
              $s_no++;
          }
        ?>
    </tbody>
  </table>
</div>

<!-- Edit Modal -->
<form action="/crud/index.php" method="POST">
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Note</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="note_no_edit" id="note_no_edit">
          <div class="mb-3">
            <label for="note_title_edit" class="form-label">Note title</label>
            <input type="text" class="form-control" id="note_title_edit" name="note_title_edit" required>
          </div>
          <div class="mb-3">
            <label for="note_desc_edit" class="form-label">Note Description</label>
            <textarea class="form-control" name="note_desc_edit" id="note_desc_edit" cols="30" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer d-block mr-auto">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</form>
  <!-- Modal end -->

<script>
  // Edit record
  let edits = document.getElementsByClassName('edit_btn');
  Array.from(edits).forEach((element) => {
    element.addEventListener('click', (e) => {
      let table_tr = e.target.parentNode.parentNode;
      let note_title_td = table_tr.getElementsByTagName('td')[0].innerText;
      let note_desc_td = table_tr.getElementsByTagName('td')[1].innerText;
      note_title_edit.value = note_title_td;
      note_desc_edit.value = note_desc_td;
      note_no_edit.value = e.target.id;
      $('#editModal').modal('toggle');
    })
  })

  //Delete record
  let dels = document.getElementsByClassName('del_btn');
  Array.from(dels).forEach((element) => {
    element.addEventListener('click', (e) => {
      note_no_del = e.target.id.substr(1,);
      if (confirm("Are you sure!")) {
        window.location = `/crud/index.php?delete=${note_no_del}`;
      }
    })
  })
</script>

<?php include 'footer.php';?>