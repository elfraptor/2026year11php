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

                    <style>
                        /* Payslip layout */
                        .card > .card-body {
                            padding: 0;
                        }

                        /* Main page layout */
                        .payslip-left-sidebar {
                            margin: 0;
                            display: flex;
                            align-items: stretch;
                            gap: 0.1rem;
                        }

                        .payslip-shift-panel,
                        .payslip-table-panel {
                            min-width: 0;
                            padding: 0;

                        }
                        .payslip-table-panel {
                            flex: 1;
                            width: 100%;
                        }

                        .payslip-shift-panel > .border,
                        .payslip-table-panel > .border {
                            width: 100%;
                            height: 100%;
                            min-width: 0;
                        }

                        /* Left panel */
                        .left-panel .form-label {
                            font-size: .95rem;
                        }

                        .left-panel .btn-group > .btn {
                            flex: 1;
                            min-width: 0;
                        }

                        .left-panel .form-control {
                            border-radius: 6px;
                        }

                        #selectedShiftTypeFields .form-label {
                            font-size: .72rem;
                            margin-bottom: .15rem;
                            white-space: nowrap;
                        }

                        #selectedShiftTypeFields .form-control {
                            padding: .3rem .4rem;
                            font-size: .82rem;
                            min-width: 0;
                        }

                        #selectedShiftTypeFields .row {
                            margin-left: -.25rem;
                            margin-right: -.25rem;
                        }

                        #selectedShiftTypeFields .row > [class*="col-"] {
                            padding-left: .25rem;
                            padding-right: .25rem;
                        }

                        /* Shifts table */
                        .shifts-header {
                            min-height: 64px;
                        }

                        .table thead th {
                            background: #fafafa;
                        }

                        .table-container {
                            width: 100%;
                            height: 460px;
                            overflow: auto;
                        }

                        .table-container table {
                            min-width: 100%;

                            table-layout: fixed;
                        }

                        .table-container th:nth-child(1),
                        .table-container td:nth-child(1) { width: 10%; }  /* Shift Type */
                        .table-container th:nth-child(2),
                        .table-container td:nth-child(2) { width: 10%; }   /* Date */
                        .table-container th:nth-child(3),
                        .table-container td:nth-child(3) { width: 10%; }   /* Start */
                        .table-container th:nth-child(4),
                        .table-container td:nth-child(4) { width: 10%; }   /* End */
                        .table-container th:nth-child(5),
                        .table-container td:nth-child(5) { width: 9%; }   /* Breaks */
                        .table-container th:nth-child(6),
                        .table-container td:nth-child(6) { width: 9%; }   /* Rate */
                        .table-container th:nth-child(7),
                        .table-container td:nth-child(7) { width: 9%; }   /* Laundry */
                        .table-container th:nth-child(8),
                        .table-container td:nth-child(8) { width: 9%; }   /* Uniform */
                        .table-container th:nth-child(9),
                        .table-container td:nth-child(9) { width: 6%;

                        align-items: center;
                        justify-content: center;

                    }
                    .table-container td:nth-child(9) .form-check-input {
                        margin: 0;
                    }
                    /* Holiday */
                    .table-container th:nth-child(10),
                    .table-container td:nth-child(10) { width: 15%; } /* Operations */



                    /* Modals */
                    .modal .modal-body .form-label {
                        font-weight: 600;
                    }

                    .payslip-modal .modal-dialog {
                        width: min(100%, 720px);
                        max-width: calc(100vw - 2rem);
                    }

                    .payslip-modal .modal-dialog.modal-sm {
                        max-width: 420px;
                    }
                    .payslip-main-table {
                        height: 460px;
                        overflow: auto;
                    }

                    .payslip-modal .modal-content {
                        border: 0;
                        box-shadow: 0 12px 32px rgba(0, 0, 0, .15);
                    }

                    .payslip-modal .modal-header {
                        padding: .9rem 1rem;
                    }

                    .payslip-modal .modal-body {
                        padding: .9rem 1rem .5rem;
                    }

                    .payslip-modal .modal-footer {
                        display: flex;
                        gap: .5rem;
                        padding: .75rem 1rem 1rem;
                        border-top: 0;
                    }

                    .payslip-modal .modal-footer .btn {
                        flex: 1;
                    }

                    /* Shift form */
                    .shift-form-grid .row {
                        margin-bottom: .3rem;
                    }

                    .shift-form-grid .row > [class^="col"] {
                        display: flex;
                        flex-direction: column;
                    }

                    .shift-form-grid .form-label {
                        font-size: .82rem;
                        font-weight: 600;
                        margin-bottom: .2rem;
                    }

                    .shift-form-grid .form-control,
                    .shift-form-grid .form-select {
                        width: 100%;
                        min-height: 2.25rem;
                        padding: .35rem .55rem;
                        font-size: .92rem;
                    }

                    .shift-form-grid .three-col-row {
                        display: grid;
                        grid-template-columns: repeat(3, minmax(0, 1fr));
                        gap: .5rem;
                    }

                    .shift-form-grid .three-col-row > .col-sm-4 {
                        width: 100%;
                    }
                    .row.g-2.col-row {
                        width: 100%;


                    }
                    .shift-form-grid .col-row > div {
                        display: flex;
                        flex-direction: column;
                        justify-content: flex-start;
                    }

                    .shift-form-grid .col-row .form-label {
                        height: 1.25rem;
                        line-height: 1.25rem;
                        margin-bottom: .2rem;
                    }

                    .shift-form-grid .col-row .form-control,
                    .shift-form-grid .col-row .form-select {
                        width: 100%;
                        height: 2.25rem;
                        min-height: 2.25rem;
                        box-sizing: border-box;
                        margin: 0;
                    }
                    f
                    .shift-form-grid .col-row {
                        width: 100%;
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 0.5rem;
                        margin: 0 0 .3rem 0;
                    }

                    .shift-form-grid .col-row > div {
                        width: 100%;
                        min-width: 0;
                        padding: 0;
                    }

                    .shift-form-grid .col-row .form-control,
                    .shift-form-grid .col-row .form-select {
                        width: 100%;
                    }


                    .shift-form-grid .form-check,
                    .shift-form-grid .holiday-field {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        min-height: 2.25rem;
                        margin: 0;
                        text-align: center;
                    }

                    .shift-form-grid .form-check {
                        gap: .25rem;
                        padding-top: .35rem;
                    }

                    .shift-form-grid .form-check-input {
                        margin: 0;
                    }
                    .shift-form-grid .col-row {
                        width: 100%;
                        display: grid;
                        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
                        gap: .5rem;
                        margin: 0 0 .3rem 0;
                    }

                    .shift-form-grid .col-row > div {
                        width: 100%;
                        min-width: 0;
                        display: flex;
                        flex-direction: column;
                    }

                    .shift-form-grid .col-row .form-label {
                        height: 1.25rem;
                        line-height: 1.25rem;
                        margin-bottom: .2rem;
                    }

                    .shift-form-grid .col-row .form-control,
                    .shift-form-grid .col-row .form-select {
                        width: 100%;
                        height: 2.25rem;
                        min-height: 2.25rem;
                        box-sizing: border-box;
                        margin: 0;
                        padding: .35rem .55rem;
                        font-size: .92rem;
                        line-height: 1.5;
                    }
                </style>
            </div>
            <div class="container-fluid px-2 py-3">

                <!-- Page Title -->
                <div class="mb-4">

                </div>

                <div class="card align-items-center justify-content-center">
                    <div class="card-body align-items-center justify-content-center">

                        <div class="row g-3 payslip-left-sidebar">

                            <!-- LEFT SIDEBAR -->
                            <div class="col-8 col-lg-3 px-2 py-2 left-panel payslip-shift-panel">

                                <div class="border rounded p-3 h-100">

                                <h6 class="mb-3">Fixed Rates</h6>

                                    <div class="modal fade" id="createShiftTypeModal" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title">Create Shift Type</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <form method="post" action="payslipCreateShiftType.php">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="user_code" value="<?= htmlspecialchars($_SESSION['user_code']) ?>">

                                                        <div class="row">
                                                            <div class="col-4 mb-3">
                                                            <label class="form-label">Rate</label>
                                                                <input name="rate"
                                                                class="form-control"
                                                                type="number"
                                                                step="0.01"
                                                                placeholder="0.00"
                                                                aria-label="Rate">
                                                            </div>

                                                            <div class="col-4 mb-3">
                                                            <label class="form-label">Deduct</label>
                                                                <input name="deductable"
                                                                class="form-control"
                                                                type="number"
                                                                step="0.01"
                                                                placeholder="0.00"
                                                                aria-label="Deductible">
                                                            </div>

                                                            <div class="col-4 mb-3">
                                                            <label class="form-label">Lau. (%)</label>
                                                                <input name="l_allow"
                                                                class="form-control"
                                                                type="number"
                                                                step="0.01"
                                                                placeholder="0.00"
                                                                aria-label="Laundry Allowance">
                                                                
                                                            </div>
                                                            
                                                        </div>

                                                        <!-- 3 columns -->
                                                        <div class="row">
                                                            <div class="col-4 mb-3">
                                                            <label class="form-label">Uni. (%)</label>
                                                                <input name="u_allow"
                                                                class="form-control"
                                                                type="number"
                                                                step="0.01"
                                                                placeholder="0.00"
                                                                aria-label="Uniform Allowance">
                                                            </div>

                                                            <div class="col-4 mb-3">
                                                            <label class="form-label">PM (%)</label>
                                                                <input name="pm_allow"
                                                                class="form-control"
                                                                type="number"
                                                                step="0.01"
                                                                placeholder="0.00"
                                                                aria-label="PM Allowance">
                                                            </div>

                                                            <div class="col-4 mb-3">
                                                            <label class="form-label">Holiday (%)</label>
                                                                <input name="holi_loading"
                                                                class="form-control"
                                                                type="number"
                                                                step="0.01"
                                                                placeholder="0.00"
                                                                aria-label="Holiday Loading">
                                                            </div>
                                                        </div>

                                                        <!-- 2 columns -->
                                                        <div class="row">
                                                            <div class="col-6 mb-3">
                                                            <label class="form-label">Saturday Loading</label>
                                                                <input name="sat_loading"
                                                                class="form-control"
                                                                type="number"
                                                                step="0.1"
                                                                placeholder="50%"
                                                                aria-label="Saturday Loading">
                                                            </div>

                                                            <div class="col-6 mb-3">
                                                            <label class="form-label">Sunday Loading</label>
                                                                <input name="sun_loading"
                                                                class="form-control"
                                                                type="number"
                                                                step="0.1"
                                                                placeholder="50%"
                                                                aria-label="Sunday Loading">
                                                            </div>
                                                        </div>


                                                        <!-- 2 columns -->
                                                        <div class="row">
                                                            <div class="col-6 mb-3">
                                                            <label class="form-label">Fringe</label>
                                                                <input name="fringe"
                                                                class="form-control"
                                                                type="number"
                                                                step="0.1"
                                                                placeholder="0%"
                                                                aria-label="Fringe">
                                                            </div>

                                                            <div class="col-6 mb-3">
                                                            <label class="form-label">Tax</label>
                                                                <input name="tax"
                                                                class="form-control"
                                                                type="number"
                                                                step="0.1"
                                                                placeholder="0%"
                                                                aria-label="Tax">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                    <button type="submit" class="btn btn-submit full">Create Shift Type</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <form id="shiftTypeForm" method="post" action="payslipUpdateShiftTypeAction.php">

                                        <input type="hidden" name="user_code" value="<?= htmlspecialchars($_SESSION['user_code']) ?>">
                                        <input type="hidden" id="shiftTypeId" name="id" value="">
                                        <div class="mb-3 d-flex justify-content-center">
                                            <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createShiftTypeModal">Create Type</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#saveShiftTypeModal">Save Type</button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteShiftTypeModal">Delete Type</button>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                        <label class="form-label">Shift Type</label>
                                            <select class="form-select" id="shiftTypeSelect" name="shift_type_id" aria-label="Shift type">
                                            <option value="">Select...</option>
                                                <?php foreach ($shift_type_rows as $row): ?>
                                                <option value="<?= htmlspecialchars($row['id']) ?>" <?= ((string)$selectedShiftTypeId === (string)$row['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($row['name']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div id="selectedShiftTypeFields">

                                            <input type="hidden"
                                            name="name"
                                            id="shiftTypeName">

                                            <!-- 3 columns -->
                                            <div class="row">
                                                <div class="col-4 mb-3">
                                                <label class="form-label">Rate ($)</label>
                                                    <input name="rate"
                                                    class="form-control"
                                                    type="number"
                                                    step="0.01"
                                                    placeholder="0.00"
                                                    aria-label="Rate">
                                                </div>

                                                <div class="col-4 mb-3">
                                                <label class="form-label">Deduct ($)</label>
                                                    <input name="deductable"
                                                    class="form-control"
                                                    type="number"
                                                    step="0.01"
                                                    placeholder="0.00"
                                                    aria-label="Deductible">
                                                </div>

                                                <div class="col-4 mb-3">
                                                <label class="form-label">Lau. ($)</label>
                                                    <input name="l_allow"
                                                    class="form-control"
                                                    type="number"
                                                    step="0.01"
                                                    placeholder="0.00"
                                                    aria-label="Laundry Allowance">
                                                </div>
                                            </div>

                                            <!-- 3 columns -->
                                            <div class="row">
                                                <div class="col-4 mb-3">
                                                <label class="form-label">Uni. ($)  </label>
                                                    <input name="u_allow"
                                                    class="form-control"
                                                    type="number"
                                                    step="0.01"
                                                    placeholder="0.00"
                                                    aria-label="Uniform Allowance">
                                                </div>

                                                <div class="col-4 mb-3">
                                                <label class="form-label">PM (%)</label>
                                                    <input name="pm_allow"
                                                    class="form-control"
                                                    type="number"
                                                    step="0.01"
                                                    placeholder="0.00"
                                                    aria-label="PM Allowance">
                                                </div>

                                                <div class="col-4 mb-3">
                                                <label class="form-label">Holiday (%)</label>
                                                    <input name="holi_loading"
                                                    class="form-control"
                                                    type="number"
                                                    step="0.1"
                                                    placeholder="0%"
                                                    aria-label="Holiday Loading">
                                                </div>
                                            </div>

                                            <!-- 2 columns -->
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                <label class="form-label">Saturday Loading (%)</label>
                                                    <input name="sat_loading"
                                                    class="form-control"
                                                    type="number"
                                                    step="0.1"
                                                    placeholder="0%"
                                                    aria-label="Saturday Loading">
                                                </div>

                                                <div class="col-6 mb-3">
                                                <label class="form-label">Sunday Loading (%)</label>
                                                    <input name="sun_loading"
                                                    class="form-control"
                                                    type="number"
                                                    step="0.1"
                                                    placeholder="0%"
                                                    aria-label="Sunday Loading">
                                                </div>
                                            </div>


                                            <!-- 2 columns -->
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                <label class="form-label">Fringe ($)</label>
                                                    <input name="fringe"
                                                    class="form-control"
                                                    type="number"
                                                    step="0.01"
                                                    placeholder="0.00"
                                                    aria-label="Fringe">
                                                </div>

                                                <div class="col-6 mb-3">
                                                <label class="form-label">Tax ($)</label>
                                                    <input name="tax"
                                                    class="form-control"
                                                    type="number"
                                                    step="0.01"
                                                    placeholder="0.00"
                                                    aria-label="Tax">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="saveShiftTypeModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title">Confirm Save</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Save the selected shift type changes?
                                                </div>
                                                <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary" form="shiftTypeForm" formaction="payslipUpdateShiftTypeAction.php" formmethod="post">Save Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="deleteShiftTypeModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Delete the selected shift type? This cannot be undone.
                                                </div>
                                                <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger" form="shiftTypeForm" formaction="payslipDeleteShiftTypeAction.php" formmethod="post">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>


                            <!-- MAIN TABLE -->

                            <div class="col-12 col-lg-8 px-2 py-2 payslip-table-panel">

                                <div class="border rounded">

                                    <!-- HEADER -->

                                    <div class="card-header d-flex align-items-center justify-content-between py-2 shifts-header">
                                        <div>
                                            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#generatePayslipModal">
                                                Generate Payslip
                                            </button>
                                        </div>

                                    <h4 class="mb-0">Shifts</h4>

                                        <div>
                                        <button class="btn btn-outline-success" type="button" data-bs-toggle="modal" data-bs-target="#addShiftModal">Add Shift</button>
                                        </div>
                                    </div>

                                    <!-- MODAL -->
                                    <div class="modal fade" id="generatePayslipModal" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                <h5 class="modal-title">Generate Payslip</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <form method="post" action="payslipGenerateAction.php">
                                                    <div class="modal-body">
                                                        <input type="hidden"
                                                        name="user_code"
                                                        value="<?= htmlspecialchars($_SESSION['user_code']) ?>">

                                                        <div class="mb-3">
                                                        <label class="form-label">Start Date</label>
                                                            <input type="date" name="start_date" class="form-control">
                                                        </div>

                                                        <div class="mb-3">
                                                        <label class="form-label">End Date</label>
                                                            <input type="date" name="end_date" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-submit full">
                                                            Generate Payslip
                                                        </button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade payslip-modal" id="addShiftModal" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                <h5 class="modal-title">Add Shift</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <form method="post" action="payslipCreateShiftAction.php">
                                                    <div class="modal-body shift-form-grid">
                                                        <input type="hidden" name="user_code" value="<?= htmlspecialchars($_SESSION['user_code']) ?>">

                                                        <div class="col-row">
                                                            <div class="col-sm-6">
                                                            <label class="form-label">Date</label>
                                                                <input type="date" name="date" class="form-control">
                                                            </div>
                                                            <div class="col-sm-6">
                                                            <label class="form-label">Shift Type</label>
                                                                <select class="form-select" name="shift_type" aria-label="Shift type">
                                                                    <?= $renderShiftTypeOptions($shift_type_rows) ?>
                                                                </select>
                                                            </div>

                                                        </div>

                                                        <div class="row g-2 three-col-row">
                                                            <div class="col-sm-4 mb-2">
                                                            <label class="form-label">Start Time</label>
                                                                <input type="time" name="start_time" class="form-control">
                                                            </div>
                                                            <div class="col-sm-4 mb-2">
                                                            <label class="form-label">End Time</label>
                                                                <input type="time" name="end_time" class="form-control">
                                                            </div>
                                                            <div class="col-sm-4 mb-2">
                                                            <label class="form-label">Breaks (minutes)</label>
                                                                <input type="number" name="breaks" class="form-control" min="0" step="1">
                                                            </div>

                                                        </div>

                                                        <div class="row g-2 three-col-row">
                                                            <div class="col-sm-4 mb-2">
                                                            <label class="form-label">Laundry Allowance</label>
                                                                <input type="number" name="laundry" class="form-control shift-rate-field" step="0.01" placeholder="0.00">
                                                            </div>
                                                            <div class="col-sm-4 mb-2">
                                                            <label class="form-label">Uniform Allowance</label>
                                                                <input type="number" name="uniform" class="form-control shift-rate-field" step="0.01" placeholder="0.00">
                                                            </div>
                                                            <div class="col-sm-4 mb-2">
                                                            <label class="form-label">Rate</label>
                                                                <input type="number" name="rate" class="form-control" step="0.01" placeholder="0.00">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-12 d-flex flex-column align-items-center">
                                                            <label class="form-label">Holiday Shift</label>
                                                                <div class="form-check holiday-field">
                                                                    <input type="checkbox" name="is_holi" class="form-check-input" value="1">

                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="submit" class="btn btn-submit full">Add Shift</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- TABLE -->

                                    <div class="table-container">

                                        <table class="table table-striped table-hover mb-0">

                                            <thead class="table-light sticky-top">
                                                <tr>
                                                <th>Type</th>
                                                <th>Date</th>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Bre.</th>
                                                <th>Rate</th>
                                                <th>Lau.</th>
                                                <th>Uni.</th>
                                                <!-- <th>Shift Allow</th> -->
                                                <th>Hol.</th>
                                                <th>Operations</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                                $modals = '';
                                                if ($shift_records && $shift_records->num_rows > 0):
                                                        while ($row = $shift_records->fetch_assoc()): ?>
                                                            <tr>
                                                            <td><?= htmlspecialchars($row['shift_type_name'] ?? 'Unknown') ?></td>
                                                            <td><?= date("d M y", strtotime(htmlspecialchars($row['date']))) ?></td>
                                                            <td><?= date('g:i A', strtotime(htmlspecialchars($row['s_time']))) ?></td>
                                                            <td><?= date('g:i A', strtotime(htmlspecialchars($row['e_time']))) ?></td>
                                                            <td><?= htmlspecialchars($row['break']).' min' ?></td>
                                                            <td><?= '$'.htmlspecialchars($row['rate']) ?></td>
                                                            <td><?= '$'.htmlspecialchars($row['l_allowance']) ?></td>
                                                            <td><?= '$'.htmlspecialchars($row['u_allowance']) ?></td>

                                                            <td><?= '<input type="checkbox" ' . (!empty($row['is_holi']) ? 'checked' : '') . ' disabled>' ?></td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editShiftModal-<?= $row['id'] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                                                    </svg></button>
                                                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteShiftModal-<?= $row['id'] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                                                    </svg></button>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            // build modals separately so they are not inside the table
                                                            $modals .= '<div class="modal fade payslip-modal" id="editShiftModal-'.htmlspecialchars($row['id']).'" tabindex="-1" aria-hidden="true">'
                                                                .'<div class="modal-dialog modal-lg"><div class="modal-content">'
                                                                .'<div class="modal-header"><h5 class="modal-title">Edit Shift</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>'
                                                                    .'<form method="post" action="payslipUpdateShiftAction.php">
                                                                        <div class="modal-body shift-form-grid">
                                                                            <input type="hidden" name="id" value="'.htmlspecialchars($row['id']).'">
                                                                            <input type="hidden" name="user_code" value="'.htmlspecialchars($_SESSION['user_code']).'">

                                                                            <div class="row g-2 three-col-row">
                                                                                <div class="col-sm-4 mb-2">
                                                                                <label class="form-label">Date</label>
                                                                                    <input type="date" name="date" value="'.htmlspecialchars($row['date']).'" class="form-control">
                                                                                </div>
                                                                                <div class="col-sm-4 mb-2">
                                                                                <label class="form-label">Shift Type</label>
                                                                                    <select class="form-select shift-record-type-select" name="shift_type" aria-label="Shift type">
                                                                                        '.$renderShiftTypeOptions($shift_type_rows, $row['shift_type'] ?? '').'
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-sm-4 mb-2">
                                                                                <label class="form-label">Start Time</label>
                                                                                    <input type="time" name="start_time" value="'.htmlspecialchars($row['s_time']).'" class="form-control">
                                                                                </div>
                                                                            </div>

                                                                            <div class="row g-2 three-col-row">
                                                                                <div class="col-sm-4 mb-2">
                                                                                <label class="form-label">End Time</label>
                                                                                    <input type="time" name="end_time" value="'.htmlspecialchars($row['e_time']).'" class="form-control">
                                                                                </div>
                                                                                <div class="col-sm-4 mb-2">
                                                                                <label class="form-label">Breaks (minutes)</label>
                                                                                    <input type="number" name="breaks" value="'.htmlspecialchars($row['break']).'" class="form-control" min="0" step="1">
                                                                                </div>
                                                                                <div class="col-sm-4 mb-2">
                                                                                <label class="form-label">Rate</label>
                                                                                    <input type="number" name="rate" value="'.htmlspecialchars($row['rate']).'" class="form-control" step="0.01" placeholder="0.00">
                                                                                </div>
                                                                            </div>

                                                                            <div class="row g-2 three-col-row">
                                                                                <div class="col-sm-4 mb-2">
                                                                                <label class="form-label">Laundry Allowance</label>
                                                                                    <input type="number" name="laundry" value="'.htmlspecialchars($row['l_allowance'] ?? '').'" class="form-control shift-rate-field" step="0.01" placeholder="0.00">
                                                                                </div>
                                                                                <div class="col-sm-4 mb-2">
                                                                                <label class="form-label">Uniform Allowance</label>
                                                                                    <input type="number" name="uniform" value="'.htmlspecialchars($row['u_allowance'] ?? '').'" class="form-control shift-rate-field" step="0.01" placeholder="0.00">
                                                                                </div>
                                                                                <div class="col-sm-4 mb-2">
                                                                                <label class="form-label">Shift Allow</label>
                                                                                    <input type="number" name="shift_allow" value="'.htmlspecialchars($row['shift_allow'] ?? '').'" class="form-control" step="0.01" placeholder="0.00">
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mt-3">
                                                                                <div class="col-12 d-flex flex-column align-items-center">
                                                                                <label class="form-label">Holiday Shift</label>
                                                                                    <div class="form-check holiday-field">
                                                                                        <input type="checkbox" name="is_holi" class="form-check-input" value="1" '.(!empty($row['is_holi']) ? 'checked' : '').'>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <button type="submit" class="btn btn-submit full">Update</button>
                                                                        </div>
                                                                    </div>'
                                                                .'<div class="modal-footer"></div></form></div></div></div>';

                                                                    $modals .= '<div class="modal fade" id="deleteShiftModal-'.htmlspecialchars($row['id']).'" tabindex="-1" aria-hidden="true">'
                                                                        .'<div class="modal-dialog"><div class="modal-content">'
                                                                        .'<div class="modal-header"><h5 class="modal-title">Delete Shift</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>'
                                                                            .'<form method="post" action="payslipDeleteShiftAction.php"><div class="modal-body">'
                                                                                .'<input type="hidden" name="id" value="'.htmlspecialchars($row['id']).'">'
                                                                            .'<p>Are you sure you want to delete this shift? This cannot be undone.</p>'

                                                                            .'<div class="modal-footer"><button type="submit" class="btn btn-danger full">Delete</button></div></div></form></div></div></div>';

                                                                            endwhile;
                                                                            else: ?>
                                                                            <tr>
                                                                            <td colspan="11" class="text-center">No shifts found.</td>
                                                                            </tr>
                                                                            <?php endif; ?>
                                                                        </tbody>

                                                                    </table>

                                                                    <?php
                                                                    // output modals after the table
                                                                    if (!empty($modals)) echo $modals;
                                                                        ?>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                            <script>
                                                (() => {
                                                    const shiftTypes = <?= json_encode($shift_type_rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
                                                    const select = document.getElementById('shiftTypeSelect');
                                                    const fields = document.getElementById('selectedShiftTypeFields');
                                                    const hiddenId = document.getElementById('shiftTypeId');

                                                    if (!select || !fields || !hiddenId) {
                                                            return;
                                                        }

                                                        const fieldNames = [
                                                            'name',
                                                            'rate',
                                                            'deductable',
                                                            'l_allow',
                                                            'u_allow',
                                                            'pm_allow',
                                                            'holi_loading',
                                                            'sat_loading',
                                                            'sun_loading',
                                                            'fringe',
                                                            'tax'
                                                        ];

                                                        const applyShiftType = () => {
                                                            const selectedId = select.value;
                                                            const selectedShiftType = shiftTypes.find((shiftType) => String(shiftType.id) === String(selectedId));

                                                            hiddenId.value = selectedShiftType ? selectedShiftType.id : '';

                                                            fieldNames.forEach((fieldName) => {
                                                                const input = fields.querySelector(`[name="${fieldName}"]`);

                                                                if (!input) {
                                                                        return;
                                                                    }

                                                                    input.value = selectedShiftType ? (selectedShiftType[fieldName] ?? '') : '';
                                                            });
                                                        };

                                                        select.addEventListener('change', applyShiftType);
                                                        applyShiftType();

                                                        document.querySelectorAll('.shift-record-type-select').forEach((recordSelect) => {
                                                            recordSelect.addEventListener('change', () => {
                                                                const selectedShiftType = shiftTypes.find((shiftType) => String(shiftType.id) === String(recordSelect.value));
                                                                const form = recordSelect.closest('form');

                                                                if (!form || !selectedShiftType) {
                                                                        return;
                                                                    }

                                                                    const fieldMap = {
                                                                        rate: 'rate',
                                                                        laundry: 'l_allow',
                                                                        uniform: 'u_allow',
                                                                    };

                                                                    Object.entries(fieldMap).forEach(([recordField, typeField]) => {
                                                                        const input = form.querySelector(`[name="${recordField}"]`);

                                                                        if (input && selectedShiftType[typeField] !== undefined) {
                                                                                input.value = selectedShiftType[typeField] ?? '';
                                                                            }
                                                                    });
                                                            });
                                                    });
                                            })();
                                        </script>
                                        <div>
                                            <?php
                                            $conn->close();
                                            include_once "indexFooter.php"; ?>