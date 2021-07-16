<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category) : ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= strip_tags($category['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<form class="form container <?php if (!empty($errors)) : ?>form--invalid<?php endif; ?>" action="register.php" method="post" enctype="multipart/form-data" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?php if (!empty($errors['email'])) : ?>form__item--invalid<?php endif; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" value="<?= strip_tags(get_post_value('email')) ?>" placeholder="Введите e-mail">
        <?php if (!empty($errors['email'])) : ?>
            <span class="form__error"><?= $errors['email'] ?></span>
        <?php endif; ?>
    </div>
    <div class="form__item <?php if (!empty($errors['password'])) : ?>form__item--invalid<?php endif; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" value="<?= strip_tags(get_post_value('password')) ?>" placeholder="Введите пароль">
        <?php if (!empty($errors['password'])) : ?>
            <span class="form__error"><?= $errors['password'] ?></span>
        <?php endif; ?>
    </div>
    <div class="form__item <?php if (!empty($errors['name'])) : ?>form__item--invalid<?php endif; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" value="<?= strip_tags(get_post_value('name')) ?>" placeholder="Введите имя">
        <?php if (!empty($errors['name'])) : ?>
            <span class="form__error"><?= $errors['name'] ?></span>
        <?php endif; ?>
    </div>
    <div class="form__item <?php if (!empty($errors['contacts'])) : ?>form__item--invalid<?php endif; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="contacts" placeholder="Напишите как с вами связаться"><?= strip_tags(get_post_value('contacts')) ?></textarea>
        <?php if (!empty($errors['contacts'])) : ?>
            <span class="form__error"><?= $errors['contacts'] ?></span>
        <?php endif; ?>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
