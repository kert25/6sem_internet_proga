<?php
// Общие функции оформления и экранирования данных для страниц работы.
function page_start(string $title): void
{
    ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container">
        <p><a class="back-link" href="index.php">← К списку заданий</a></p>
        <section class="card">
    <?php
}

function page_end(): void
{
    ?>
        </section>
    </main>
</body>
</html>
    <?php
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
