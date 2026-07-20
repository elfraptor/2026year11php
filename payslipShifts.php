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
        SELECT *
        FROM shift_records
        WHERE user_code = '" . $_SESSION['user_code'] . "'
        AND status = '1'
        ");

        $conn->close();
        ?>

        <style>
            /* Small UI tweaks for payslip shifts */
            .payslip-card {box-shadow: 0 6px 18px rgba(0,0,0,0.06);}
            .table-container{height:460px; overflow:auto;}
            .left-panel .form-label{font-size:0.95rem}
            .left-panel .btn-group > .btn{min-width:72px}
            .left-panel .form-control{border-radius:6px}
            .shifts-header{min-height:64px}
            .modal .modal-body .form-label{font-weight:600}
            .table thead th{background:#fafafa}
        </style>

        <div class="container-fluid p-4">

            <!-- Page Title -->
            <div class="mb-4">
            <h3>Enter Shifts Page</h3>
            </div>

            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <!-- LEFT SIDEBAR -->
                        <div class="col-md-3 left-panel">
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

                                                    <div class="mb-3">
                                                    <label class="form-label">Shift Type Name</label>
                                                        <input class="form-control" type="text" name="shift_name" placeholder="Enter shift type name" aria-label="Shift Type Name">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">Rate</label>
                                                            <input class="form-control" type="number" name="rate" step="0.01" placeholder="0.00" aria-label="Rate">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">Deductable</label>
                                                            <input class="form-control" type="number" name="deductable" step="0.01" placeholder="0.00" aria-label="Deductable">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">Laundry Allowance</label>
                                                            <input class="form-control" name="l_allow" type="number" step="0.01" placeholder="0.00" aria-label="Laundry Allowance">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">Uniform Allowance</label>
                                                            <input class="form-control" name="u_allow" type="number" step="0.01" placeholder="0.00" aria-label="Uniform Allowance">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">Fringe</label>
                                                            <input class="form-control" name="fringe" type="number" step="0.01" placeholder="0.00" aria-label="Fringe">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">Tax</label>
                                                            <input class="form-control" name="tax" type="number" step="0.01" placeholder="0.00" aria-label="Tax">
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

                                <div class="mb-3 btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createShiftTypeModal">Create Shift Type</button>
                                <button class="btn btn-sm btn-outline-secondary">Save</button>
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </div>

                                <div class="mb-3">
                                <label class="form-label">Shift Type</label>
                                    <select class="form-select" id="shiftTypeSelect" name="shift_type_id" aria-label="Shift type">
                                    <option value="">Select...</option>
                                        <?php foreach ($shift_type_rows as $row): ?>
                                        <option value="<?= htmlspecialchars($row['id']) ?>">
                                            <?= htmlspecialchars($row['name']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div id="selectedShiftTypeFields">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Rate</label>
                                        <input name="rate" class="form-control" type="number" step="0.01" placeholder="0.00" aria-label="Rate">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Deductable</label>
                                        <input class="form-control" name="deductable" type="number" step="0.01" placeholder="0.00" aria-label="Deductable">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Laundry Allowance</label>
                                        <input class="form-control" name="l_allow" type="number" step="0.01" placeholder="0.00" aria-label="Laundry Allowance">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Uniform Allowance</label>
                                        <input class="form-control" name="u_allow" type="number" step="0.01" placeholder="0.00" aria-label="Uniform Allowance">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fringe</label>
                                        <input class="form-control" name="fringe" type="number" step="0.01" placeholder="0.00" aria-label="Fringe">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tax</label>
                                        <input class="form-control" name="tax" type="number" step="0.01" placeholder="0.00" aria-label="Tax">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- MAIN TABLE -->
                        <div class="col-md-9">

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
                                    <button class="btn btn-outline-success" type="submit"data-bs-toggle="modal" data-bs-target="#addShiftModal">Add Shift</button>
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
                                                    <input type="hidden" name="user_code" value="<?= htmlspecialchars($_SESSION['user_code']) ?>">
                                                    <div class="mb-3">
                                                    <label class="form-label">Start Date</label>
                                                        <input type="date" name="start_date" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                    <label class="form-label">End Date</label>
                                                        <input type="date" name="end_date" class="form-control">
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="submit" class="btn btn-submit full">Generate Payslip</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="addShiftModal" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                            <h5 class="modal-title">Add Shift</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <form method="post" action="payslipCreateShiftAction.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="user_code" value="<?= htmlspecialchars($_SESSION['user_code']) ?>">

                                                    <div class="mb-3">
                                                    <label class="form-label">Date</label>
                                                        <input type="date" name="date" class="form-control">
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">Start Time</label>
                                                            <input type="time" name="start_time" class="form-control">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">End Time</label>
                                                            <input type="time" name="end_time" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">Breaks (minutes)</label>
                                                            <input type="number" name="breaks" class="form-control" min="0" step="1">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">Rate</label>
                                                            <input type="number" name="rate" class="form-control" step="0.01" placeholder="0.00">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">Laundry Allowance</label>
                                                            <input type="number" name="laundry" class="form-control" step="0.01" placeholder="0.00">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">Uniform Allowance</label>
                                                            <input type="number" name="uniform" class="form-control" step="0.01" placeholder="0.00">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">Fringe</label>
                                                            <input type="number" name="fringe" class="form-control" step="0.01" placeholder="0.00">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                        <label class="form-label">Tax</label>
                                                            <input type="number" name="tax" class="form-control" step="0.01" placeholder="0.00">
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
                                            <th>Date</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Breaks</th>
                                            <th>Rate</th>
                                            <th>Laundry</th>
                                            <th>Uniform</th>
                                            <th>Fringe</th>
                                            <th>Tax</th>
                                            <th>Operations</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $modals = '';
                                            if ($shift_records && $shift_records->num_rows > 0):
                                                    while ($row = $shift_records->fetch_assoc()): ?>
                                                        <tr>
                                                        <td><?= htmlspecialchars($row['date']) ?></td>
                                                        <td><?= htmlspecialchars($row['s_time']) ?></td>
                                                        <td><?= htmlspecialchars($row['e_time']) ?></td>
                                                        <td><?= htmlspecialchars($row['break']) ?></td>
                                                        <td><?= htmlspecialchars($row['rate']) ?></td>
                                                        <td><?= htmlspecialchars($row['l_allowance']) ?></td>
                                                        <td><?= htmlspecialchars($row['u_allowance']) ?></td>
                                                        <td><?= htmlspecialchars($row['fringe']) ?></td>
                                                        <td><?= htmlspecialchars($row['tax']) ?></td>
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
                                                        $modals .= '<div class="modal fade" id="editShiftModal-'.htmlspecialchars($row['id']).'" tabindex="-1" aria-hidden="true">'
                                                            .'<div class="modal-dialog"><div class="modal-content">'
                                                            .'<div class="modal-header"><h5 class="modal-title">Edit Shift</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>'
                                                                .'<form method="post" action="payslipUpdateShiftAction.php"><div class="modal-body">'
                                                                    .'<input type="hidden" name="user_code" value="'.htmlspecialchars($_SESSION['user_code']).'">'
                                                                .'<div class="mb-2"><label>Date</label><input type="date" name="date" class="form-control" value="'.htmlspecialchars($row['date']).'"></div>'
                                                                    .'<div class="row">'
                                                                    .'<div class="col-md-6 mb-2"><label>Start Time</label><input type="time" name="start_time" class="form-control" value="'.htmlspecialchars($row['s_time']).'"></div>'
                                                                    .'<div class="col-md-6 mb-2"><label>End Time</label><input type="time" name="end_time" class="form-control" value="'.htmlspecialchars($row['e_time']).'"></div>'
                                                                    .'</div>'
                                                                    .'<div class="row">'
                                                                    .'<div class="col-md-6 mb-2"><label>Breaks (minutes)</label><input type="number" name="breaks" class="form-control" value="'.htmlspecialchars($row['break']).'"></div>'
                                                                    .'<div class="col-md-6 mb-2"><label>Rate</label><input type="text" name="rate" class="form-control" value="'.htmlspecialchars($row['rate']).'"></div>'
                                                                    .'</div>'
                                                                    .'<div class="row">'
                                                                    .'<div class="col-md-6 mb-2"><label>Laundry Allowance</label><input type="number" step="0.01" name="laundry" class="form-control" value="'.htmlspecialchars($row['l_allowance']).'"></div>'
                                                                    .'<div class="col-md-6 mb-2"><label>Uniform Allowance</label><input type="number" step="0.01" name="uniform" class="form-control" value="'.htmlspecialchars($row['u_allowance']).'"></div>'
                                                                    .'</div>'
                                                                    .'<div class="row">'
                                                                    .'<div class="col-md-6 mb-2"><label>Fringe</label><input type="number" step="0.01" name="fringe" class="form-control" value="'.htmlspecialchars($row['fringe']).'"></div>'
                                                                    .'<div class="col-md-6 mb-2"><label>Tax</label><input type="number" step="0.01" name="tax" class="form-control" value="'.htmlspecialchars($row['tax']).'"></div>'
                                                                    .'</div>'
                                                                .'</div><div class="modal-footer"><button type="submit" class="btn btn-submit full">Update</button></div></form></div></div></div>';

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
                                                                            <td colspan="10" class="text-center">No shifts found.</td>
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

                                                    if (!select || !fields) {
                                                        return;
                                                    }

                                                    const fieldNames = ['rate', 'deductable', 'l_allow', 'u_allow', 'fringe', 'tax'];

                                                    const applyShiftType = () => {
                                                        const selectedId = select.value;
                                                        const selectedShiftType = shiftTypes.find((shiftType) => String(shiftType.id) === String(selectedId));

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
                                                })();
                                            </script>

                                            <?php include_once "indexFooter.php"; ?>