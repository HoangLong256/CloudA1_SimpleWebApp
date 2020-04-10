<?php
    $title = 'Database Management';
    include('./common/boostrap.php');
    include('validation.php');
    include('function.php');
    //Generate Error Array
    $error = array();

    // Handle Create Employee
    if(isset($_POST['create'])){
        $error = array();
        $error['firstName'] = textValidation($_POST['firstName']) == 1 ? null: textValidation($_POST['firstName']);
        $error['lastName'] = textValidation($_POST['lastName']) == 1 ? null :textValidation($_POST['lastName']);
        $error['address'] = textValidation($_POST['address']) == 1 ? null :textValidation($_POST['address']);
        $error['age'] = ageValidation($_POST['age']) == 1 ? null :ageValidation($_POST['age']);
        $error['phone'] = phoneValidation($_POST['phone']) == 1 ? null :phoneValidation($_POST['phone']);
        if(!$error['firstName'] && !$error['lastName'] && !$error['address'] && !$error['age'] && !$error['phone'] ){
            createEmployee();
            unset($_POST);
        }
    }

    // Handle Update Button
    $selectedEmployee = array();
    if(isset($_POST['update'])){
        $selectedEmployee = getEmployeeByID($_POST['update']);
    }

    // Handle Update Employee
    if(isset($_POST['updateAction'])){
        $error = array();
        $error['firstName'] = textValidation($_POST['firstName']) == 1 ? null: textValidation($_POST['firstName']);
        $error['lastName'] = textValidation($_POST['lastName']) == 1 ? null :textValidation($_POST['lastName']);
        $error['address'] = textValidation($_POST['address']) == 1 ? null :textValidation($_POST['address']);
        $error['age'] = ageValidation($_POST['age']) == 1 ? null :ageValidation($_POST['age']);
        $error['phone'] = phoneValidation($_POST['phone']) == 1 ? null :phoneValidation($_POST['phone']);
        if(!$error['firstName'] && !$error['lastName'] && !$error['address'] && !$error['age'] && !$error['phone'] ){
            updateEmployee();
            unset($_POST);
            $selectedEmployee = array();
        }else{
            $_POST['update'] = $_POST['id'];
            $selectedEmployee = getEmployeeByID($_POST['update']);
        }
    }

    // Handle Delete Employee
    if(isset($_POST['delete'])){
        deleteEmployee();
        header('Location: '.$_SERVER['PHP_SELF']);  
    }
?>

