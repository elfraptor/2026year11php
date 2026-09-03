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

                                                        <div class="mb-3">
                                                        <label class="form-label">Shift Type Name</label>
                                                            <input name="shift_name"
                                                            class="form-control"
                                                            type="text"
                                                            maxlength="100"
                                                            placeholder="e.g. Weekday Full Time"
                                                            aria-label="Shift Type Name"
                                                            required>
                                                        </div>

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

                                            <div class="mb-3">
                                            <label class="form-label">Shift Type Name</label>
                                                <input name="name"
                                                id="shiftTypeName"
                                                class="form-control"
                                                type="text"
                                                maxlength="100"
                                                aria-label="Shift Type Name"
                                                required>
                                            </div>

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