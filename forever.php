<?php

/* mysql_connect() */
/* mysql_select_db() */
    $link  =  mysqli_connect ( 'localhost' ,  'root' ,  '808086' ,  'user' );
    $clean = array();
    $mysql = array();
    var_dump($_COOKIE);
    $now = time();
    $salt = 'SHIFLETT';

    list($identifier, $token) = explode(':', $_COOKIE['auth']);

    if (ctype_alnum($identifier) && ctype_alnum($token))
    {
        $clean['identifier'] = $identifier;
        $clean['token'] = $token;
    }else{
        echo "No cookie";
    }

    $mysql['identifier'] = $clean['identifier'];

    $sql = "SELECT username, token, timeout FROM   customer WHERE  identifier = '{$mysql['identifier']}'";

    if ($result = mysqli_query($link,$sql)){
        if (mysqli_num_rows($result)){
            $record = mysqli_fetch_assoc($result);
            if ($clean['token'] != $record['token']){
                echo "Failed Login (wrong token)";
            }elseif ($now > $record['timeout']){
                echo "Failed Login (timeout)";
            }elseif ($clean['identifier'] !=md5($salt . md5($record['username'] . $salt))){
                echo "Failed Login (invalid identifier)";
            }else{
                echo "Successful Login";
            }

        }else{
            echo "Failed Login (invalid identifier)";
        }
    }else{
        echo "Error";
    }

?>
