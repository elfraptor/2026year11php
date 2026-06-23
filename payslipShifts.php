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
        ");

        /* Shift Records */
        $shift_records = $conn->query("
        SELECT *
        FROM shift_records
        WHERE user_code = '" . $_SESSION['user_code'] . "'
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
                                            <form method="post" action="payslipShiftsAction.php">

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
                                            <button class="btn btn-sm btn-warning">Edit</button>
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                            </td>
                                        </tr>
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