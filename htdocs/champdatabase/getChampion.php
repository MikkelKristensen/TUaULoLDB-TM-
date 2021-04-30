<?php
    function getID($champname, $connection){
        $sql = "SELECT id from champions where name='$champname'";
        $result = mysqli_query($connection, $sql);
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        return $array[0]['id'];
    }

    function getFromDatabase($champid, $tablename, $desiredvalue, $connection){
        $sql = "SELECT `$desiredvalue` from `$tablename` where id='$champid'";
        $result = mysqli_query($connection, $sql);
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        $value = $array[0][$desiredvalue];
        return str_replace('\n', '<br />', $value);
    }

    function getName($champid, $abilityname, $connection){
        return getFromDatabase($champid, $abilityname, 'name', $connection);
    }

    function getDescription($champid, $abilityname, $connection){
        return getFromDatabase($champid, $abilityname, 'description', $connection);
    }

    function getImageReference($champid, $abilityname, $connection){
        return getFromDatabase($champid, $abilityname, 'image reference', $connection);
    }

    function getAttributes($champid, $abilityname, $connection){
        return getFromDatabase($champid, $abilityname, 'attributes', $connection);
    }

    function getAbility($champid, $abilityname, $connection){
        $array = array();
        $array['name'] = getName($champid, $abilityname, $connection);
        $array['description'] = getDescription($champid, $abilityname, $connection);
        $array['image reference'] = getImageReference($champid, $abilityname, $connection);

        if($abilityname != 'passive'){
            $array['attributes'] = getAttributes($champid, $abilityname, $connection);
        }

        return $array;
    }

    // Connect to MySQL through MySQLi
    $connected = mysqli_connect('localhost', 'mikkel', 'test123', 'leaguechampiondatabase');

    // Check connection
    if(!$connected){
        echo 'Connection error: ', mysqli_connect_error();
    }


    // Get champ
    $champname = $_GET['champname'];
    $champid = getID($champname, $connected);
    $passivedata = getAbility($champid, 'passive', $connected);
    $qdata = getAbility($champid, 'q', $connected);
    $wdata = getAbility($champid, 'w', $connected);
    $edata = getAbility($champid, 'e', $connected);
    $rdata = getAbility($champid, 'r', $connected);

    $champdata = array();
    $champdata['passive'] = $passivedata;
    $champdata['q'] = $qdata;
    $champdata['w'] = $wdata;
    $champdata['e'] = $edata;
    $champdata['r'] = $rdata;

    echo json_encode($champdata);

    // Close connection
    mysqli_close($connected);

?>