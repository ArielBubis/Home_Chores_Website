<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chores Inc | Main</title>
  <link rel="icon" type="image/x-icon" href="img/logo.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="style/style.css">
</head>

<body>
  <!-- import navbar from header.php -->
  <?php require_once('header.php');?>

  <div class="container all_style">
    <div class="row">
      <div class="col-12">
        <h3 class="company_title">Chores:</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="table-responsive bg-light">
          <table class="table text-center align-middle mb-0" style="border-radius: 10px;">
            <thead class="bg-light">
              <tr>
                <th class="text-center">List title</th>
                <th class="text-center">Due date</th>
                <th class="text-center">User assigned</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <p class="fw-bold mb-1"><a href="choreslist.php">House Chores</a></p>
                </td>
                <td class="text-center">
                  <p class="text-break mb-1">07/06/2024</p>
                </td>
                <td class="text-center">
                  <div class="d-flex flex-column align-items-center justify-content-center">
                    <img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px"
                      class="img-fluid rounded-circle mb-2 d-none d-sm-block" />
                    <p class="mb-1">Bro</p>
                  </div>
                </td>
                <td class="text-center">
                  <span class="badge text-wrap text-bg-danger" style="height: 20px;">
                    <p class="d-none d-sm-block">Not Finished</p>
                  </span>
                </td>
              </tr>
              <tr>
                <td>
                  <p class="fw-bold mb-1"><a href="choreslist.php">Shopping</a></p>
                </td>
                <td class="text-center">
                  <p class="text-break mb-1">08/06/2024</p>
                </td>
                <td class="text-center">
                  <div class="d-flex flex-column align-items-center justify-content-center">
                    <img src="https://mdbootstrap.com/img/new/avatars/6.jpg" alt="" style="width: 45px; height: 45px"
                      class="img-fluid rounded-circle mb-2 d-none d-sm-block" />
                    <p class="mb-1">Princessa</p>
                  </div>
                </td>
                <td class="text-center">
                  <span class="badge text-wrap text-bg-success" style="height: 20px;">
                    <p class="d-none d-sm-block">Finished</p>
                  </span>
                </td>
              </tr>
              <tr>
                <td>
                  <p class="fw-bold mb-1"><a href="choreslist.php">Car stuff</a></p>
                </td>
                <td class="text-center">
                  <p class="text-break mb-1">09/06/2024</p>
                </td>
                <td class="text-center">
                  <div class="d-flex flex-column align-items-center justify-content-center">
                    <img src="https://mdbootstrap.com/img/new/avatars/7.jpg" alt="" style="width: 45px; height: 45px"
                      class="img-fluid rounded-circle mb-2 d-none d-sm-block" />
                    <p class="mb-1">LolaMarsh</p>
                  </div>
                </td>
                <td class="text-center">
                  <span class="badge text-wrap text-bg-success" style="height: 20px;">
                    <p class="d-none d-sm-block">Finished</p>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
    <div class="text-center">
      <button type="button" class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#newUserModal">
        Add New User
      </button>
    </div>

  </div>
  <div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newUserModalLabel">New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body all_style">
          <form id="newUserForm">
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success">Add user</button>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- import footer from footer.php -->
  <?php require_once('footer.php');?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

</body>

</html>