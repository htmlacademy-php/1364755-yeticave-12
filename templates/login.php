<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category) : ?>
            <li class="nav__item">
                <a href="all-lots.php?category_id=<?= $category['category_id'] ?>"><?= strip_tags($category['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<form class="form container <?= !empty($errors) ? 'form--invalid' : '' ?>" action="login.php" method="post">
    <h2>Вход</h2>
    <div class="form__item <?= !empty($errors['email']) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" value="<?= !empty($data['email']) ? strip_tags($data['email']) : '' ?>"
        placeholder="Введите e-mail">
        <?php if (!empty($errors['email'])) : ?>
            <span class="form__error"><?= $errors['email'] ?></span>
        <?php endif; ?>
    </div>
    <div class="form__item form__item--last <?= !empty($errors['password']) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" value="<?= !empty($data['password']) ? strip_tags($data['password']) : '' ?>"
        placeholder="Введите пароль">
        <?php if (!empty($errors['password'])) : ?>
            <span class="form__error"><?= $errors['password'] ?></span>
        <?php endif; ?>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
