<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category) : ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= strip_tags($category['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= strip_tags($search) ?></span>»</h2>
        <ul class="lots__list">
            <?php if (empty($lots)) : ?>
                <span>Ничего не найдено по вашему запросу</span>
            <?php endif; ?>
            <?php foreach ($lots as $lot) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $lot['img'] ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= strip_tags($lot['category_name']) ?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $lot['lot_id'] ?>"><?= strip_tags($lot['lot_name']) ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= format_sum($lot['starting_price']) ?></span>
                            </div>
                            <div class="lot__timer timer
                            <?php $hours = get_hours($lot['date_end']) ?>
                            <?php if ($hours <= 0) : ?>
                                timer--finishing
                            <?php endif; ?>">
                                <?= get_date_range($lot['date_end']) ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php if (count($pages) > 1) : ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
                <?php if ($current_page > 1) : ?>
                    <a href="/search.php?search=<?= strip_tags($search) ?>&page=<?= $current_page - 1 ?>">Назад</a>
                <?php else : ?>
                    <a>Назад</a>
                <?php endif; ?>
            </li>
            <?php foreach ($pages as $page) : ?>
                <li class="pagination-item <?= $page == $current_page ? 'pagination-item-active' : '' ?>">
                    <a href="/search.php?search=<?= strip_tags($search) ?>&page=<?= $page; ?>"><?= $page ?></a>
                </li>
            <?php endforeach; ?>
            <li class="pagination-item pagination-item-next">
                <?php if ($current_page < count($pages)) : ?>
                    <a href="/search.php?search=<?= strip_tags($search) ?>&page=<?= $current_page + 1 ?>">Вперед</a>
                <?php else : ?>
                    <a>Вперед</a>
                <?php endif; ?>
            </li>
        </ul>
    <?php endif; ?>
</div>
