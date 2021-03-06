<?php
    // Text validation : last name, first name, address...
    function textValidation($data){
        if(empty($data)){
            return 'Cannot be empty';
        }
        return 1;
    }

    // Age Validation
    function ageValidation($data){
        if(empty($data)){
            return 'Cannot be empty';
        }
        if(!preg_match("/^\d+$/", $data)){
            return 'Must be an integer';
        }
        if($data < 0 || $data > 100){
            return 'Invalid number';
        }
        return 1;
    }

    // Phone Validation
    function phoneValidation($data){
        if(empty($data)){
            return 'Cannot be empty';
        }
        if(!preg_match("/^\d+$/", $data)){
            return 'Must be an integer';
        }
        if(strlen((string) $data) < 9){
            return 'Must be at least 9 character';
        }
        return 1;
    }
    
    
?>
