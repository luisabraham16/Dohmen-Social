<?php
    function isNumber($number, $min = 0, $max = 100): bool
    {
        return ($number >= $min and $number <= $max);
    }

    function validateText(string $theStr, string $theID){
        $settings['options']['regexp'] = '/^[A-z]{2,20}$/';
        // $result = filter_input(INPUT_POST, $theID, FILTER_VALIDATE_REGEXP, $settings);
        // return $result;
        //OR
        return(filter_input(INPUT_POST, $theID, FILTER_VALIDATE_REGEXP, $settings))?true:false;
        
        //ORIGINAL VERSION
        // FILTER_VALIDATE_REGEXP, $settings)
        // $validationFilters[$theID]['filter'] = FILTER_VALIDATE_REGEXP;
        //     $validationFilters['first_name']['options']['regexp'] = '/^[A-z]{2,10}$/';
    }

    function isText(string $string, int $min = 0, int $max = 1000): bool
    {
        $length = mb_strlen($string);
        return ($length >= $min and $length <= $max);
    }

    function isEmail($email): bool
    {
        return (filter_var($email, FILTER_VALIDATE_EMAIL)) ? true : false;
    }

    function isPassword($password)
    {
        if ( mb_strlen($password) >= 6                    // Length 6 or more chars
            and preg_match('/[A-Z]/', $password)             // Contains uppercase A-Z
            and preg_match('/[a-z]/', $password)             // Contains lowercase a-z
            and preg_match('/[0-9]/', $password)             // Contains 0-9
        ) {
            return true;                                     // Passed all tests
        }
        return false;                                      // Invalid password
    }

    function sanitizeData(&$userData){
        $userData['first_name']=filter_var($userData['FName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $userData['last_name']=filter_var($userData['LName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $userData['user_name']=filter_var($userData['Username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $userData['email']=filter_var($userData['Email'], FILTER_SANITIZE_EMAIL);


        //no need to sanitize password but before inserting password into DB store its hash and insert that into the DB

    }
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
?>

