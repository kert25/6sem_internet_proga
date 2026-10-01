<?php
// Главная страница лабораторной работы №15.
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ЛР15 — Использование PHP</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container">
        <header class="hero">
            <p class="eyebrow">Лабораторная работа №15</p>
            <h1>Использование языка PHP</h1>
            <p>Набор программ по индивидуальным заданиям варианта 9.</p>
        </header>

        <section class="card">
            <h2>Первая программа на PHP</h2>
            <p class="result"><?php echo 'Это моя первая программа на PHP.'; ?></p>
        </section>

        <nav class="tasks" aria-label="Задания лабораторной работы">
            <a href="ex51.php"><strong>Задание 5.1</strong><span>Проверка email-адреса</span></a>
            <a href="ex91.php"><strong>Задание 9.1</strong><span>Настройки в сессии</span></a>
            <a href="ex41.php"><strong>Задание 4.1</strong><span>Функция размера текста</span></a>
            <a href="ex31.php"><strong>Задание 3.1</strong><span>Фильмы по жанрам</span></a>
            <a href="ex21.php"><strong>Задание 2.1</strong><span>Проверка возраста</span></a>
        </nav>
    </main>
</body>
</html>
