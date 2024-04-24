<?php
include_once "conn.php";
include 'generation.php';
include 'crossover.php';
include 'mutation.php';
include 'selection.php';

// Retrieve form data
$data = json_decode(file_get_contents("php://input"), true);

if ($data !== null && !empty($data['formData'])) {
    foreach ($data['formData'] as $form) {

        $classCode = $form['classCode'];
        $className = $form['className'];
        $scheduleID = strval(mt_rand(1000000000, 9999999999)); // rand. gen ID

        $grandParentA = generateClassTimes('AM', 90, 5);
        $grandParentB = generateClassTimes('AM', 90, 5);
        $grandParentC = generateClassTimes('AM', 120, 5);
        $grandParentD = generateClassTimes('AM', 120, 5);

        $parentA = crossover($grandParentA, $grandParentC);
        $parentB = crossover($grandParentB, $grandParentD);

        $mutatedGene = mutate($parentA, $parentB, 0.3);
        $selectedGene = selection($mutatedGene);

        $subjectClassStart = $selectedGene[0];
        $subjectClassEnd = $selectedGene[1];
        $subjectClassDays = $selectedGene[2];

        try {
            // Prepare SQL statement
            $sql = "INSERT INTO scheduling (scheduleID, subjectCode, subjectName, subjectClassStart, subjectClassEnd, subjectClassDays)
            VALUES (:scheduleID, :classCode, :className, :subjectClassStart, :subjectClassEnd, :subjectClassDays)"; 
            //
            //
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':scheduleID', $scheduleID);
            $stmt->bindParam(':classCode', $classCode);
            $stmt->bindParam(':className', $className);
            $stmt->bindParam(':subjectClassStart', $subjectClassStart);
            $stmt->bindParam(':subjectClassEnd', $subjectClassEnd);
            $stmt->bindParam(':subjectClassDays', $subjectClassDays);

            // Execute the statement
            $stmt->execute();
            echo "Data inserted successfully" . "<br>";

        } catch(PDOException $e) {
            // Log error
            error_log("Error: " . $e->getMessage(), 0);

            echo "Error: " . $e->getMessage();
        }
    }
} else {
}
?>