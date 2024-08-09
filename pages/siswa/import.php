<?php
if (isset($_POST['import'])) {
    $fileName = $_FILES['file']['tmp_name'];

    if ($_FILES['file']['size'] > 0) {
        $file = fopen($fileName, "r");

        // Jika CSV
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            $sqlInsert = "INSERT into your_table (column1, column2, column3)
                   values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "')";
            $result = mysqli_query($conn, $sqlInsert);

            if (!empty($result)) {
                echo "CSV Data Imported into the Database";
            } else {
                echo "Problem in Importing CSV Data";
            }
        }
        fclose($file);

        // Jika Excel
        require 'vendor/autoload.php';
        use PhpOffice\PhpSpreadsheet\IOFactory;

        $spreadsheet = IOFactory::load($fileName);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach ($rows as $row) {
            $sqlInsert = "INSERT into your_table (column1, column2, column3)
                   values ('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "')";
            $result = mysqli_query($conn, $sqlInsert);

            if (!empty($result)) {
                echo "Excel Data Imported into the Database";
            } else {
                echo "Problem in Importing Excel Data";
            }
        }
    }
}
?>

<form action="import.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="file" accept=".csv, .xlsx, .xls">
    <button type="submit" name="import">Import</button>
</form>
