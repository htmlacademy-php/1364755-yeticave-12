<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category) : ?>
            <li class="nav__item">
                <a href="all-lots.php?category_id=<?= $category['category_id'] ?>"><?= strip_tags($category['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($my_bets as $bet) : ?>
            <tr class="rates__item
            <?php if ($bet['user_id'] == $bet['user_win_id']) : ?>
                rates__item--win
            <?php elseif (get_date_range($bet['date_end']) == 'Торги окончены') : ?>
                rates__item--end
            <?php endif; ?>
            ">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= $bet['img'] ?>" width="54" height="40" alt="">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="lot.php?id=<?= $bet['lot_id'] ?>"><?= $bet['name'] ?></a></h3>
                        <?php if ($bet['user_id'] == $bet['user_win_id']) : ?>
                            <p><?= $bet['contacts'] ?></p>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__category">
                    <?= $bet['category_name'] ?>
                </td>
                <td class="rates__timer">
                    <div class="timer
                    <?php $hours = get_hours($bet['date_end']) ?>
                    <?php if ($hours <= 0) : ?>
                        timer--finishing
                    <?php elseif ($bet['user_id'] == $bet['user_win_id']) : ?>
                        timer--win
                    <?php elseif (get_date_range($bet['date_end']) == 'Торги окончены') : ?>
                        timer--end
                    <?php endif; ?>
                    ">
                    <?php if ($bet['user_id'] == $bet['user_win_id']) : ?>
                        Ставка выиграла
                    <?php else : ?>
                        <?= get_date_range($bet['date_end']) ?>
                    <?php endif; ?>
                    </div>
                </td>
                <td class="rates__price">
                    <?= format_sum($bet['sum']) ?>
                </td>
                <td class="rates__time">
                    <?= $bet['date_add'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
