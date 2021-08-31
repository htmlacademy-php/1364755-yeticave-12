<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category) : ?>
            <li class="nav__item">
                <a href="all-lots.php?category_id=<?= $category['category_id'] ?>"><?= strip_tags($category['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2><?= strip_tags($lot['name']) ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot["img"] ?>" width="730" height="548" alt="">
            </div>
            <p class="lot-item__category">Категория: <span><?= strip_tags($lot['category_name']) ?></span></p>
            <p class="lot-item__description"><?= $lot['description'] ?></p>
        </div>
        <div class="lot-item__right">
            <?php if (isset($_SESSION['user'])) : ?>
                <div class="lot-item__state">
                    <div class="lot-item__timer timer
                        <?php $hours = get_hours($lot['date_end']) ?>
                        <?php if ($hours <= 0) : ?>
                            timer--finishing
                        <?php endif; ?>
                    ">
                        <?= get_date_range($lot['date_end']) ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= format_sum($current_price) ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= format_sum($min_bet) ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="lot.php?id=<?= $lot['lot_id'] ?>" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item <?= !empty($errors) ? 'form__item--invalid' : '' ?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="sum" value="<?= !empty($value) ? strip_tags($value) : '' ?>" placeholder="<?= format_sum($min_bet) ?>">
                            <?php if (!empty($errors)) : ?>
                                <span class="form__error"><?= $errors ?></span>
                            <?php endif; ?>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="history">
                <h3>История ставок (<span><?= count($bets_history) ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($bets_history as $history) : ?>
                        <tr class="history__item">
                            <td class="history__name"><?= $history['name'] ?></td>
                            <td class="history__price"><?= format_sum($history['sum']) ?></td>
                            <td class="history__time"><?= rate_dt_add($history['date_add']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>
