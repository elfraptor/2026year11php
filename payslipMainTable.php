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
                                                                <input type="time" name="start_time" class="form-control shift-start-time">
                                                            </div>
                                                            <div class="col-sm-4 mb-2">
                                                            <label class="form-label">End Time</label>
                                                                <input type="time" name="end_time" class="form-control shift-end-time">
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
                                                                                    <input type="time" name="start_time" value="'.htmlspecialchars($row['s_time']).'" class="form-control shift-start-time">
                                                                                </div>
                                                                            </div>

                                                                            <div class="row g-2 three-col-row">
                                                                                <div class="col-sm-4 mb-2">
                                                                                <label class="form-label">End Time</label>
                                                                                    <input type="time" name="end_time" value="'.htmlspecialchars($row['e_time']).'" class="form-control shift-end-time">
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