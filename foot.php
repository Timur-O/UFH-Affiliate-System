<?php
/**
 * Declare variables imported from config.php
 *
 * @var $companyName string The company name
 * @var $affiliateTOSLink string The URL to the Affiliate TOS
 */
if (basename($_SERVER["SCRIPT_FILENAME"]) == 'index.php') {
  echo "<footer class='footerlogin'>";
} else {
  echo "<footer>";
}
?>
  <div class="row">
    <div class="left-align col s4">Copyright © <?php echo $companyName;?></div>
    <div class="center col s4"><a href="<?php echo $affiliateTOSLink; ?>" target="_blank">Affiliate Program TOS</a></div>
    <div class="right-align col s4">Designed and Developed by <a href="https://github.com/Timur-O">Timur O</a></div>
  </div>
</footer>