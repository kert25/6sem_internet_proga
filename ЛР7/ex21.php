<?php
require_once 'common.php';

$age = filter_input(INPUT_GET, 'age', FILTER_VALIDATE_INT);
$result = null;

if ($age !== false && $age !== null) {
    // Условие задания: возраст от 18 до 30 лет включительно.
    $result = $age >= 18 && $age <= 30 ? 'Для студентов' : 'Для всех возрастов';
}

page_start('Задание 2.1 — Проверка возраста');
?>
<h1>Задание 2.1. Управление потоком с if</h1>
<p>При возрасте от 18 до 30 лет включительно программа выводит «Для студентов».</p>
<form method="get">
    <label for="age">Возраст</label>
    <input id="age" name="age" type="number" min="0" max="150" value="<?= $age !== false && $age !== null ? escape((string) $age) : '' ?>" required>
    <button type="submit">Проверить</button>
</form>
<?php if ($result !== null): ?>
    <p class="result success"><?= escape($result) ?></p>
<?php endif; ?>
<?php page_end(); ?>
