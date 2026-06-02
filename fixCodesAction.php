<?php
$sql = "
UPDATE users u
JOIN (
    SELECT 
        id,
        UPPER(
            SUBSTRING(
                REGEXP_REPLACE(last_name, '[^A-Za-z0-9]', ''),
                1,
                3
            )
        ) AS prefix,
        ROW_NUMBER() OVER (
            PARTITION BY UPPER(
                SUBSTRING(
                    REGEXP_REPLACE(last_name, '[^A-Za-z0-9]', ''),
                    1,
                    3
                )
            )
            ORDER BY id
        ) AS sequence_num
    FROM users
) su ON u.id = su.id
SET u.code = CONCAT(su.prefix, LPAD(su.sequence_num, 4, '0'))
";
$conn->begin_transaction();

try {
    $conn->query($sql);
    $conn->commit();
    echo "Success";
} catch (Exception $e) {
    $conn->rollback();
    echo $e->getMessage();
}

$conn->close();
?>