<body>
    <?php include('./common/navbar.php');
    ?>
    <!-- Edit and Create Form -->
    <div class="row m-auto">
        <div class="col-sm-1 d-none d-xl-block"></div>
        <div class="col-sm-5 col-xl-4 bg-info">
            <form class="m-5" method="post">
                <h3 class="form-header">
                    <?php echo isset($_POST['update']) ? "Edit Lecturer" : 'Add Lecturer'; ?>
                </h3>
                <div class="form-group">
                    <input type="hidden" name="id" value="
                            <?php
                                if(isset($_POST['update'])){
                                    echo $selectedEmployee[0];
                                }else{
                                    echo '';
                                }
                            ?>" />
                </div>
                <?php 
                    echo isset($_POST['update']) 
                    ? '<div class="form-group">
                            <label>ID: </label>
                            <input type="text" class="form-control"
                                value="'.trim($selectedEmployee[0]).'" disabled>
                        </div>'
                    : '';
                ?>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" class="form-control" name="lastName" value="<?php
                                if(isset($_POST['update'])){
                                    echo $selectedEmployee[2];
                                }else{
                                    echo $_POST['lastName'];
                                }
                            ?>">
                    <p class="text-danger"><?php echo isset($error['lastName']) ? $error['lastName'] : ''; ?></p>
                </div>
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" class="form-control" name="firstName" value="<?php
                                if(isset($_POST['update'])){
                                    echo $selectedEmployee[1];
                                }else{
                                    echo $_POST['firstName'];
                                }
                            ?>">
                    <p class="text-danger"><?php echo isset($error['firstName']) ? $error['firstName'] : ''; ?></p>
                </div>

                <div class="form-group">
                    <label>Gender </label>
                    <select class="form-control" name="gender">
                        <option
                            <?php  echo isset($_POST['update']) ? ($selectedEmployee[3] == 'M' ?  'selected' : ''):  'selected'?>>
                            M</option>
                        <option
                            <?php  echo isset($_POST['update']) ? ($selectedEmployee[3] == 'F' ?  'selected' : ''):  ''?>>
                            F</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Age</label>
                    <input type="text" class="form-control" name="age" value="<?php
                                if(isset($_POST['update'])){
                                    echo $selectedEmployee[4];
                                }else{
                                    echo $_POST['age'];
                                }
                            ?>">
                    <p class="text-danger"><?php echo isset($error['age']) ? $error['age'] : ''; ?><p>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" class="form-control" name="address" value="<?php
                                if(isset($_POST['update'])){
                                    echo $selectedEmployee[5];
                                }else{
                                    echo $_POST['address'];
                                }
                            ?>">
                    <p class="text-danger"><?php echo isset($error['address']) ? $error['address'] : ''; ?><p>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" name="phone" value="<?php
                                if(isset($_POST['update'])){
                                    echo $selectedEmployee[6];
                                }else{
                                    echo $_POST['phone'];
                                }
                            ?>">
                    <p class="text-danger"><?php echo isset($error['phone']) ? $error['phone'] : ''; ?></p>
                </div>
                <?php 
                    if(!isset($_POST['update'])){
                        echo '<button type="submit" class="btn btn-primary" name="create">Create</button>';
                    }else{
                        echo '<button type="submit" class="btn btn-primary" name="updateAction">Update</button>';
                    }
                ?>
            </form>
        </div>
        <div class="col-sm-7 col-xl-6 ">
            <div class="d-flex justify-content-center mt-2">
                <h2>Database</h2>
            </div>
            <!-- Filter Form -->
            <div class="row ml-0 my-2">
                <div>
                    <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filterFunction">
                        Filter
                    </button>
                </div>
                <div class="collapse mt-0 ml-1" id="filterFunction">
                    <div>
                        <form class="form-inline" method="POST">
                            <label class="my-1 mr-2">Gender</label>
                            <select class="custom-select my-1 mr-sm-2" name="genderFilter">
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                            <button type="submit" name="genderFilterBtn" class="btn btn-info my-1">Apply</button>
                        </form>
                        <form class="form-inline" method="POST">
                            <label class="my-1 mr-2">First Name</label>
                            <input type="text" class="form-control mb-2 mr-sm-2" name="nameSearch">
                            <button type="submit" name="nameSearchBtn" class="btn btn-info my-1">Apply</button>
                        </form>
                        <form class="form-inline" method="POST">
                            <label class="my-1 mr-2">Last Name</label>
                            <input type="text" class="form-control mb-2 mr-sm-2" name="lastNameSearch">
                            <button type="submit" name="lastNameSearchBtn" class="btn btn-info my-1">Apply</button>
                        </form>
                        <form class="form-inline" method="POST">
                            <label class="my-1 mr-2">Age Under</label>
                            <input type="text" class="form-control mb-2 mr-sm-2" name="ageUnderFilter">
                            <button type="submit" name="ageUnderFilterBtn" class="btn btn-info my-1">Apply</button>
                        </form>
                        <form class="form-inline" method="POST">
                            <label class="my-1 mr-2">Age Over </label>
                            <input type="text" class="form-control mb-2 mr-sm-2" name="ageOverFilter">
                            <button type="submit" name="ageOverFilterBtn" class="btn btn-info my-1">Apply</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Loading data into table -->
            <div class="m-auto p-0 d-flex justify-content-center ">
                <table class="table table-responsive table-hover m-auto">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Last</th>
                            <th scope="col">First</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Age</th>
                            <th scope="col">Address</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    // Display data based on default or filter setting
                        $dataArray = array();
                        if(isset($_POST['ageOverFilterBtn'])){
                            $dataArray = filterByAge(1);
                        }elseif(isset($_POST['ageUnderFilterBtn'])){
                            $dataArray = filterByAge(0);
                        }elseif(isset($_POST['genderFilterBtn'])){
                            $dataArray = filterByGender($_POST['genderFilter']);
                        }elseif(isset($_POST['nameSearchBtn'])){
                            $dataArray = searchByName(1, $_POST['nameSearch']);
                        }elseif(isset($_POST['lastNameSearchBtn'])){
                            $dataArray = searchByName(2, $_POST['lastNameSearch']);
                        }else{
                            $dataArray = getEmployee();
                        }
                        if($dataArray){
                            foreach($dataArray as $Employee) {
                                $eDetail = explode(",", $Employee); 
                                // then create the table and print out the lecturer's info
                                echo "<tr>";
                                echo    "<td>".$eDetail[0]."</td>";
                                echo    "<td>".$eDetail[2]."</td>";
                                echo    "<td>".$eDetail[1]."</td>";
                                echo    "<td>".$eDetail[3]."</td>";
                                echo    "<td>".$eDetail[4]."</td>";
                                echo    "<td>".$eDetail[5]."</td>";
                                echo    "<td>".$eDetail[6]."</td>";
                                echo "<form method='POST'>";
                                echo    "<td><button type='submit' class='btn btn-info' name='update' value='$eDetail[0]'>Edit</button></td>";
                                echo    "<td><button type='submit' class='btn btn-danger' name='delete' value='$eDetail[0]'>Delete</button></td>";
                                echo "</form>";
                                echo "</tr>";
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-1 d-none d-xl-block"></div>

    </div>
    <?php
        include('./common/footer.php')
    ?>
</body>