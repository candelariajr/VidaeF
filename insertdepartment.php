<?php
include("access.php");

function getEmployeeList()
{
    //populate dept mgr with names from query
    $getDepartmentMgrQuery = "select lname, fname, ssn from employee order by lname, fname";
    GLOBAL $conn;
    $result = $conn->query($getDepartmentMgrQuery);

    while ($row = $result->fetch_assoc())
    {
        echo "<option data-tokens=\"".$row["lname"].",".$row["fname"]."\" value=\"".$row["ssn"]."\">";
        echo $row["lname"].", ".$row["fname"];
        echo "</option>";
    }
}

function generateValidator()
{
    $getUniqueColumnsQuery = "select column_name from information_schema.columns where table_name = \"department\" and column_key = \"UNI\" or table_name = \"department\" and column_key = \"PRI\";";
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
        $getUniqueItemsQuery = "select ".$row["column_name"]." from department";
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
    <title>Add new Department</title>
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
    <h2>Add new Department</h2>
    <form id="theForm" class ="form-horizontal" role="form" action="submit.php" method="post" id="mainForm">
        <input type="hidden" name="table" value="department"/>
        <fieldset>
            <legend>Departmental Information</legend>
            <div class = "form-group">
                <label class="control-label col-sm-2" for="dname">Department Number: </label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="dnumber" id="dnumber" maxlength="2"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="dname">Department Name: </label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="dname" id="dname" maxlength="20"/>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Manager Information</legend>
            <div class="form-group">
                <label class="control-label col-sm-2" for="ssn">Department Manager: </label>
                <div class="col-sm-10">
                    <select class="selectpicker" data-live-search="true" id="ssn" name="ssn">
                        <?php
                        getEmployeeList();
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="mgrstartdate">Manager Start Date: </label>
                <div class="col-sm-10">
                    <input class="form-control" type="date" name="mgrstartdate" id="mgrstartdate"/>
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
                if(document.getElementById(uniqueEntity[i][0]).value === uniqueEntity[i][j])
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
</script>
</html>
