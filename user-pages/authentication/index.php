<?php
session_start();
include('../../utilities/header.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $system->get_validateuser($db, $_POST['username'], $_POST['password']);

    if ($user === 'locked') {
        $_SESSION['error_message'] = "Your account is temporarily locked. Please try again later.";
    } elseif ($user) {
        $_SESSION = [
            'username' => $user['username'],
            'name' => $user['name'],
            'uaid' => $user['uaid'],
            'status' => $user['status'],
            'modid' => $user['modid'],
            'position' => $user['position'],
            'dept_name' => $user['dept_name']  // Adding dept_id to the session
        ];        

        if ($_SESSION['status'] == 1) {
            // Handle specific modid values
            if ($_SESSION['modid'] == 0) {
                header("Location: ../sys-admin/");
            } elseif ($_SESSION['modid'] == 2) {
                header("Location: ../requestor/");
            } elseif ($_SESSION['modid'] == 3) {
                header("Location: ../dept_head/");
            } elseif ($_SESSION['modid'] == 4) {
                header("Location: ../dept_head/");
            } elseif ($_SESSION['modid'] == 5) {
                header("Location: ../purchaser/");
            } elseif ($_SESSION['modid'] == 1 || $_SESSION['modid'] == 6) {  // Corrected condition
                header("Location: ../custodian/");
            }
            exit(); // Ensure the script stops after redirection
        } else {
            $_SESSION['error_message'] = "Your account is inactive.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid username or password.";
    }

    header("Location: ../authentication");
    exit();
}
?>


 <input type="hidden" id="notificationBadge">
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">
                    <a href="">
                        <img src="../../static/dbclogo.png" width="110" height="32" alt="DBPIS" class="navbar-brand-image">
                        DBAMS
                    </a>
                </h2>
                <form action="" method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Password
                        </label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-2">

                    </div>

                    <!-- Display error message from session if it exists -->
                    <?php if (isset($_SESSION['error_message'])) : ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error_message']; ?></div>
                        <?php unset($_SESSION['error_message']); // Clear the message after displaying it ?>
                    <?php endif; ?>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

