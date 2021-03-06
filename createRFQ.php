<!DOCTYPE html>
<html lang="en">
    <head>
      <meta charset="utf-8" />
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" 
      integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" 
      crossorigin="anonymous">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" />
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
      <link rel="stylesheet" href="./index.css" />
      <title>Request for Quote</title>
      <?php
        require 'auth.php';

        $conn = new mysqli($servername, $username, $password, $username);

        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }
      ?>
    </head>
    <body>
      <?php 

        session_start();
        $accountNumber = $_SESSION['accountNumber'];

        $repEmail = $repPassword = $repEmailErr = $repPasswordErr = "";

        $partId = $partIdErr = $quantity = $quantityErr = $date = $dateErr = "";

        $rfqId = "";

        $error = $feedback = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          /*************** Form Validation and Populating Variables **************/
            if (empty($_POST["partId"])) {
              $partIdErr = "Part Id is required.";
            } else {
              $partId = test_input($_POST["partId"]);
            }

            if (empty($_POST["quantity"])) {
              $quantityErr = "Quantity is required.";
            } else {
              $quantity = test_input($_POST["quantity"]);
            }

            if (empty($_POST["date"])) {
              $dateErr = "Date is required.";
            } else {
              $date = test_input($_POST["date"]);
            }

            if (!empty($accountNumber) && empty($partIdErr) && empty($quantityErr) && empty($dateErr)) {
              $sql ="INSERT INTO RFQ(AccountNumber)
              VALUES ('$accountNumber')";

              mysqli_query($conn, $sql);

              $rfqId = mysqli_insert_id($conn);

              $sql = "INSERT INTO RFQDetail(RFQID, PartID, Quantity, DateRequired) VALUES
              ($rfqId, '$partId', '$quantity', '$date')";

              if (mysqli_query($conn, $sql)) {
                $feedback = "Submitted Request For Quote Succesfully.";
                $error = "";
              } else {
                $error = "Error: " . $sql . "" . mysqli_error($conn);
                $feedback = "";
              }

              
            } else {
              $error = "Please fill in data properly.";
              $feedback = "";
            }
        }

          

        function test_input($data) {
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          $data = str_replace("'", '', $data);
          return $data;
        }

      ?>
      <noscript>You need to enable Javascript to run this app.</noscript>

      <!-- NAVBAR -->
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">RFQ</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="./index.php">Log Out</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./createCustomer.php">Create Customer</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="./createPart.php">Create Part</a><span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./generateReport.php">Generate Report</a>
            </li>
          </ul>
          <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
          </form>
        </div>
      </nav>

      <!-- Form -->
      <main>
        <div class="container">

          <div class="row">
            <div class="col-12">
              <h2 class="center">Create Request For Quote</h2>
            </div>
          </div>

          <form id="rfqForm" method="POST">
            <div class="box">
              <h4 class="center">RFQ</h4>
              <div class="box">
                <div class="form-group">

                  <label for="partId">Part</label>
                  <?php
                    $sql = "SELECT PartID, Name FROM Inventory";
                    $result = $conn->query($sql);

                    echo '<select class="form-control" name="partId" form="rfqForm">';
                    
                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        echo '<option value="' .$row['PartID'].'">'.$row['Name'].'</option>';
                      }
                    }

                    echo '</select>';
        
                    $conn = null;
                  ?>
                  <span class="error"><?php echo $partIdErr;?></span>
                </div>

                <div class="form-group">
                  <label for="quantity">Quantity</label>
                  <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $quantity;?>">
                  <span class="error"><?php echo $quantityErr;?></span>
                </div>

                <div class="form-group">
                  <label for="date">Date Required</label>
                  <input type="date" class="form-control" id="date" name="date" value="<?php echo $date;?>" min="<?php echo date("d/m/Y") ?>">
                  <span class="error"><?php echo $dateErr;?></span>
                </div>
              </div>

              <div class="box center">
                <span class="feedback"><?php echo $feedback;?></span>
                <span class="error"><?php echo $error; ?></span>
                <div class="row">
                  <div class="col-6 center">
                    <button type="reset" name="cancel" class="btn btn-secondary btn-lg">Cancel</button>
                  </div>

                  <div class="col-6 center">
                    <button type="submit" form="rfqForm" class="btn btn-primary btn-lg">Create</button>
                  </div>
                </div>
              </div>
            </div>
          </form>

        </div>
	    </main>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" 
    integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" 
    crossorigin="anonymous"></script>
    </body>
</html>
