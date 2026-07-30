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
    } elseif ($weekday === 6) {
        $dayLoading = (float)($shift['sat_loading'] ?? $dayLoading);
    } elseif ($weekday === 7) {
        $dayLoading = (float)($shift['sun_loading'] ?? $dayLoading);
    }

    $recordLoading = $shiftLoading + $dayLoading;
    $recordDeductable = (float)($shift['type_deductable'] ?? 0);
    $recordFringe = (float)($shift['type_fringe'] ?? 0);
    $recordTax = (float)($shift['type_tax'] ?? 0);

    $totalHours += $hours;
    $gross += $pay;
    $laundry += $lAllowance;
    $uniform += $uAllowance;
    $loading += $recordLoading;
    $deductable += $recordDeductable;
    $fringe += $recordFringe;
    $tax += $recordTax;
}

$grossPay = $gross + $laundry + $uniform + $loading + $fringe;


$netPay = $grossPay - ($tax + $deductable);

?>
<div class="container mt-4">

    <table class="table table-borderless table-sm">

        <thead class="border-bottom">
        <tr class="fw-bold text-secondary">
            <th>DESCRIPTION</th>
            <th class="text-end">UNITS</th>
            <th class="text-end">RATE</th>
            <th class="text-end">LOADING</th>
            <th class="text-end">PAYMENT</th>
        </tr>
        </thead>

        <tbody>

        <tr>
            <td class="fw-bold">
                Payslip for <?= $_SESSION['f_name'] ?> <?= $_SESSION['l_name'] ?> <br>
            </td>

            <td class="text-end">
                <?= number_format($totalHours,2) ?>
            </td>

            <td class="text-end">
                <?= isset($shifts[0]) ? number_format((float)($shifts[0]['rate'] ?? $shifts[0]['type_rate'] ?? 0),4) : '0.0000' ?>
            </td>

            <td class="text-end">
                100%
            </td>

            <td class="text-end">
                <?= number_format($gross,2) ?>
            </td>
        </tr>

        <tr>
            <td>Laundry Allowance</td>
            <td class="text-end"><?= count($shifts) ?></td>
            <td class="text-end"><?= isset($shifts[0]) ? number_format((float)($shifts[0]['l_allowance'] ?? $shifts[0]['type_l_allow'] ?? 0),2) : '0.00' ?></td>
            <td class="text-end">100%</td>
            <td class="text-end"><?= number_format($laundry,2) ?></td>
        </tr>

        <tr>
            <td>Uniform Allowance</td>
            <td class="text-end"><?= count($shifts) ?></td>
            <td class="text-end"><?= isset($shifts[0]) ? number_format((float)($shifts[0]['u_allowance'] ?? $shifts[0]['type_u_allow'] ?? 0),2) : '0.00' ?></td>
            <td class="text-end">100%</td>
            <td class="text-end"><?= number_format($uniform,2) ?></td>
        </tr>

        <tr>
            <td>Loading / Shift Allowance</td>
            <td class="text-end"><?= count($shifts) ?></td>
            <td class="text-end"><?= isset($shifts[0]) ? number_format((float)($shifts[0]['shift_allow'] ?? 0),2) : '0.00' ?></td>
            <td class="text-end">100%</td>
            <td class="text-end"><?= number_format($loading,2) ?></td>
        </tr>

        <tr>
            <td>Fringe Benefits</td>
            <td class="text-end"><?= count($shifts) ?></td>
            <td class="text-end"><?= isset($shifts[0]) ? number_format((float)($shifts[0]['type_fringe'] ?? 0),2) : '0.00' ?></td>
            <td class="text-end">100%</td>
            <td class="text-end"><?= number_format($fringe,2) ?></td>
        </tr>

        <tr>
            <td>Deductable</td>
            <td class="text-end"><?= count($shifts) ?></td>
            <td class="text-end"><?= isset($shifts[0]) ? number_format((float)($shifts[0]['type_deductable'] ?? 0),2) : '0.00' ?></td>
            <td class="text-end">100%</td>
            <td class="text-end text-danger">-<?= number_format($deductable,2) ?></td>
        </tr>

        <tr class="border-top fw-bold">
            <td>TOTAL GROSS</td>
            <td colspan="3"></td>
            <td class="text-end">
                <?= number_format($grossPay,2) ?>
            </td>
        </tr>

        <tr>
            <td>Income Tax Deduction</td>
            <td colspan="3"></td>
            <td class="text-end text-danger">
                -<?= number_format($tax,2) ?>
            </td>
        </tr>

        <tr class="fw-bold">
            <td>TOTAL DEDUCTIONS</td>
            <td colspan="3"></td>
            <td class="text-end text-danger">
                -<?= number_format($tax + $deductable,2) ?>
            </td>
        </tr>

        <tr class="border-top fw-bold fs-5">
            <td>NETT PAY</td>
            <td colspan="3"></td>
            <td class="text-end">
                $<?= number_format($netPay,2) ?>
            </td>
        </tr>

        </tbody>

    </table>

</div>
<?php
$conn->close();
include_once "indexFooter.php";
?>