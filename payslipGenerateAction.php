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
        $query="SELECT * FROM `shift_records` WHERE `status`='1' AND `user_code`='".$_SESSION['user_code']."' AND `date` BETWEEN '$start_date' AND '$end_date'";
        echo $query;
        $conn=new mysqli($host, $user, $pass, $db);
        $result = mysqli_query($conn, $query);

$totalHours = 0;
$gross = 0;
$laundry = 0;
$uniform = 0;
$fringe=0;
$tax=0;
$shifts = mysqli_fetch_all($result, MYSQLI_ASSOC);
foreach ($shifts as $shift) {

    $start = strtotime($shift['date'].' '.$shift['s_time']);
    $end   = strtotime($shift['date'].' '.$shift['e_time']);

    if ($end < $start) {
        $end = strtotime('+1 day', $end);
    }

    $hours = (($end - $start) / 3600) - ($shift['break'] / 60);

    $pay = $hours * $shift['rate'];

    $totalHours += $hours;
    $gross += $pay;
    $laundry += $shift['l_allowance'];
    $uniform += $shift['u_allowance'];
    $fringe += $shift['fringe'];
    $tax += $shift['tax'];
}

$grossPay = $gross + $laundry + $uniform+$fringe;


$netPay = $grossPay - $tax;

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
                <?= number_format($shifts[0]['rate'],4) ?>
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
            <td class="text-end"><?= number_format($shifts[0]['l_allowance'],2) ?></td>
            <td class="text-end">100%</td>
            <td class="text-end"><?= number_format($laundry,2) ?></td>
        </tr>

        <tr>
            <td>Fringe Benefits</td>
            <td class="text-end"><?= count($shifts) ?></td>
            <td class="text-end"><?= number_format($shifts[0]['fringe'],2) ?></td>
            <td class="text-end">100%</td>
            <td class="text-end"><?= number_format($fringe,2) ?></td>
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
                -<?= number_format($tax,2) ?>
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