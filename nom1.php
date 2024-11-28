<?php
$data = [
    ['Иванов', 'Математика', 5],
    ['Иванов', 'Математика', 4],
    ['Иванов', 'Математика', 5],
    ['Петров', 'Математика', 5],
    ['Сидоров', 'Физика', 4],
    ['Иванов', 'Физика', 4],
    ['Петров', 'ОБЖ', 4],
];

// Преобразование данных для сводной таблицы
$result = array_reduce($data, function ($carry, $item) {
    $carry[$item[0]][$item[1]] = ($carry[$item[0]][$item[1]] ?? 0) + $item[2];
    return $carry;
}, []);

// Уникальные школьники и предметы
$students = array_keys($result);
$subjects = array_unique(array_merge(...array_map('array_keys', $result)));
sort($students);
sort($subjects);

// Вывод таблицы
echo '<table border="1">';
echo '<tr><th></th>';
foreach ($subjects as $subject) {
    echo "<th>$subject</th>";
}
echo '</tr>';

foreach ($students as $student) {
    echo "<tr><td>$student</td>";
    foreach ($subjects as $subject) {
        echo '<td>' . ($result[$student][$subject] ?? '') . '</td>';
    }
    echo '</tr>';
}
echo '</table>';
?>
