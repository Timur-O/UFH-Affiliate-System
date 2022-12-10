<!DOCTYPE html>
<html lang="en" dir="ltr">

  <head>
    <?php
        /**
         * Declare variables imported from config.php
         *
         * @var $conn mysqli The MySQL Database Connection Variable
         * @var $currency string The currency
         * @var $conversionsTableName string The name of the table containing conversion information
         */
        include 'head.php';
    ?>

    <title>Conversions - Affiliate Panel</title>
  </head>

  <body>
    <!-- Include the Nav into the page -->
    <?php include 'nav.php';?>

    <div class="main">
      <!-- Button to show/hide menu -->
      <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
      
      <?php
        $sql = "SELECT COUNT(*) as 'num' FROM `$conversionsTableName` WHERE `affiliate` = '{$_SESSION['userRefCode']}'";
        $result = $conn->query($sql)->fetch_assoc();
        $numberOfConversions = $result['num'];
        
        if (isset($_GET['page'])) {
          $pageNum = $_GET['page'];
          $offset = ($pageNum - 1) * 10;
        } else {
          $offset = 0;
        }
      ?>

      <div class="row respon-table">

        <div class="col s10 offset-s1">
          <h5 class="center">Your Conversions</h5>
          <hr>
        </div>

        <table id="userstable" class="col s10 offset-s1 centered">
          <thead>
            <th>Date</th>
            <th>Type</th>
            <th>Commission Amount</th>
            <th>Approved</th>
          </thead>

          <tbody>
            <?php
              if ($numberOfConversions > 0) {
                $sql = "SELECT `date`, `type`, `commissionAmount`, `approved` FROM `$conversionsTableName` WHERE `affiliate` = '{$_SESSION['userRefCode']}' LIMIT 10 OFFSET $offset";
                $fullResult = $conn->query($sql);
                
                while ($row = $fullResult->fetch_assoc()) {
                  $commissionDate = $row['date'];
                  $commissionType = $row['type'];
                  $commissionAmount = $row['commissionAmount'];
                  $commissionApproved = $row['approved'];

                  echo "<tr>";
                    echo "<td>" . $commissionDate . "</td>";
                    echo "<td>" . $commissionType . "</td>";
                    echo "<td>" . $commissionAmount . " " . $currency . "</td>";
                    if ($commissionApproved == 1) {
                      echo "<td class='green-text'>Approved</td>";
                    } else if ($commissionApproved == 2) {
                      echo "<td class='red-text'>Rejected</td>";
                    } else {
                      echo "<td>Pending Approval</td>";
                    }
                  echo "</tr>";
                }
              } else {
                echo '<td colspan="4"><p class="red-text">No conversions yet. Visit the <a href="assets.php">assets</a> page to get started.</p></td>';
              }
            ?>
            <tr class="paginator">
              <td colspan="5" class="center">
                <ul class="pagination">
                    <?php
                        include("pagination.php");
                        displayPagination("conversions.php");
                    ?>
                </ul>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
    </div>

    <?php include 'foot.php';?>

  </body>

</html>
