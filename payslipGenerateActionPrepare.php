<?php
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
                            $payGroups[$group]['sat_pay'] += ($weekday == 6) ? $rate * ($payGroups[$group]['sat_loading']/100) * $hours : 0;
                            $payGroups[$group]['sun_pay'] += ($weekday == 7) ? $rate * ($payGroups[$group]['sun_loading']/100) * $hours : 0;
                            $payGroups[$group]['holi_pay'] += (!empty($shift['is_holi'])) ? $rate * ($payGroups[$group]['holi_loading']/100) * $hours : 0;
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