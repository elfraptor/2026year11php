<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['logged_in'])) {
            header('Location: indexLogin.php');
            exit;
        }

        include_once "indexHeader.php";
        include_once "payslipGenerateActionPrepare.php";

                        ?>
                        <div class="container my-5">
                            <div class="card shadow-lg border-0 rounded-4">
                                <div class="card-header bg-primary text-white py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                        <h3 class="mb-0">Payslip</h3>
                                            <small><?= date('d M Y', strtotime($start_date)) ?>
                                                -
                                                <?= date('d M Y', strtotime($end_date)) ?>
                                            </small>
                                        </div>

                                        <div class="text-end">
                                        <div class="small">Employee</div>
                                        <strong><?= htmlspecialchars($_SESSION['f_name']) ?> <?= htmlspecialchars($_SESSION['l_name']) ?></strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="container mt-4">

                                        <table class="table table-hover align-middle">

                                            <thead class="table-light">
                                                <tr>
                                                <th>DESCRIPTION</th>
                                                <th class="text-end">UNITS</th>
                                                <th class="text-end">RATE</th>
                                                <th class="text-end">LOADING</th>
                                                <th class="text-end">AMOUNT</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                <?php foreach ($payGroups as $type => $data): ?>

                                                <!-- Shift Type Header -->
                                                <tr class="table-secondary">
                                                    <td colspan="5" class="fw-bold fs-5">
                                                        <?= htmlspecialchars($type) ?>
                                                    </td>
                                                </tr>


                                                <!-- Base Pay -->
                                                <tr class="ps-0 text-muted">
                                                <td>Employee Base Rate</td>
                                                <td class="text-end"><?= number_format($data['hours'],2) ?></td>
                                                <td class="text-end"><?= number_format($data['rate'],2) ?></td>
                                                <td class="text-end">100%</td>
                                                <td class="text-end"><?= number_format($data['pay'],2) ?></td>
                                                </tr>
                                                <?php if ($data['laundry_pay'] > 0): ?>
                                                <tr>

                                                    <td class="ps-1 text-muted">

                                                        Laundry Allowance
                                                    </td>
                                                <td class="text-end"><?= number_format($data['hours'],2) ?></td>
                                                <td class="text-end"><?= number_format($data['laundry_loading'],2) ?></td>
                                                <td class="text-end">100%</td>
                                                <td class="text-end"><?= number_format($data['laundry_pay'],2) ?></td>
                                                </tr>
                                                <?php endif; ?>
                                                <?php if ($data['uniform_pay'] > 0): ?>
                                                <tr>

                                                    <td class="ps-1 text-muted">
                                                        Uniform Allowance
                                                    </td>
                                                <td class="text-end"><?= number_format($data['hours'],2) ?></td>
                                                <td class="text-end"><?= number_format($data['uniform_loading'], 2)?></td>
                                                <td class="text-end">100%</td>
                                                <td class="text-end"><?= number_format($data['uniform_pay'],2) ?></td>
                                                </tr>
                                                <?php endif; ?>
                                                <tr>
                                                    <?php if ($data['fringe'] > 0): ?>
                                                <td class="ps-1 text-muted">Fringe Benefits</td>
                                                <td class="text-end"><?= number_format($data['hours'],2) ?></td>
                                                <td class="text-end"><?= number_format($data['fringe'] / max($data['hours'],1),2) ?></td>
                                                <td class="text-end">100%</td>
                                                <td class="text-end"><?= number_format($data['fringe'],2) ?></td>
                                                </tr>
                                                <?php endif; ?> <?php if ($data['sat_pay'] > 0): ?>
                                            <td class="ps-1 text-muted">Saturday Loading</td>
                                            <td class="text-end"><?= number_format($data['sat_hours'],2) ?></td>
                                            <td class="text-end"><?= number_format($data['rate']*($data['sat_loading']/100),0) ?></td>
                                            <td class="text-end"><?= number_format($data['sat_loading'],2) .'%'?></td>
                                            <td class="text-end"><?= number_format($data['sat_pay'],2) ?></td>
                                                <?php endif; ?> <?php if ($data['sun_pay'] > 0): ?>
                                            <td class="ps-1 text-muted">Sunday Loading</td>
                                            <td class="text-end"><?= number_format($data['sun_hours'],2) ?></td>
                                            <td class="text-end"><?= number_format($data['rate']*($data['sun_loading']/100),2) ?></td>
                                            <td class="text-end"><?= number_format($data['sun_loading'],0) .'%'?></td>
                                            <td class="text-end"><?= number_format($data['sun_pay'],2) ?></td>
                                                <?php endif; ?> <?php if ($data['holi_pay'] > 0): ?>
                                            <td class="ps-1 text-muted">Public Holiday Loading</td>
                                            <td class="text-end"><?= number_format($data['holi_hours'],2) ?></td>
                                            <td class="text-end"><?= number_format($data['rate']*($data['holi_loading']/100),2) ?></td>
                                            <td class="text-end"><?= number_format($data['holi_loading'],0) .'%'?></td>
                                            <td class="text-end"><?= number_format($data['holi_pay'],2) ?></td>
                                                <?php endif; ?>




<?php if ($data['deductable'] > 0): ?>
                                                <tr>
                                                <td class="ps-1 text-danger">Deductable</td>
                                                <td class="text-end"><?= number_format($data['hours'],2) ?></td>
                                                <td class="text-end"><?= number_format($data['deductable'] / max($data['hours'],1),2) ?></td>
                                                <td class="text-end">100%</td>
                                                    <td class="text-end text-danger">
                                                        -<?= number_format($data['deductable'],2) ?>
                                                    </td>
                                                </tr>
<?php endif; ?> <?php if ($data['tax'] > 0): ?>
                                                <tr>
                                                <td class="ps-1 text-danger">Income Tax</td>
                                                <td class="text-end"><?= number_format($data['hours'],2) ?></td>
                                                <td class="text-end"><?= number_format($data['tax'] / max($data['hours'],1),2) ?></td>
                                                <td class="text-end">100%</td>
                                                    <td class="text-end text-danger">
                                                        -<?= number_format($data['tax'],2) ?>
                                                    </td>
                                                </tr>
<?php endif; ?>
                                                <tr class="border-top border-2">
                                                    <td colspan="4" class="text-end fw-bold">
                                                        Subtotal for <?= htmlspecialchars($type) ?>
                                                    </td>

                                                    <td class="text-end fw-bold text-primary fs-5">
                                                        <?= number_format($payGroups[$type]['total'],2) ?>
                                                    </td>
                                                </tr>


                                            </tr>

                                            <tr>
                                            <td colspan="5" class="py-2 border-0"></td>
                                            </tr>

                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>

                                    <div class="row mt-4">

                                        <div class="col-md-4">
                                            <div class="card bg-light border-0 shadow-sm">
                                                <div class="card-body">
                                                <small class="fw-bold text-black">Gross Pay</small>
                                                    <h3 class="text-primary mb-0">
                                                        $<?= number_format($grossPay,2) ?>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card bg-light border-0 shadow-sm">
                                                <div class="card-body">
                                                <small class="fw-bold text-black">Deductions</small>
                                                    <h3 class="text-danger mb-0">
                                                        $<?= number_format($tax+$deductable,2) ?>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card bg-success border-0 text-white shadow-sm">
                                                <div class="card-body">
                                                <small class="fw-bold">Nett Pay</small>
                                                    <h3 class="mb-0">
                                                        $<?= number_format($netPay,2) ?>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <?php
                            $conn->close();
                            include_once "indexFooter.php";
                            ?>