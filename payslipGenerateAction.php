<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['logged_in'])) {
            header('Location: indexLogin.php');
            exit;
        }

        include_once "indexHeader.php";
        $start_date = trim($_POST['start_date']);
        $end_date = trim($_POST['end_date']);
        $user_code = trim($_POST['user_code']);
        $query="SELECT shift_records.*, shift_types.name AS shift_type_name, shift_types.rate AS type_rate, shift_types.l_allow AS type_l_allow, shift_types.u_allow AS type_u_allow, shift_types.pm_allow, shift_types.sat_loading, shift_types.sun_loading, shift_types.holi_loading, shift_types.deductable AS type_deductable, shift_types.fringe AS type_fringe, shift_types.tax AS type_tax FROM `shift_records` LEFT JOIN `shift_types` ON `shift_types`.`id` = `shift_records`.`shift_type` AND `shift_types`.`user_code` = `shift_records`.`user_code` AND `shift_types`.`status`='1' WHERE `shift_records`.`status`='1' AND `shift_records`.`user_code`='".$_SESSION['user_code']."' AND `shift_records`.`date` BETWEEN '$start_date' AND '$end_date'";
        $conn=new mysqli($host, $user, $pass, $db);
        $result = mysqli_query($conn, $query);

        $totalHours = 0;
        $gross = 0;
        $laundry = 0;
        $uniform = 0;
        $loading = 0;
        $deductable = 0;
        $fringe=0;
        $tax=0;
        $shifts = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $payGroups = [];

        foreach ($shifts as $shift) {

                $start = strtotime($shift['date'].' '.$shift['s_time']);
                $end   = strtotime($shift['date'].' '.$shift['e_time']);

                if ($end < $start) {
                        $end = strtotime('+1 day', $end);
                    }

                    $hours = (($end - $start) / 3600) - ((float)$shift['break'] / 60);
                    $hours = max(0, $hours);

                    $rate = (float)($shift['rate'] ?? $shift['type_rate'] ?? 0);
                    $pay = $hours * $rate;

                    $lAllowance = (float)($shift['l_allowance'] ?? $shift['type_l_allow'] ?? 0);
                    $uAllowance = (float)($shift['u_allowance'] ?? $shift['type_u_allow'] ?? 0);
                    $shiftLoading = (float)($shift['shift_allow'] ?? 0);

                    $weekday = (int)date('N', strtotime($shift['date']));
                    $dayLoading = (float)($shift['pm_allow'] ?? 0);

                    if (!empty($shift['is_holi'])) {
                            $dayLoading = (float)($shift['holi_loading'] ?? $dayLoading);
                        } elseif ($weekday == 6) {
                            $dayLoading = (float)($shift['sat_loading'] ?? $dayLoading);
                        } elseif ($weekday == 7) {
                            $dayLoading = (float)($shift['sun_loading'] ?? $dayLoading);
                        }

                        $recordLoading = $shiftLoading;
                        $recordDeductable = (float)$shift['type_deductable'];
                        $recordFringe = (float)$shift['type_fringe'];
                        $recordTax = (float)$shift['type_tax'];

                        $group = $shift['shift_type_name'] ?: 'Unknown';

                        if (!isset($payGroups[$group])) {
                                $payGroups[$group] = [
                                    'hours' => 0,
                                    'rate' => $rate,
                                    'pay' => 0,
                                    'count' => 0,
                                    'laundry_loading' => $shift['l_allowance'] ?? $shift['type_l_allow'] ?? 0,
                                    'uniform_loading' => $shift['u_allowance'] ?? $shift['type_u_allow'] ?? 0,
                                    'uniform_pay' => 0,
                                    'laundry_pay' => 0,
                                    'fringe' => 0,
                                    'deductable' => 0,
                                    'tax' => 0,
                                    'sat_hours' => 0,
                                    'sun_hours' => 0,
                                    'holi_hours' => 0,
                                    'sat_loading' => $shift['sat_loading'] ?? 0,
                                    'sun_loading' => $shift['sun_loading'] ?? 0,
                                    'holi_loading' => $shift['holi_loading'] ?? 0,
                                    'sat_pay' => 0,
                                    'sun_pay' => 0,
                                    'holi_pay' => 0,


                                ];
                            }

                            $payGroups[$group]['hours'] += $hours;
                            $payGroups[$group]['pay'] += $pay;
                            $payGroups[$group]['count']++;
                            $payGroups[$group]['laundry_pay'] += $payGroups[$group]['laundry_loading'] * $hours;
                            $payGroups[$group]['uniform_pay'] += $payGroups[$group]['uniform_loading'] * $hours;
                            $payGroups[$group]['fringe'] += $recordFringe;
                            $payGroups[$group]['deductable'] += $recordDeductable;
                            $payGroups[$group]['sat_hours'] += ($weekday == 6) ? $hours : 0;
                            $payGroups[$group]['sun_hours'] += ($weekday == 7) ? $hours : 0;
                            $payGroups[$group]['holi_hours'] += (!empty($shift['is_holi'])) ? $hours : 0;
                            $payGroups[$group]['sat_pay'] += ($weekday == 6) ? $payGroups[$group]['sat_loading'] * $hours : 0;
                            $payGroups[$group]['sun_pay'] += ($weekday == 7) ? $payGroups[$group]['sun_loading'] * $hours : 0;
                            $payGroups[$group]['holi_pay'] += (!empty($shift['is_holi'])) ? $payGroups[$group]['holi_loading'] * $hours : 0;
                            $payGroups[$group]['tax'] += $recordTax;
                            $payGroups[$group]['total'] = $payGroups[$group]['pay'] + $payGroups[$group]['sat_pay'] + $payGroups[$group]['sun_pay'] + $payGroups[$group]['holi_pay'] + $payGroups[$group]['laundry_pay'] + $payGroups[$group]['uniform_pay'] + $payGroups[$group]['fringe'] - $payGroups[$group]['deductable'] - $payGroups[$group]['tax'];
                            // Totals
                            $totalHours += $hours;
                            $gross += $pay;
                            $laundry += $lAllowance;
                            $uniform += $uAllowance;
                            $loading += $recordLoading;
                            $deductable += $recordDeductable;
                            $fringe += $recordFringe;
                            $tax += $recordTax;
                        }

                        $grossPay = $gross + $laundry + $uniform + $fringe;


                        $netPay = $grossPay - ($tax + $deductable);

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
                                                <td class="text-end"><?= $data['count'] ?></td>
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
                                                <td class="text-end"><?= $data['count'] ?></td>
                                                <td class="text-end"><?= number_format($data['uniform_loading'], 2)?></td>
                                                <td class="text-end">100%</td>
                                                <td class="text-end"><?= number_format($data['uniform_pay'],2) ?></td>
                                                </tr>
                                                <?php endif; ?>
                                                <tr>
                                                    <?php if ($data['fringe'] > 0): ?>
                                                <td class="ps-1 text-muted">Fringe Benefits</td>
                                                <td class="text-end"><?= $data['count'] ?></td>
                                                <td class="text-end"><?= number_format($data['fringe'] / max($data['count'],1),2) ?></td>
                                                <td class="text-end">100%</td>
                                                <td class="text-end"><?= number_format($data['fringe'],2) ?></td>
                                                </tr>
                                                <?php endif; ?> <?php if ($data['sat_pay'] > 0): ?>
                                            <td class="ps-1 text-muted">Saturday Loading</td>
                                            <td class="text-end"><?= number_format($data['sat_hours'],2) ?></td>
                                            <td class="text-end"><?= number_format($data['rate'],2) ?></td>
                                            <td class="text-end"><?= number_format($data['sat_loading']*100,2) .'%'?></td>
                                            <td class="text-end"><?= number_format($data['sat_pay'],2) ?></td>
                                                <?php endif; ?> <?php if ($data['sun_pay'] > 0): ?>
                                            <td class="ps-1 text-muted">Sunday Loading</td>
                                            <td class="text-end"><?= number_format($data['sun_hours'],2) ?></td>
                                            <td class="text-end"><?= number_format($data['rate'],2) ?></td>
                                            <td class="text-end"><?= number_format($data['sun_loading']*100,2) .'%'?></td>
                                            <td class="text-end"><?= number_format($data['sun_pay'],2) ?></td>
                                                <?php endif; ?> <?php if ($data['holi_pay'] > 0): ?>
                                            <td class="ps-1 text-muted">Public Holiday Loading</td>
                                            <td class="text-end"><?= number_format($data['holi_hours'],2) ?></td>
                                            <td class="text-end"><?= number_format($data['rate'],2) ?></td>
                                            <td class="text-end"><?= number_format($data['holi_loading']*100,0) .'%'?></td>
                                            <td class="text-end"><?= number_format($data['holi_pay'],2) ?></td>
                                                <?php endif; ?>





                                                <tr>
                                                <td class="ps-1 text-danger">Deductable</td>
                                                <td class="text-end"><?= $data['count'] ?></td>
                                                <td class="text-end"><?= number_format($data['deductable'] / max($data['count'],1),2) ?></td>
                                                <td class="text-end">100%</td>
                                                    <td class="text-end text-danger">
                                                        -<?= number_format($data['deductable'],2) ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                <td class="ps-1 text-danger">Income Tax</td>
                                                <td class="text-end"><?= $data['count'] ?></td>
                                                <td class="text-end"><?= number_format($data['tax'] / max($data['count'],1),2) ?></td>
                                                <td class="text-end">100%</td>
                                                    <td class="text-end text-danger">
                                                        -<?= number_format($data['tax'],2) ?>
                                                    </td>
                                                </tr>

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