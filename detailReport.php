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

        // Getting all of our data from the Create Report Screen and saving to local variables
        session_start();
        $report = $_SESSION["report"];
        $startDate = $_SESSION["startDate"];
        $endDate = $_SESSION["endDate"];
        $content = $_SESSION["content"];
      ?>

      <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
      </script>
    </head>
    <body>
      <?php
        $error = $feedback = "";

        if ($content== "all,RFQ.RFQID,CustomerAccount.CompanyName,Inventory.Name,RFQDetail.Quantity,Inventory.Price") {
          $sql = "SELECT DISTINCT RFQ.RFQID AS 'Rfq Id', CustomerAccount.CompanyName as 'Account Name', Inventory.Name as 'Part Name', RFQDetail.Quantity, RFQDetail.DateRequired
            AS 'Date Required', Inventory.Price  FROM RFQ, CustomerAccount, Inventory, RFQDetail WHERE RFQDetail.RFQID LIKE RFQ.RFQID AND RFQDetail.PartID LIKE
            Inventory.PartID";
        } else {
          $sql = "SELECT DISTINCT " .$content. " FROM RFQ, CustomerAccount, Inventory, RFQDetail WHERE RFQDetail.RFQID LIKE RFQ.RFQID AND RFQDetail.PartID LIKE
            Inventory.PartID";
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
              <h2 class="center">RFQ Detail Report</h2>
            </div>
          </div>

          <div id="report" class="box center">
            <h4>Report</h4>
            <div class="box">
          <table class="table">
          <?php
            $result = $conn->query($sql);
            echo '<thead><tr>';

            if (!$result) {
              $error = 'ERROR: ' . mysqli_error($conn);
              return $error;
            } else {
              $i = 0;
              while($i < mysqli_num_fields($result)) {
                $meta = mysqli_fetch_field($result);
                echo '<th>' .$meta->name . '</th>';
                $i = $i + 1;
              }
            }

            echo '</tr></thead>';

            echo '<tbody>';

            $i = 0;
            while($row = mysqli_fetch_row($result)) {
              echo '<tr>';
                $count = count($row);
                $y = 0;
                while ($y < $count) {
                  $c_row = current($row);
                  echo '<td>' . $c_row . '</td>';
                  next($row);
                  $y = $y + 1;
                }
              echo '</tr>';
              $i = $i + 1;
            }

            echo '</tbody>';
            

            $conn = null;
          ?>
          </table>
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
                  <button type="submit" onclick="printDiv('report')" form="rfqForm" class="btn btn-primary btn-lg">Print</button>
                </div>
              </div>
          </div>
        </div>
	    </main>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" 
    integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" 
    crossorigin="anonymous"></script>
    </body>
</html>
