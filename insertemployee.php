<?php
include("access.php");
$getDepartmentQuery = "select dname, dnumber from department order by dname";

function generateDepartmentList()
{
    $getDepartmentQuery = "select dname, dnumber from department order by dname";
    GLOBAL $conn;
    $result = $conn->query($getDepartmentQuery);

    while ($row = $result->fetch_assoc())
    {
        echo "<option data-tokens=\"".$row["dname"]."\" value=\"".$row["dnumber"]."\">";
        echo $row["dname"];
        echo "</option>";
    }
}

function getEmployeeList()
{
    $getDepartmentQuery = "select lname, fname, ssn from employee order by lname, fname";
    GLOBAL $conn;
    $result = $conn->query($getDepartmentQuery);

    while ($row = $result->fetch_assoc())
    {
        echo "<option data-tokens=\"".$row["lname"].",".$row["fname"]."\" value=\"".$row["ssn"]."\">";
        echo $row["lname"].", ".$row["fname"];
        echo "</option>";
    }
}

function generateValidator()
{
    //DB TABLE HERE!
    $getUniqueColumnsQuery = "select column_name from information_schema.columns where table_name = \"employee\" and column_key = \"UNI\" or table_name = \"employee\" and column_key = \"PRI\";";
    GLOBAL $conn;
    $result = $conn->query($getUniqueColumnsQuery);
    echo "<script>
    var testArray = [];
    var uniqueEntity = [];";
    $uniqueIndex = 0;
    while($row = $result->fetch_assoc())
    {
        //echo "<br>".$row["column_name"]."<br>";
        echo"uniqueEntity.push([]);";
        //put display_name here add to array and adjust JS!!!!
        echo"uniqueEntity[".$uniqueIndex."].push(\"".$row["column_name"]."\");";
        //DB TABLE HERE!
        $getUniqueItemsQuery = "select ".$row["column_name"]." from employee";
        $fieldResult = $conn->query($getUniqueItemsQuery);
        while($fieldRow = $fieldResult->fetch_assoc())
        {
            //echo $fieldRow[$row["column_name"]];
            echo"uniqueEntity[".$uniqueIndex."].push(\"".$fieldRow[$row["column_name"]]."\");";
        }
        $uniqueIndex++;
    }
    echo"</script>";
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Add new Employee</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
</head>
<body>
<div class="container">
    <h2>Add an Employee</h2>
    <form id="theForm" class ="form-horizontal" role="form" action="submit.php" method="post">
        <input type="hidden" name="table" value="employee"/>
        <fieldset>
            <legend>Personal Information</legend>
            <div class = "form-group">
                <label class="control-label col-sm-2" for="fname">First Name: </label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="fname" id="fname" maxlength="20"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="minit">Middle Initial: </label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="minit" id="minit" maxlength="1"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="lname">Last Name: </label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="lname" id="lname" maxlength="20"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="ssn">SSN: </label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="ssn" id="ssn" maxlength="11" size="11"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="bdate">Birthdate: </label>
                <div class="col-sm-10">
                    <input class="form-control date-picker" type="date" name="bdate" id="bdate"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="address">Address: </label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="address" id="address" maxlength="50"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="sex">Sex: </label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" maxlength="1" name="sex" id="sex"/>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Departmental Information</legend>
            <div class="form-group">
                <label class="control-label col-sm-2" for="salary">Salary: </label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="salary" id="salary"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="superssn">Supervisor: </label>
                <div class="col-sm-10">
                    <select class="selectpicker" name="superssn" data-live-search="true">
                        <?php
                        getEmployeeList();
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="dno">Department: </label>
                <div class="col-sm-10">
                    <select class="selectpicker" name="dno" data-live-search="true">
                        <?php
                        generateDepartmentList();
                        ?>
                    </select>
                </div>
            </div>
        </fieldset>
        <hr>
        <div class="col-sm-20">
            <button type="button" onclick="validate()" class="btn btn-primary">Submit</button>
        </div>
        <?php generateValidator();?>
    </form>
</div>
<br>
<br>
</body>
<script>
    function validate()
    {
        var validState = 1;
        for(var i = 0; i < uniqueEntity.length; i++)
        {
            for(var j = 1; j < uniqueEntity[i].length; j++)
            {
                if(document.getElementById(uniqueEntity[i][0]).value.replace(/\W/g, '') === uniqueEntity[i][j])
                {
                    alert("Value " + uniqueEntity[i][j] + " already exists for " + uniqueEntity[i][0]);
                    validState = 0;
                }
            }
        }
        if(validState == 1)
        {

            document.getElementById("theForm").submit();
        }

    }

    $('#ssn').keyup(function() {
        var val = this.value.replace(/\D/g, '');
        var newVal = '';
        if(val.length > 4) {
            this.value = val;
        }
        if((val.length > 3) && (val.length < 6)) {
            newVal += val.substr(0, 3) + '-';
            val = val.substr(3);
        }
        if (val.length > 5) {
            newVal += val.substr(0, 3) + '-';
            newVal += val.substr(3, 2) + '-';
            val = val.substr(5);
        }
        newVal += val;
        this.value = newVal;
    });
</script>
</html>