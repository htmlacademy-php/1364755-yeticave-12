<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category) : ?>
            <li class="nav__item">
                <a href="all-lots.php?category_id=<?= $category['category_id'] ?>"><?= strip_tags($category['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<form class="form container <?= !empty($errors) ? 'form--invalid' : '' ?>" action="register.php" method="post" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= !empty($errors['email']) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" value="<?= !empty($data['email']) ? strip_tags($data['email']) : '' ?>"
         placeholder="Введите e-mail">
        <?php if (!empty($errors['email'])) : ?>
            <span class="form__error"><?= $errors['email'] ?></span>
        <?php endif; ?>
    </div>
    <div class="form__item <?= !empty($errors['password']) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" value="<?= !empty($data['password']) ? strip_tags($data['password']) : '' ?>"
         placeholder="Введите пароль">
        <?php if (!empty($errors['password'])) : ?>
            <span class="form__error"><?= $errors['password'] ?></span>
        <?php endif; ?>
    </div>
    <div class="form__item <?= !empty($errors['name']) ? 'form__item--invalid' : '' ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" value="<?= !empty($data['name']) ? strip_tags($data['name']) : '' ?>" placeholder="Введите имя">
        <?php if (!empty($errors['name'])) : ?>
            <span class="form__error"><?= $errors['name'] ?></span>
        <?php endif; ?>
    </div>
    <div class="form__item <?= !empty($errors['contacts']) ? 'form__item--invalid' : '' ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="contacts" placeholder="Напишите как с вами связаться"><?= !empty($data['contacts']) ? strip_tags($data['contacts']) : '' ?></textarea>
        <?php if (!empty($errors['contacts'])) : ?>
            <span class="form__error"><?= $errors['contacts'] ?></span>
        <?php endif; ?>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
