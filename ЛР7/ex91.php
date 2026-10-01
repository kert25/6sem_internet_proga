<?php
session_start();
require_once 'common.php';

$colors = [
    'white' => 'Белый',
    'aliceblue' => 'Голубой',
    'honeydew' => 'Светло-зелёный',
    'lavenderblush' => 'Светло-розовый',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = trim((string) ($_POST['nickname'] ?? ''));
    $color = (string) ($_POST['color'] ?? 'white');

    if ($nickname !== '') {
        $_SESSION['nickname'] = $nickname;
    }
    if (array_key_exists($color, $colors)) {
        $_SESSION['background'] = $color;
    }

    header('Location: ex91.php');
    exit;
}

$nickname = (string) ($_SESSION['nickname'] ?? 'Гость');
$currentColor = (string) ($_SESSION['background'] ?? 'white');
if (!array_key_exists($currentColor, $colors)) {
    $currentColor = 'white';
}

page_start('Задание 9.1 — Настройки в сессии');
?>
<h1>Задание 9.1. Персональные настройки в сессии</h1>
<p>Текущая сессия хранит ник пользователя и выбранный фон страницы.</p>
<p class="result">Здравствуйте, <strong><?= escape($nickname) ?></strong>! Текущий фон: <?= escape($colors[$currentColor]) ?>.</p>
<form method="post" style="background-color: <?= escape($currentColor) ?>;">
    <label for="nickname">Ник</label>
    <input id="nickname" name="nickname" type="text" maxlength="40" value="<?= escape($nickname === 'Гость' ? '' : $nickname) ?>" required>
    <label for="color">Цвет фона</label>
    <select id="color" name="color">
        <?php foreach ($colors as $value => $label): ?>
            <option value="<?= escape($value) ?>" <?= $value === $currentColor ? 'selected' : '' ?>><?= escape($label) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Сохранить настройки</button>
</form>
<?php page_end(); ?>
