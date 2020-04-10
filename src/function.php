<?php
    include('./common/constant.php');

    function generateID() {
        if(file_exists(employeeData)) {
            $dataArray = file(employeeData, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if(sizeof($dataArray) == 0){
                return 1;
            }
            $lastID = explode(",", $dataArray[count($dataArray) - 1])[0];
            $newID = $lastID + 1;
            return $newID;
        } 
        return -1;
    }

    function createEmployee(){
        $dataArray = file(employeeData, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $eID = generateID();
        $eDetail = "{$eID},{$_POST['firstName']},{$_POST['lastName']},{$_POST['gender']},{$_POST['age']},{$_POST['address']},{$_POST['phone']}";
        //$eDetail="{$eID},Long,Nguyen,M,9";
        array_push($dataArray, $eDetail);
        $dataText = implode("\n", $dataArray);
        $csvFile = fopen(employeeData, 'w');
        fwrite($csvFile, implode("\n", $dataArray));
        fclose($csvFile);
        return $dataText;
    }

    function createEmployeeTest(){
        $dataArray = file(employeeData, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $eID = generateID();
        //$eDetail = "{$eID},{$_POST['firstName']},{$_POST['lastName']},{$_POST['gender']},{$_POST['age']},{$_POST['address']},{$_POST['phone']}";
        $eDetail="{$eID},Long,Nguyen,M,9";
        array_push($dataArray, $eDetail);
        $dataText = implode("\n", $dataArray);
        $csvFile = fopen(employeeData, 'w');
        fwrite($csvFile, implode("\n", $dataArray));
        fclose($csvFile);
        return $dataText;
    }

    function getEmployeeByID($id){
        $dataArray = file(employeeData, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($dataArray as $employee){
            $eDetail = explode(",", $employee);
            if($eDetail[0] == $id){
                return $eDetail;
            }
        }
        return -1;
    }

    function getEmployee(){
        if(!file_exists(employeeData)){
            return -1;
        }
        return file(employeeData, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    function updateEmployee(){
        if(!file_exists(employeeData)){
            return -1;
        }
        $dataArray = file(employeeData, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $newDataArray = array();
        for($i = 0; $i < count($dataArray); $i++){
            $eID = explode(",", $dataArray[$i])[0];
            if($eID == $_POST['id']){
                $eDetail = "{$_POST['id']},{$_POST['firstName']},{$_POST['lastName']},{$_POST['gender']},{$_POST['age']},{$_POST['address']},{$_POST['phone']}";
                $dataArray[$i] = $eDetail;
                $csvFile = fopen(employeeData, 'w');
                fwrite($csvFile, implode("\n", $dataArray));
                fclose($csvFile);
                return $dataArray;
            }
        }
        return -1;
    }

    function deleteEmployee(){
        if(!file_exists(employeeData)){
            return -1;
        }
        $dataArray = file(employeeData, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        for($i = 0; $i < count($dataArray); $i++){
            $eID = explode(",", $dataArray[$i])[0];
            if($eID == $_POST['delete']){
                unset($dataArray[$i]);
                $csvFile = fopen(employeeData, 'w');
                fwrite($csvFile, implode("\n", $dataArray));
                fclose($csvFile);
                return "Delete Successfully";
            }
        }
    }


    function searchByName($type, $name){
        if(!file_exists(employeeData)){
            return -1;
        }
        $dataArray = file(employeeData, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $foundData = [];
        for($i = 0; $i < count($dataArray); $i++){
            $eName =  explode(",", $dataArray[$i])[$type];
            if(strtolower($eName) == strtolower($name)){
                array_push($foundData, $dataArray[$i]);
            }
        }
        return removeEmptyArray($foundData);
    }

    function filterByGender($gen){
        if(!file_exists(employeeData)){
            return -1;
        }
        $dataArray = file(employeeData, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $newData = array();
        for($i = 0; $i < count($dataArray); $i++){
            $eGender =  explode(",", $dataArray[$i])[3];
            if($eGender === $gen){
                array_push($newData, $dataArray[$i]);
            }
        }
        return removeEmptyArray($newData);
    }

    function filterByAge($type){
        // 1: Over
        // 0: Under
        if(!file_exists(employeeData)){
            return -1;
        }
        $dataArray = file(employeeData, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $newData = [];
        for($i = 0; $i < count($dataArray); $i++){
            $age =  explode(",", $dataArray[$i])[4];
            if($type == 1){
                $age > $_POST['ageOverFilter'] ? array_push($newData, $dataArray[$i]) : '';
            }elseif($type == 0){
                $age < $_POST['ageUnderFilter'] ? array_push($newData, $dataArray[$i]) : '';
            }
        }
        return $newData;
    }

    function removeEmptyArray($dataArray){
        $newArray = array();
        foreach($dataArray as $data){
            if($data){
                array_push($newArray, $data);
            }
        }
        return $newArray;
    }
    
    
?>