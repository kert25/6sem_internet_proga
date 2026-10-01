<?php
require_once 'common.php';

$email = '';
$message = null;
$isValid = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $isValid = str_contains($email, '@');
    $message = $isValid
        ? 'Адрес содержит символ @.'
        : 'Предупреждение: в адресе электронной почты отсутствует символ @.';
}

page_start('Задание 5.1 — Проверка email');
?>
<h1>Задание 5.1. Проверка email-адреса</h1>
<p>Программа проверяет наличие символа <code>@</code> в введённом адресе.</p>
<form method="post" novalidate>
    <label for="email">Адрес электронной почты</label>
    <input id="email" name="email" type="text" value="<?= escape($email) ?>" placeholder="name@example.com">
    <button type="submit">Проверить</button>
</form>
<?php if ($message !== null): ?>
    <p class="result <?= $isValid ? 'success' : 'warning' ?>"><?= escape($message) ?></p>
<?php endif; ?>
<?php page_end(); ?>
