<?php
require_once 'common.php';

// Функция выводит текст с размером шрифта, переданным вторым аргументом.
function print_sized_text(string $text, int $size): void
{
    $safeSize = max(12, min($size, 72));
    echo '<p class="sized-text" style="font-size: ' . $safeSize . 'px">' . escape($text) . '</p>';
}

$text = (string) ($_GET['text'] ?? 'Текст, выведенный PHP-функцией');
$size = filter_input(INPUT_GET, 'size', FILTER_VALIDATE_INT);
$size = $size === false || $size === null ? 28 : $size;

page_start('Задание 4.1 — Функция размера текста');
?>
<h1>Задание 4.1. Функция изменения размера текста</h1>
<p>Функция <code>print_sized_text()</code> принимает строку и размер шрифта в пикселях.</p>
<form method="get">
    <label for="text">Текст</label>
    <input id="text" name="text" type="text" value="<?= escape($text) ?>">
    <label for="size">Размер шрифта, px</label>
    <input id="size" name="size" type="number" min="12" max="72" value="<?= escape((string) $size) ?>">
    <button type="submit">Вывести текст</button>
</form>
<div class="preview">
    <?php print_sized_text($text, $size); ?>
</div>
<?php page_end(); ?>
