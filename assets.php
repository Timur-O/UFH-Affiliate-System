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
         * @var $websiteURL string The URL of the website affiliates are redirecting to
         * @var $assetsPath string The path where the assets to help advertise can be found
         * @var $companyName string The name of the company
         */
        include 'head.php';
    ?>

    <title>Assets - Affiliate Panel</title>
  </head>

  <body>
    <!-- Include the Nav into the page -->
    <?php include 'nav.php';?>

    <div class="main">
      <!-- Button to show/hide menu -->
      <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    
      <div class="row">
        <div class="col s12">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Your Refferal Link</span>
                <input type="text" disabled value="<?php echo $websiteURL . '?ref=' . $_SESSION['userRefCode']; ?>">
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <?php
          $images = glob($assetsPath . "/*.webp");
          $videos = glob($assetsPath . "/*.mp4");
          if ((sizeof($images) == 0) && (sizeof($videos) == 0)) {
            echo '<p class="red-text center">No assets available yet. Contact the support of ' . $companyName . '</p>';
          }
        ?>
        <div class="cards-container col s12">
          <?php
            foreach($images as $image) {
              // echo '<div class="col s12 m6">';
                echo '<div class="card">';
                  echo'<div class="card-image">';
                    echo '<img class="assetImage" src="' . $image . '" />';

                    list($width, $height) = getimagesize($image);

                  echo '</div>';
                  echo '<div class="card-content">';
                    echo '<span class="card-title">' . $width . 'x' . $height . '</span>';
                    echo '<input class="imageURL" readonly value="//' . $_SERVER['HTTP_HOST'] . '/' . $image . '">';
                  echo '</div>';
                echo '</div>';
              // echo '</div>';
            }
            $count = 1;
            foreach($videos as $video) {
              // echo '<div class="col s12 m6">';
                echo '<div class="card">';
                  echo'<div class="card-image">';
                    echo '<video width="720" height="480" controls>';
                      echo '<source src="' . $video . '" type="video/mp4">';
                      echo 'Your browser does not support the video tag.';
                    echo '</video>';
                  echo '</div>';
                  echo '<div class="card-content">';
                    echo '<span class="card-title">GIF/Video ' . $count . ' - 1080p</span>';
                    echo '<input class="imageURL" readonly value="//' . $_SERVER['HTTP_HOST'] . '/' . $image . '">';
                  echo '</div>';
                echo '</div>';
              // echo '</div>';
              $count++;
            }
          ?>
        </div>
      </div>
      
    </div>

    <?php include 'foot.php';?>

  </body>

</html>
