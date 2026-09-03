<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['logged_in'])) {
            header('Location: indexLogin.php');
            exit;
        }

        include_once "indexHeader.php";

        $conn = new mysqli($host, $user, $pass, $db);
        $selectedShiftTypeId = $_GET['selected_shift_type_id'] ?? '';

        $renderShiftTypeOptions = function (array $shiftTypeRows, $selectedId = '') {
        $options = '<option value="">Select...</option>';

            foreach ($shiftTypeRows as $row) {
                    $isSelected = ((string)($row['id'] ?? '') === (string)$selectedId) ? ' selected' : '';
                    $options .= '<option value="' . htmlspecialchars((string)$row['id']) . '"' . $isSelected . '>'
                        . htmlspecialchars($row['name'] ?? '')
                    . '</option>';
                }

                return $options;
            };

            /* Shift Types */
            $shift_types = $conn->query("
            SELECT *
            FROM shift_types
            WHERE user_code = '" . $_SESSION['user_code'] . "'
            AND status = '1'
            ");

            $shift_type_rows = [];
            if ($shift_types) {
                    while ($shift_type = $shift_types->fetch_assoc()) {
                            $shift_type_rows[] = $shift_type;
                        }
                    }

                    /* Shift Records */
                    $shift_records = $conn->query("
                    SELECT shift_records.*, shift_types.name AS shift_type_name
                    FROM shift_records
                    LEFT JOIN shift_types ON shift_types.id = shift_records.shift_type
                    AND shift_types.user_code = shift_records.user_code
                    AND shift_types.status = '1'
                    WHERE shift_records.user_code = '" . $_SESSION['user_code'] . "'
                    AND shift_records.status = '1'
                    ORDER BY shift_records.date DESC, shift_records.s_time DESC
                    ");


                    ?>

                    <?php include_once "payslipShiftsCSS.php"; ?>
                </div>
                <div class="container-fluid px-2 py-3">

                    <!-- Page Title -->
                    <div class="mb-4">

                    </div>

                    <div class="card align-items-center justify-content-center">
                        <div class="card-body align-items-center justify-content-center">

                            <div class="row g-3 payslip-left-sidebar">