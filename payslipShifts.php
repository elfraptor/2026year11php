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

                                <div class="mb-3 btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary">Add</button>
                                    <button class="btn btn-sm btn-outline-secondary">Save</button>
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Shift Type</label>
                                    <select class="form-select" aria-label="Shift type">
                                        <option value="">Select...</option>
                                        <?php while ($row = $shift_types->fetch_assoc()): ?>
                                        <option value="<?= htmlspecialchars($row['id'] ?? $row['shift_name']) ?>">
                                            <?= htmlspecialchars($row['shift_name']) ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Rate</label>
                                    <input class="form-control" type="number" step="0.01" placeholder="0.00" aria-label="Rate">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Laundry Allowance</label>
                                    <input class="form-control" type="number" step="0.01" placeholder="0.00" aria-label="Laundry Allowance">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Uniform Allowance</label>
                                    <input class="form-control" type="number" step="0.01" placeholder="0.00" aria-label="Uniform Allowance">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Fringe</label>
                                    <input class="form-control" type="number" step="0.01" placeholder="0.00" aria-label="Fringe">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tax</label>
                                    <input class="form-control" type="number" step="0.01" placeholder="0.00" aria-label="Tax">
                                </div>

                            </div>
                        </div>

                        <!-- MAIN TABLE -->
                        <div class="col-md-9">

                            <div class="border rounded">

                                <!-- HEADER -->

                                <div class="card-header d-flex align-items-center justify-content-between py-2 shifts-header">
                                    <div>
                                        <button class="btn btn-outline-secondary">
                                            Make Payslip
                                        </button>
                                    </div>

                                    <h4 class="mb-0">Shifts</h4>

                                    <div>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addShiftModal">Add Shift</button>
                                    </div>
                                </div>

                            <!-- MODAL -->
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
                                            <button type="submit" class="btn btn-primary w-100">Create</button>
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
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editShiftModal-<?= $row['id'] ?>">Edit</button>
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteShiftModal-<?= $row['id'] ?>">Delete</button>
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
                                                    .'<div class="mb-2"><label>Start Time</label><input type="time" name="start_time" class="form-control" value="'.htmlspecialchars($row['s_time']).'"></div>'
                                                    .'<div class="mb-2"><label>End Time</label><input type="time" name="end_time" class="form-control" value="'.htmlspecialchars($row['e_time']).'"></div>'
                                                    .'<div class="mb-2"><label>Breaks (minutes)</label><input type="number" name="breaks" class="form-control" value="'.htmlspecialchars($row['break']).'"></div>'
                                                    .'<div class="mb-2"><label>Rate</label><input type="text" name="rate" class="form-control" value="'.htmlspecialchars($row['rate']).'"></div>'
                                                    .'<div class="mb-2"><label>Laundry Allowance</label><input type="number" step="0.01" name="laundry" class="form-control" value="'.htmlspecialchars($row['l_allowance']).'"></div>'
                                                    .'<div class="mb-2"><label>Uniform Allowance</label><input type="number" step="0.01" name="uniform" class="form-control" value="'.htmlspecialchars($row['u_allowance']).'"></div>'
                                                    .'<div class="mb-2"><label>Fringe</label><input type="number" step="0.01" name="fringe" class="form-control" value="'.htmlspecialchars($row['fringe']).'"></div>'
                                                    .'<div class="mb-2"><label>Tax</label><input type="number" step="0.01" name="tax" class="form-control" value="'.htmlspecialchars($row['tax']).'"></div>'
                                                    .'</div><div class="modal-footer"><button type="submit" class="btn btn-primary w-100">Update</button></div></form></div></div></div>';

                                            $modals .= '<div class="modal fade" id="deleteShiftModal-'.htmlspecialchars($row['id']).'" tabindex="-1" aria-hidden="true">'
                                                    .'<div class="modal-dialog"><div class="modal-content">'
                                                    .'<div class="modal-header"><h5 class="modal-title">Delete Shift</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>'
                                                    .'<form method="post" action="payslipDeleteShiftAction.php"><div class="modal-body">'
                                                    .'<input type="hidden" name="id" value="'.htmlspecialchars($row['id']).'">'
                                                    .'<p>Are you sure you want to delete this shift? This cannot be undone.</p>'
                                                    .'<button type="submit" class="btn btn-danger">Delete</button></div>'
                                                    .'<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div></form></div></div></div>';

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

                <?php include_once "indexFooter.php"; ?>