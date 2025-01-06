<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Include database config
include 'config/db.php';

// Handle preflight requests (CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get HTTP method (GET, POST, etc.) and requested endpoint
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = rtrim($_SERVER['REQUEST_URI'], '/');
$urls = explode('/', $requestUri);

$key = $urls[2] ?? ''; // Extract the endpoint (e.g., 'login', 'tokenverify', etc.)

// Main routing logic
switch ($key) {

        // Login endpoint
    case 'login':
        if ($method == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true); // Get input data
            include 'api/login.php';
            if (isset($data['email'], $data['password'])) {
                login($data['email'], $data['password'], $urls[3]);
            } else {
                echo json_encode(['status' => false, 'message' => 'Invalid input. Missing email or password.']);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'Method not allowed. Use POST for login.']);
        }
        break;

        // Token verification endpoint
    case 'tokenverify':
        if ($method == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true); // Get input data
            include 'api/check_token.php';
            if (isset($data['data']['userID'], $data['data']['userRole'])) {
                $role = $data['data']['userRole'];
                $userID = $data['data']['userID'];
                checkToken($userID, $role);
            } else {
                echo json_encode(['status' => false, 'message' => 'Invalid input. Missing userID or role.']);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'Method not allowed. Use POST for token verification.']);
        }
        break;

        // Properties endpoint
    case 'properties':
        include 'api/properties.php';
        $action = $urls[3] ?? '';
        $data = json_decode(file_get_contents("php://input"), true);
        if ($method == "POST" || $method == "GET") {
            switch ($action) {
                case 'getproperty':
                    getProperty();
                    break;
                case 'addflat':
                    addFlat($data);
                    break;
                case 'editflat':
                    editFlat($data);
                    break;
                case 'deleteflat':
                    deleteFlat($data);
                    break;
                case 'addproperty':
                    addproperty($data);
                    break;
                case 'editproperty':
                    editproperty($data);
                    break;
                case 'deleteproperty':
                    deleteproperty($data);
                    break;
                default:
                    echo json_encode(['status' => false, 'message' => 'Invalid action for properties.']);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'Invalid Method for properties.']);
        }
        break;

        // Payment method endpoint
    case 'paymentmethod':
        include 'api/paymentMethod.php';
        if ($method == "GET") {
            if (isset($urls[3])) getPaymentMethod($urls[3]);
            else echo json_encode(['status' => false, 'message' => 'No id provided.']);
        } else if ($method == "POST") updatePaymentmethod($urls[3]);
        else echo json_encode(['status' => false, 'message' => 'Invalid Method for payment method.']);
        break;

        // Payment history endpoint
    case 'paymenthistory':
        if ($method == "GET") {
            include 'api/ownerPaymentHistory.php';
            if (isset($urls[3])) fetchpaymentHistory($urls[3]);
            else echo json_encode(['status' => false, 'message' => 'No owner id provided.']);
        } else echo json_encode(['status' => false, 'message' => 'Invalid Method for payment history.']);
        break;

        // Tenants endpoint
    case 'tenants':
        include 'api/tenants.php';
        if ($method == "GET") {
            if (isset($urls[3])) {
                switch ($urls[3]) {
                    case 'delete':
                        if (isset($urls[4])) removeTenant($urls[4]);
                        else echo json_encode(['status' => false, 'message' => 'No tenant ID provided for deletion.']);
                        break;
                    case 'free':
                        if (isset($urls[4])) freeTenant($urls[4]);
                        else echo json_encode(['status' => false, 'message' => 'No tenant ID provided to free.']);
                        break;
                    case 'add':
                        if (isset($urls[4], $urls[5])) {
                            addTenantToFlat($urls[4], $urls[5]);
                        } else {
                            echo json_encode(['status' => false, 'message' => 'Missing flat ID or tenant ID']);
                        }
                        break;
                    case 'get':
                        getTenants($urls[4]);
                        break;
                    default:
                        echo json_encode(['status' => false, 'message' => 'Invalid operation for tenants.']);
                }
            } else {
                getAllTenant();
            }
        } else if ($method == "POST") {
            if (isset($urls[3]) && $urls[3] == 'add') {
                $data = json_decode(file_get_contents("php://input"), true);
                if (isset($data['name'], $data['email'])) {
                    $flatId = isset($data['flat_id']) ? $data['flat_id'] : null;
                    addTenant($data['name'], $data['email'], $flatId);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Missing required fields for adding tenant.']);
                }
            } else {
                echo json_encode(['status' => false, 'message' => 'Invalid operation for tenants.']);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'Invalid method for tenants.']);
        }
        break;

        // Fetch pending payments endpoint
    case 'fetchPendingPayments':
        if ($method == "GET") {
            include 'api/fetchPendingPayment.php';
            getPendingPayments();
        }
        break;

        // Payment stats endpoint
    case 'paymentstat':
        include 'api/paymentStat.php';
        if ($method == "GET") {
            fetchPaymentStat();
        } else {
            echo json_encode(['status' => false, 'message' => 'Invalid method for payment statistics.']);
        }
        break;

    case 'sendemail':
        if ($method == "POST") {
            include 'sendEmail.php';
        } else {
            echo json_encode(['status' => false, 'message' => 'Invalid method for Email.']);
        }
        break;


    case 'tenant-profile':
        if ($method == "GET") {
            include 'api/tenantsProfile.php';
            getProfile($urls[3]);
        } else if ($method == "POST") {
            include 'api/tenantsProfile.php';
            $data = json_decode(file_get_contents("php://input"), true);
            updateProfile($urls[3], $data);
        } else {
            echo json_encode(['status' => false, 'message' => 'Invalid method for Tenant Profile.']);
        }

        break;

    case 'support':
        if ($method == 'POST') {
            include 'api/support.php';
            $data = json_decode(file_get_contents("php://input"), true);
            storeSupport($urls[3], $data);
        }
        else if($method=='GET'){
            include 'api/support.php';
            getSUpport($urls[3]);
        }
        break;

    case 'make-payment':
        include 'api/payment.php';
        getPaymentMethods();
        break;

    //paid but not confirmed
    case 'pending-payments':
        include 'api/fetchPendingPayment.php';
        if($method=="GET"){
            if (isset($urls[3])) { 
                getPendingPaymentsforPending($urls[3]);
             }else { 
                echo json_encode(['status' => false, 'message' => 'Tenant ID is required.']);
            }
        }
        else if($method=="POST"){
            $data = json_decode(file_get_contents("php://input"), true);
            storePendingPayment($data);
        }
        break;

    case 'payments':
        include 'api/payments.php';
        // Fetch tenant ID from query parameters
        if (isset($urls[3])) {
            $tenantId = $urls[3];
            $limit = isset($urls[4]) ? $urls[4] : null;
            getRecentTransactions($tenantId, $limit);
        } else {
            echo json_encode(['status' => false, 'error' => 'Tenant ID is required']);
        }
        break;

    case 'verifypayment':
        include 'api/verifypayment.php';
        if($method=="GET"){
            if($urls[3]=="getnumber"){
                getNumber();
            }
            else if($urls[3]=="getpayments"){
                getPayments();
            }
            else if($urls[3]=="approve"){
                approvePayment($urls[4]);
            }
            else if($urls[3]=="reject"){
                rejectPayment($urls[4]);
            }
        }
        break;
        // Default case for invalid endpoints
    default:
        echo json_encode(['status' => false, 'message' => 'Invalid endpoint.']);
        break;
}

// Close the database connection
$pdo = null;
