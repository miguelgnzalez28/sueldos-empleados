<?php
session_start();

$employeeList = [];

if (isset($_SESSION['employeeData'])) {
    $employeeList = $_SESSION['employeeData'];
}

if (isset($_POST['delete'])) {
    session_destroy();
    header("Location: index.php");
}

class Employee
{
    public $name;
    public $age;
    public $maritalStatus;
    public $gender;
    public $salary;

    public function __construct($name, $age, $maritalStatus, $gender, $salary)
    {
        $this->name = $name;
        $this->age = $age;
        $this->maritalStatus = $maritalStatus;
        $this->gender = $gender;
        $this->salary = $salary;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getSalary()
    {
        return $this->salary;
    }
}

if (isset($_POST['nombre']) && isset($_POST['edad']) && isset($_POST['estado']) && isset($_POST['sexo']) && isset($_POST['sueldo'])) {
    $name = $_POST['nombre'];
    $age = $_POST['edad'];
    $maritalStatus = $_POST['estado'];
    $gender = $_POST['sexo'];
    $salary = $_POST['sueldo'];

    if (!empty($name) && !empty($age) && !empty($maritalStatus) && !empty($gender) && !empty($salary)) {
        $employee = new Employee($name, $age, $maritalStatus, $gender, $salary);
        array_push($employeeList, $employee);
        $_SESSION['employeeData'] = $employeeList;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Act. 1</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <br>
    <h1>Salario de trabajadores</h1>
    <br><br>
    <form action="index.php" method="post" class="form-inline">

        <h2>Ingresa los siguientes datos</h2>
        <div class="form-group mb-2">
            <label for="nombre">Nombre y Apellido</label>
            <input onkeyup="lettersOnly(this)" class="form-control" type="text" id="nombre" name="nombre">

            <label for="edad">Edad</label>
            <input pattern="[0-9]+" maxlength="3"
                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" /
                class="form-control" type="number" id="edad" name="edad">

            <label>Estado Civil</label>
            <br>
            <input class="form-check-input" type="radio" id="Casado" name="estado" value="Casado(a)" checked>
            <label for="Casado">Casado(a)</label>
            <input class="form-check-input" type="radio" id="Soltero" name="estado" value="Soltero(a)">
            <label for="Soltero">Soltero(a)</label>
            <input class="form-check-input" type="radio" id="Viudo" name="estado" value="Viudo(a)">
            <label for="Viudo">Viudo(a)</label>
            <br>

            <label>Sexo</label>
            <br>
            <input class="form-check-input" type="radio" id="Masculino" name="sexo" value="Masculino" checked>
            <label for="Masculino">Masculino</label>
            <input class="form-check-input" type="radio" id="Femenino" name="sexo" value="Femenino">
            <label for="Femenino">Femenino</label>
            <br>

            <label>Sueldo</label>
            <br>
            <input class="form-check-input" type="radio" id="1.000_Bs" name="sueldo" value="-1.000 Bs" checked>
            <label for="1.000_Bs">-1.000 Bs</label>
            <input class="form-check-input" type="radio" id="1.000-2.500_Bs" name="sueldo"
                value="1.000 Bs a 2.500 Bs">
            <label for="1.000-2.500_Bs">1.000 Bs a 2.500 Bs</label>
            <input class="form-check-input" type="radio" id="2.500_Bs" name="sueldo" value="+2.500 Bs">
            <label for="2.500_Bs">+2.500 Bs</label>
            <br>
            <br>

            <input type="submit" value="Agregar" name="btn" class="btn btn-success">
            <input type="submit" value="Reiniciar" name="delete" class="btn btn-danger">
            <a href="../index.php" class="btn btn-secondary">Regresar</a>
        </div>

    </form>

    <h2>Lista empleados</h2>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Nombre y Apellido</th>
                <th scope="col">Edad</th>
                <th scope="col">Estado Civil</th>
                <th scope="col">Sexo</th>
                <th scope="col">Sueldo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($employeeList as $employee) {
                echo "<tr>";
                echo "<td>", $employee->getName(), "</td>";
                echo "<td>", $employee->getAge(), "</td>";
                echo "<td>", $employee->getMaritalStatus(), "</td>";
                echo "<td>", $employee->getGender(), "</td>";
                echo "<td>", $employee->getSalary(), "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br><br>

    <h2>Datos Filtrados</h2>

    <?php
    $amountFemale = 0;
    $amountMalesMarried2500 = 0;
    $amountWidows1000 = 0;
    $amountMales = 0;
    $summAgeMale = 0;
    foreach ($employeeList as $employee) {
        if ($employee->getGender() == "Femenino") {
            $amountFemale += 1;
        }
        if ($employee->getGender() == "Masculino" && $employee->getMaritalStatus() == "Casado(a)" && $employee->getSalary() == "+2.500 Bs") {
            $amountMalesMarried2500 += 1;
        }
        if ($employee->getMaritalStatus() == "Viudo(a)" && $employee->getGender() == "Femenino" && ($employee->getSalary() == "1.000 Bs a 2.500 Bs" || $employee->getSalary() == "+2.500 Bs")) {
            $amountWidows1000 += 1;
        }
        if ($employee->getGender() == "Masculino") {
            $amountMales += 1;
            $summAgeMale += $employee->getAge();
        }
    }

    echo "<p> Total de empleadas femeninas: ", $amountFemale, "</p>";
    echo "<p> Total de empleados masculinos casados que ganan +2.500 Bs: ", $amountMalesMarried2500, "</p>";
    echo "<p> Total de empleadas femeninas viudas que ganan +1.000 Bs: ", $amountWidows1000, "</p>";
    echo "<p> Edad promedio de empleados masculinos: ";
    try {
        echo $averageAgeMale = intval($summAgeMale / $amountMales);
    } catch (DivisionByZeroError $e) {
        echo "0";
    }
    echo "</p>";
    ?>

</body>

<script>
    function lettersOnly(input) {
        var regex = /[^a-z ]/gi;
        input.value = input.value.replace(regex, "");
    }
</script>

</html>
