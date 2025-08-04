<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Registro de Café</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f5f5f5;
        }

        h1 {
            color: #444;
        }

        form {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        label, select, input {
            display: block;
            width: 100%;
            margin-bottom: 15px;
        }

        button {
            background-color: #c0392b;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        .response {
            margin-top: 20px;
            background-color: #eee;
            padding: 15px;
            border-radius: 6px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<h1>Eliminar Registro</h1>

<form method="POST">
    <label for="table">Tabla:</label>
    <select name="table" id="table" required>
        <option value="">-- Selecciona una tabla --</option>
        <option value="planta">Plantas de Café</option>
        <option value="grano">Granos de Café</option>
        <option value="tiempo_crecimiento">Tiempo de Crecimiento</option>
        <option value="region">Región</option>
        <option value="sabor">Sabor</option>
        <option value="datos_cafe">Datos del Café</option>
    </select>

    <label for="id">ID del registro:</label>
    <input type="number" name="id" id="id" min="1" required>

    <button type="submit">Eliminar</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table'] ?? '';
    $id = $_POST['id'] ?? '';

    // Lista blanca de tablas permitidas
    $allowed_tables = [
        'planta',
        'grano',
        'tiempo_crecimiento',
        'region',
        'sabor',
        'datos_cafe'
    ];

    // Validación básica
    if (!in_array($table, $allowed_tables)) {
        echo "<div class='response error'>Tabla no permitida o inválida.</div>";
        exit;
    }

    if (!is_numeric($id) || (int)$id <= 0) {
        echo "<div class='response error'>ID inválido.</div>";
        exit;
    }

    // Llamada a la API para eliminar
    $url = "http://localhost:8081/caracteristicas_cafe/delete?table=" . urlencode($table) . "&id=" . urlencode($id);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo '<div class="response">';
    if ($httpCode === 200) {
        echo "<p class='success'>Registro eliminado correctamente.</p>";
    } else {
        $data = json_decode($response, true);
        $errorMsg = $data['error'] ?? $data['message'] ?? 'Error desconocido.';
        echo "<p class='error'>Error ({$httpCode}): " . htmlspecialchars($errorMsg) . "</p>";
    }
    echo '</div>';
}
?>

</body>
</html>
