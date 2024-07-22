<?php
  // require_once('API/logout.php'); ?>
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Logged Out</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body all_style">
          You successfully logged out from the website.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="closeButton" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

?>

    <div class="modal fade" id="signUpModal" tabindex="-1" aria-labelledby="signUpModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="signUpModalLabel">Sign Up Successful</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body all_style">
            You successfully signed up to the website, please log in to continue.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="closeButton" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>



  
<?php
    function debug_to_console($data) {
      $output = $data;
      if (is_array($output))
          $output = implode(',', $output);
  
      echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
  }?>
