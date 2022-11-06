<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

  <head>
    <?php
        /**
         * Declare variables imported from config.php
         *
         * @var $conn mysqli The MySQL Database Connection Variable
         * @var $affiliateTableName string The name of the table containing information about affiliates
         * @var $userTableName string The name of the table containing user information
         * @var $primaryKeyColumn string The name of the column containing the IDs
         * @var $currency string The currency
         * @var $websiteURL string The URL of the website
         */
        include 'head.php';
    ?>

    <title>Overview - Affiliate Panel</title>
  </head>

  <body>
    <!-- Include the Nav into the page -->
    <?php include 'nav.php';?>

    <div class="main">
      <!-- Button to show/hide menu -->
      <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>

      <?php
        $sql = "SELECT `clicks`, `conversions`, `commissionBalance` FROM `$affiliateTableName` WHERE `affiliateID` = {$_SESSION['userRefCode']}";
        $result = $conn->query($sql)->fetch_assoc();
        $numberOfClicks = $result['clicks'];
        $numberOfConversions = $result['conversions'];
        $currentCommissionValue = $result['commissionBalance'];

        $sql = "UPDATE `$userTableName` SET `lastLogin` = now() WHERE `$primaryKeyColumn` = {$_SESSION['userRefCode']}";
        $conn->query($sql) or die($conn->error);

        cloudflareIPRewrite();

        if (is_null($result)) {
          $sql = "INSERT INTO  `$affiliateTableName` (`affiliateID`, `clicks`, `conversions`, `commissionBalance`, `payoutEmail`, `firstLoginIP`, `firstLoginProxyIP`, `lastLoginIP`) VALUES ({$_SESSION['userRefCode']}, 0, 0, 0, 'Please set a payout email...', '{$_SERVER['REMOTE_ADDR']}', '{$_SERVER['HTTP_X_FORWARDED_FOR']}', '{$_SERVER['REMOTE_ADDR']}')";
          $conn->query($sql) or die($conn->error);

          $sql = "SELECT `clicks`, `conversions`, `commissionBalance` FROM `$affiliateTableName` WHERE `affiliateID` = '{$_SESSION['userRefCode']}'";
          $result = $conn->query($sql)->fetch_assoc();
          $numberOfClicks = $result['clicks'];
          $numberOfConversions = $result['conversions'];
          $currentCommissionValue = $result['commissionBalance'];
        } else {
          $sql = "UPDATE `$affiliateTableName` SET `lastLoginIP` = '{$_SERVER['REMOTE_ADDR']}' WHERE `affiliateID` = '{$_SESSION['userRefCode']}'";
          $conn->query($sql);
        }
        
        function cloudflareIPRewrite() {
          if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $cf_ip_ranges = array(
              "173.245.48.0/20",
              "103.21.244.0/22",
              "103.22.200.0/22",
              "103.31.4.0/22",
              "141.101.64.0/18",
              "108.162.192.0/18",
              "190.93.240.0/20",
              "188.114.96.0/20",
              "197.234.240.0/22",
              "198.41.128.0/17",
              "104.16.0.0/12",
              "162.158.0.0/15",
              "172.64.0.0/13",
              "131.0.72.0/22",
            );
            foreach ($cf_ip_ranges as $range) {
              if (ip_in_range($_SERVER['REMOTE_ADDR'], $range)) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
                break;
              }
            }
          }
        }
        
        function ip_in_range($ip, $range) {
          $rangeArray = array();
          $range = explode('/', $range);
          $rangeArray[0] = (ip2long($range[0])) & ((-1 << (32 - (int)$range[1])));
          $rangeArray[1] = (ip2long($range[0])) + pow(2, (32 - (int)$range[1])) - 1;
          return (ip2long($ip) >= $rangeArray[0]) && (ip2long($ip) <= $rangeArray[1]);
        }

      ?>

      <div class="row rowtoppadded2">
      <?php
        $sql = "SELECT * FROM `announcements`";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
          if ($row["location"] == "affdash") {
            echo '<div class="announcement">
              <h6>' . $row["title"] . '</h6>
              <p>
              ' . $row["text"] . '
              </p>
              ' . $row["extra"] . '
            </div>';
          }
        }
      ?>
        <div class="col m3 s12">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Total Clicks</span>
              <h5><?php echo $numberOfClicks; ?></h5><p> Clicks</p>
            </div>
          </div>
        </div>
        <div class="col m3 s12">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Total Conversions</span>
              <h5><?php echo $numberOfConversions;?></h5><p> Conversions</p>
            </div>
          </div>
        </div>
        <div class="col m3 s12">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Conversion Rate</span>
              <?php 
                if ($numberOfClicks == 0) {
                  $convDivClick = 0;
                } else {
                  $convDivClick = intval($numberOfConversions) / intval($numberOfClicks);
                }
              ?>
              <h5><?php echo round(($convDivClick * 100), 2);?></h5><p>Percent (%)</p>
            </div>
          </div>
        </div>
      <div class="col m3 s12">
        <div class="card">
          <div class="card-content">
            <span class="card-title">Commission Amount (Pending)</span>
            <h5><?php echo $currentCommissionValue;?></h5><p> <?php echo $currency?></p>
          </div>
        </div>
      </div>
    </div>

      <div class="row">
        <div class="col s12">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Your Referral Link</span>
                <input type="text" disabled value="<?php echo $websiteURL . '?ref=' . $_SESSION['userRefCode']; ?>">
            </div>
          </div>
        </div>
      </div>

      <div class="row" style="text-align: center;">
        <?php include 'ad_inserter.php'; insertAds('affovw', false); ?>
      </div>

    </div>
    <?php include 'foot.php';?>
  </body>
</html>
