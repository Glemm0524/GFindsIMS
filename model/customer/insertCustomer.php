<?php
require_once('../../inc/config/constants.php');
require_once('../../inc/config/db.php');

if (isset($_POST['customerDetailsCustomerFullName'])) {
    // Sanitize and assign variables
    $fullName = htmlentities($_POST['customerDetailsCustomerFullName']);
    $email = htmlentities($_POST['customerDetailsCustomerEmail']);
    $mobile = htmlentities($_POST['customerDetailsCustomerMobile']);
    $phone2 = htmlentities($_POST['customerDetailsCustomerPhone2']);
    $address = htmlentities($_POST['customerDetailsCustomerAddress']);
    $address2 = htmlentities($_POST['customerDetailsCustomerAddress2']);
    $city = htmlentities($_POST['customerDetailsCustomerCity']);
    $district = htmlentities($_POST['customerDetailsCustomerDistrict']);
    $status = htmlentities($_POST['customerDetailsStatus']);

    // Validate form inputs
    if (isset($fullName) && isset($mobile) && isset($address)) {
        if (!preg_match('/^\d{10,15}$/', $mobile)) {
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid mobile number</div>';
            exit();
        }

        if (!empty($phone2) && !preg_match('/^\d{10,15}$/', $phone2)) {
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid number for phone number 2.</div>';
            exit();
        }

        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid email</div>';
            exit();
        }

        if (empty($address)) {
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter Address 1</div>';
            exit();
        }

        if (empty($fullName)) {
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter Full Name.</div>';
            exit();
        }

        // Insert data into database
        try {
            $sql = 'INSERT INTO customer (fullName, email, mobile, phone2, address, address2, city, district, status) 
                    VALUES (:fullName, :email, :mobile, :phone2, :address, :address2, :city, :district, :status)';
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'fullName' => $fullName, 
                'email' => $email, 
                'mobile' => $mobile, 
                'phone2' => $phone2, 
                'address' => $address, 
                'address2' => $address2, 
                'city' => $city, 
                'district' => $district, 
                'status' => $status
            ]);
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Customer added to database</div>';
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Database error: ' . $e->getMessage() . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter all fields marked with a (*)</div>';
        exit();
    }
}
?>
