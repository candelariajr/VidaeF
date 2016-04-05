<?php
/**
 * Created by PhpStorm.
 * User: Frayo
 * Date: 4/3/2016
 * Time: 11:33 PM
 */

include("access.php");

function generateResults($tableResults)
{
    switch($tableResults)
    {
        case "employee":
            generateEmployeeBody();
            break;
        case "department";
            generateDepartmentBody();
            break;
    }
}

function generateEmployeeBody()
{
    echo "
<div class=\"container\">
    <h2>Display Employees</h2>
    <hr>
    <table class=\"table table-responsive\">
        <thead>
        <tr>
            <th>First Name</th>
            <th>M.I.</th>
            <th>Last Name</th>
            <th>SSN</th>
            <th>Birthdate</th>
            <th>Address</th>
            <th>Sex</th>
            <th>Salary</th>
            <th>Supervisor</th>
            <th>Dept No</th>
        </tr>
        </thead>
        <tbody>";
    generateEmployeeRows();
    echo"
        </tbody>
    </table>
</div>
";
}

function generateEmployeeRows()
{
    $getEmployeesQuery = "Select * from employee order by lname, fname;";
    GLOBAL $conn;
    $result = $conn->query($getEmployeesQuery);

    while ($row = $result->fetch_assoc())
    {
        echo "<tr>";
        echo "<td>".$row["fname"]."</td>";
        echo "<td>".$row["minit"]."</td>";
        echo "<td>".$row["lname"]."</td>";
        echo "<td>".$row["ssn"]."</td>";
        echo "<td>".$row["bdate"]."</td>";
        echo "<td>".$row["address"]."</td>";
        echo "<td>".$row["sex"]."</td>";
        echo "<td>".round($row["salary"])."</td>";
        echo "<td>".$row["superssn"]."</td>";
        echo "<td>".$row["dno"]."</td>";
        echo "</tr>";
    }
}

function generateDepartmentBody()
{
    echo "
<div class=\"container\">
    <h2>Display Department</h2>
    <hr>
    <table class=\"table table-responsive\">
        <thead>
        <tr>
            <th>Department Name</th>
            <th>Department No.</th>
            <th>Manager</th>
            <th>Manager Start Date</th>
        </tr>
        </thead>
        <tbody>";
    generateDepartmentRows();
    echo"
        </tbody>
    </table>
</div>
";
}

function generateDepartmentRows()
{
    $getDepartmentQuery = "select dname, dnumber, lname, fname, minit, mgrstartdate from employee, department where ssn = mgrssn;";
    GLOBAL $conn;
    $result = $conn->query($getDepartmentQuery);
    while ($row = $result->fetch_assoc())
    {
        echo "<tr>";
        echo "<td>".$row["dname"]."</td>";
        echo "<td>".$row["dnumber"]."</td>";
        echo "<td>".$row["lname"].", ".$row["fname"]." ".$row["minit"].".</td>";
        echo "<td>".$row["mgrstartdate"]."</td>";
        echo "</tr>";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Display Employees</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
</head>
<body>
<?php
echo "<table>";
foreach ($_POST as $key => $value)
{
    echo "<tr>";
    echo "<td>";
    echo $key;
    echo "</td>";
    echo "<td>";
    echo $value;
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
if(isset($_POST["table"]))
{
    generateResults($_POST["table"]);
}
else
{
    echo "<br>This page is not generated without a form directed to it! Please use the data entry form!";
}
?>
<br>
<br>
</body>
</html>