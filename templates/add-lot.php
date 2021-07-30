<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category) : ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= strip_tags($category['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<form class="form form--add-lot container <?= !empty($errors) ? 'form--invalid' : '' ?>" action="add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?= !empty($errors['name']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="name" value="<?= !empty($data['name']) ? strip_tags($data['name']) : '' ?>"
             placeholder="Введите наименование лота">
            <?php if (!empty($errors['name'])) : ?>
                <span class="form__error"><?= $errors['name'] ?></span>
            <?php endif; ?>
        </div>
        <div class="form__item <?= !empty($errors['category_id']) ? 'form__item--invalid' : '' ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category_id">
                <option value="">Выберите категорию</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['category_id'] ?>"
                    <?php if ($category['category_id'] == $data['category_id']) : ?>
                        selected
                    <?php endif; ?>>
                        <?= strip_tags($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['category_id'])) : ?>
                <span class="form__error"><?= $errors['category_id'] ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="form__item form__item--wide <?= !empty($errors['description']) ? 'form__item--invalid' : '' ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="description" placeholder="Напишите описание лота"><?= !empty($data['description']) ? strip_tags($data['description']) : '' ?></textarea>
        <?php if (!empty($errors['description'])) : ?>
            <span class="form__error"><?= $errors['description'] ?></span>
        <?php endif; ?>
    </div>
    <div class="form__item form__item--file <?= !empty($errors['image']) ? 'form__item--invalid' : '' ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="image" id="lot-img" value="">
            <label for="lot-img">
                Добавить
            </label>
            <?php if (!empty($errors['image'])) : ?>
                <span class="form__error"><?= $errors['image'] ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?= !empty($errors['starting_price']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="starting_price" value="<?= !empty($data['starting_price']) ? strip_tags($data['starting_price']) : '' ?>"
             placeholder="0">
            <?php if (!empty($errors['starting_price'])) : ?>
                <span class="form__error"><?= $errors['starting_price'] ?></span>
            <?php endif; ?>
        </div>
        <div class="form__item form__item--small <?= !empty($errors['bet_step']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="bet_step" value="<?= !empty($data['bet_step']) ? strip_tags($data['bet_step']) : '' ?>"
            placeholder="0">
            <?php if (!empty($errors['bet_step'])) : ?>
                <span class="form__error"><?= $errors['bet_step'] ?></span>
            <?php endif; ?>
        </div>
        <div class="form__item <?= !empty($errors['date_end']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="date_end"
            value="<?= !empty($data['date_end']) ? strip_tags($data['date_end']) : '' ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <?php if (!empty($errors['date_end'])) : ?>
                <span class="form__error"><?= $errors['date_end'] ?></span>
            <?php endif; ?>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
