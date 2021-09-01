<?php

require "conexion.php";

// Creamos variables vacio
$name = $address = $salary = "";
$name_err = $address_err = $salary_err = "";

// Si recibimos los datos por POST
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validamos nombre
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Por favor ingrese el nombre del empleado.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Por favor ingrese un nombre válido.";
    } else{
        $name = $input_name;
    }

    // Validamos dirreccion
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Por favor ingrese una dirección.";
    } else{
        $address = $input_address;
    }

    // Validamos salario
    $input_salary = trim($_POST["salary"]);
    if(empty($input_salary)){
        $salary_err = "Por favor ingrese el monto del salario del empleado.";
    } elseif(!ctype_digit($input_salary)){
        $salary_err = "Por favor ingrese un valor correcto y positivo.";
    } else{
        $salary = $input_salary;
    }

    // Checkeamos si hay errores antes de iniar la insercion
    if(empty($name_err) && empty($address_err) && empty($salary_err)){
        // Prepared Statement
        $sql = "INSERT INTO employees (name, address, salary) VALUES (?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // usamos el parametros bind
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_address, $param_salary);


            $param_name = $name;
            $param_address = $address;
            $param_salary = $salary;

            // Si se ejecuto statement
            if(mysqli_stmt_execute($stmt)){
                // Nos redireccionamos a index
                header("Location: index.php");
                exit();
            } else{
                echo "Hay un error. Intentelo mas tarde.";
            }
        }

        // Cerramos Statement
        mysqli_stmt_close($stmt);
    }

    // Cerramos conexion
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agregar Empleado</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Agregar Empleado</h2>
                    </div>
                    <p>Favor diligenciar el siguiente formulario, para agregar el empleado.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Dirección</label>
                            <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
                            <span class="help-block"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($salary_err)) ? 'has-error' : ''; ?>">
                            <label>Sueldo</label>
                            <input type="text" name="salary" class="form-control" value="<?php echo $salary; ?>">
                            <span class="help-block"><?php echo $salary_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
