<?php
require_once 'common.php';

// Ассоциативный массив: ключ — жанр, значение — список фильмов.
$moviesByGenre = [
    'Мелодрама' => ['Ла-Ла Ленд', 'До встречи с тобой'],
    'Боевик' => ['Безумный Макс: Дорога ярости', 'Джон Уик'],
    'Детектив' => ['Достать ножи', 'Семь'],
    'Фантастика' => ['Интерстеллар', 'Прибытие'],
];

page_start('Задание 3.1 — Массив фильмов');
?>
<h1>Задание 3.1. Фильмы, организованные по жанрам</h1>
<p>Создан ассоциативный массив, в котором ключами являются названия жанров.</p>
<div class="movie-grid">
    <?php foreach ($moviesByGenre as $genre => $movies): ?>
        <article class="genre">
            <h2><?= escape($genre) ?></h2>
            <ul>
                <?php foreach ($movies as $movie): ?>
                    <li><?= escape($movie) ?></li>
                <?php endforeach; ?>
            </ul>
        </article>
    <?php endforeach; ?>
</div>
<?php page_end(); ?>
