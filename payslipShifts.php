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

        <div class="container-fluid p-4">

            <!-- Page Title -->
            <div class="mb-4">
            <h3>Enter Shifts Page</h3>
            </div>

            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <!-- LEFT SIDEBAR -->
                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100">

                            <h6 class="mb-3">Fixed Rates</h6>

                                <div class="mb-3 d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary">+</button>
                                <button class="btn btn-sm btn-outline-secondary">Save</button>
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </div>

                                <div class="mb-3">
                                <label class="form-label">Shift Type</label>
                                    <select class="form-select">
                                    <option>Select...</option>
                                        <?php while ($row = $shift_types->fetch_assoc()): ?>
                                        <option>
                                            <?= htmlspecialchars($row['shift_name']) ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                <label class="form-label">Rate</label>
                                    <input class="form-control" type="number">
                                </div>

                                <div class="mb-3">
                                <label class="form-label">Laundry Allowance</label>
                                    <input class="form-control" type="number">
                                </div>

                                <div class="mb-3">
                                <label class="form-label">Uniform Allowance</label>
                                    <input class="form-control" type="number">
                                </div>

                                <div class="mb-3">
                                <label class="form-label">Fringe</label>
                                    <input class="form-control" type="number">
                                </div>

                                <div class="mb-3">
                                <label class="form-label">Tax</label>
                                    <input class="form-control" type="number">
                                </div>

                            </div>
                        </div>

                        <!-- MAIN TABLE -->
                        <div class="col-md-9">

                            <div class="border rounded">

                                <!-- HEADER -->
                                <div class="card-header position-relative py-3">

                                    <button class="btn btn-outline-secondary position-absolute start-0 top-50 translate-middle-y ms-3">
                                        Make Payslip
                                    </button>

                                <h4 class="text-center mb-0">Shifts</h4>

                                    <button class="btn btn-primary position-absolute end-0 top-50 translate-middle-y me-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addShiftModal">
                                    Add Shift
                                </button>

                            </div>

                            <!-- MODAL -->
                            <div class="modal fade" id="addShiftModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                        <h5 class="modal-title">Add Shift</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <form method="post" action="payslipCreateShiftAction.php">

                                                <div class="mb-2">
                                                    <input type="hidden" name="user_code" value="<?= htmlspecialchars($_SESSION['user_code']) ?>">

                                                <label>Date</label>
                                                    <input type="date" name="date" class="form-control">
                                                </div>
                                                <div class="mb-2">
                                                <label>Start Time</label>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="time" name="start_time" class="form-control">
                                                </div>
                                                <div class="mb-2">
                                                <label>End Time</label>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="time" name="end_time" class="form-control">
                                                </div>
                                                <div class="mb-2">
                                                <label>Breaks (minutes)</label>
                                                    <input type="number" name="breaks" class="form-control">
                                                </div>
                                                <div class="mb-2">
                                                <label>Rate</label>
                                                    <input type="float" name="rate" class="form-control">
                                                </div>

                                                <div class="mb-2">
                                                <label>Laundry Allowance</label>
                                                    <input type="float" name="laundry" class="form-control">
                                                </div>
                                                <div class="mb-2">
                                                <label>Uniform Allowance</label>
                                                    <input type="float" name="uniform" class="form-control">
                                                </div>
                                                <div class="mb-2">
                                                <label>Fringe</label>
                                                    <input type="float" name="fringe" class="form-control">
                                                </div>
                                                <div class="mb-2">
                                                <label>Tax</label>
                                                    <input type="float" name="tax" class="form-control">
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100">
                                                    Create
                                                </button>

                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- TABLE -->
                            <div style="height:500px; overflow:auto;">

                                <table class="table table-striped mb-0">

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
                                        <?php if ($shift_records && $shift_records->num_rows > 0): ?>
                                        <?php while ($row = $shift_records->fetch_assoc()): ?>
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

                                        <div class="modal fade" id="editShiftModal-<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editShiftModalLabel-<?= $row['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editShiftModalLabel-<?= $row['id'] ?>">Edit Shift</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="post" action="payslipUpdateShiftAction.php">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="user_code" value="<?= $_SESSION['user_code'] ?>">
                                                            <div class="mb-2">
                                                                <label>Date</label>
                                                                <input type="date" name="date" class="form-control" value="<?= $row['date'] ?>">
                                                            </div>
                                                            <div class="mb-2">
                                                                <label>Start Time</label>
                                                                <input type="time" name="start_time" class="form-control" value="<?= $row['s_time'] ?>">
                                                            </div>
                                                            <div class="mb-2">
                                                                <label>End Time</label>
                                                                <input type="time" name="end_time" class="form-control" value="<?= $row['e_time'] ?>">
                                                            </div>
                                                            <div class="mb-2">
                                                                <label>Breaks (minutes)</label>
                                                                <input type="number" name="breaks" class="form-control" value="<?= $row['break'] ?>">
                                                            </div>
                                                            <div class="mb-2">
                                                                <label>Rate</label>
                                                                <input type="float" name="rate" class="form-control" value="<?= htmlspecialchars($row['rate']) ?>">
                                                            </div>
                                                            <div class="mb-2">
                                                                <label>Laundry Allowance</label>
                                                                <input type="number" step="0.01" name="laundry" class="form-control" value="<?= htmlspecialchars($row['l_allowance']) ?>">
                                                            </div>
                                                            <div class="mb-2">
                                                                <label>Uniform Allowance</label>
                                                                <input type="number" step="0.01" name="uniform" class="form-control" value="<?= htmlspecialchars($row['u_allowance']) ?>">
                                                            </div>
                                                            <div class="mb-2">
                                                                <label>Fringe</label>
                                                                <input type="number" step="0.01" name="fringe" class="form-control" value="<?= htmlspecialchars($row['fringe']) ?>">
                                                            </div>
                                                            <div class="mb-2">
                                                                <label>Tax</label>
                                                                <input type="number" step="0.01" name="tax" class="form-control" value="<?= htmlspecialchars($row['tax']) ?>">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary w-100">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="deleteShiftModal-<?= $row['id'] ?>" tabindex="-1" aria-labelledby="deleteShiftModalLabel-<?= $row['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteShiftModalLabel-<?= $row['id'] ?>">Delete Shift</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="post" action="payslipDeleteShiftAction.php">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                            <label>Are you sure you want to delete this shift? This cannot be undone.</label>
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            
                                                        </div>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                                    <?php endwhile; ?>
                                                    <?php else: ?>
                                                    <tr>
                                                        <td colspan="10" class="text-center">
                                                            No shifts found.
                                                        </td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>

                <?php include_once "indexFooter.php"; ?>