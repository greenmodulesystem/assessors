<?php 
define('DUPLICATE_RECORD', 'Duplicate record already found in the system.');
define('REQUIRED_FIELD', 'Please fill in required fields.');
define('PASSWORD_LENGTH', 'Password should not be less than 6 (six) characters long.');
define('NO_USERNAME_FOUND', 'Username not found.');
define('INVALID_USERNAME_PASSWORD', 'Invalid password.');
define('INVALID_PASSWORD', 'Invalid password.');
define('ACCOUNT_DISABLED', 'Account is currently disabled.');
define('SAVED_SUCCESSFUL', 'Details saved successfully.');
define('RENEW_SUCCESSFUL', 'Application successfully saved.');
define('CONFIRM_RENEWAL', 'Submit this application?');
define('NO_CRYPTO', 'No Cryptographically Secure Random Function Available.');
define('ERROR_API_KEY', 'Error validating security key.');
//added by mel
define('ACCESS_DENIED',"Access denied. User permission disabled.");
define('ACCESS_GRANTED','Access granted. Action successfully executed.');
define('MODULE_NOT_FOUND',"Module access denied.");
define('REQUIRED_DATE', 'Date field requied.');
define('REQUIRED_PARAMETER','Requierd parameter/s missing');
define('OPTION_NOT_FOUND','Option not found in the system');
define('TYPE_NOT_FOUND','Type not found in the system');
define('ERROR_ACCESS_KEY','Error validating security access key');
define('NO_USER','User unidentified');
define('NO_APPLICANT','No applicant selected');
define('INVALID_PARAMETER','Invalid input');
define('UNDEFINED_PATH','Path not found');
// fire
define('HAS_FSIC','Applicant has FSIC');
define('APP_DENIED','Application denied due to uncomplied violation/s');

define('DEFAULT_PASSWORD','Do not use the default password. Please create new password.'  );
define('NOT_MATCH','Your password does not match. Please try again.'  );

function error_text() {
    return ('<span style="position:absolute;top:40%;left:50%;transform: translate(-50%, -50%);text-align:center;">
    <font size="7" color="red" face="Arial Black"><u>ACCESS DENIED</u></font></br><font size="4" color="red"
    face="Arial Black">Your request for this page has been denied.</font></span>');
}