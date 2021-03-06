<main class='container'>
    <section class='promo'>
        <h2 class='promo__title'>Нужен стафф для катки?</h2>
        <p class='promo__text'>На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное
            снаряжение.</p>
        <ul class='promo__list'>
            <!--заполните этот список из массива категорий-->
            <?php foreach($categories as $category): ?>
            <li class='promo__item promo__item--<?=$category['code']?>'>
                <a class='promo__link' href='pages/all-lots.html'><?=$category['name']?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class='lots'>
        <div class='lots__header'>
            <h2>Открытые лоты</h2>
        </div>
        <ul class='lots__list'>
            <?php foreach ($activeLots as $lot): ?>
                <!--заполните этот список из массива с товарами-->
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $lot['img'] ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category">
                            <?= $lot['categoryName'] ?>
                        </span>
                        <h3 class="lot__title">
                            <a class="text-link" href="pages/lot.html">
                                <?= $lot['title'] ?>
                            </a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost">
                                    <b>
                                        <?= $lot['endPrice'] ?>
                                    </b>
                                </span>
                            </div>
                            <div class="lot__timer timer<?php if ($lot['timeLeft'] <= 3600):?> timer--finishing<?php endif;?>">
                                <?php $days = floor($lot['timeLeft'] / (3600 * 24));?>
                                <?php if ($days > 1) :?>
                                <?= $days ?>
                                <?=' days'?>
                                <?php endif;?>
                                <?php if ($days === 1) :?>
                                <?= $days ?>
                                <?=' day'?>
                                <?php endif;?>
                                <?=gmdate('H:i', $lot['timeLeft'] % (3600 * 24))?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>
