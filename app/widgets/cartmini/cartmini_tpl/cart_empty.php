<p>На данный момент ваша корзина пуста.</p>
<p>Оформить заказ вы можете следующими способами:</p>
<ol>
    <li>Перейти в каталог и выбрать товар
        <ul class="nav mt-1 mb-1">
            <li class="nav-item">
                <a href="<?= \ishop\Link::getLink('women'); ?>" class="js-link-true2">Женщинам</a>
            </li>
            <li class="nav-item ms-2">
                <a href="<?= \ishop\Link::getLink('men'); ?>" class="js-link-true2">Мужчинам</a>
            </li> 
            <li class="nav-item ms-2">
                <a href="<?= \ishop\Link::getLink('kids'); ?>" class="js-link-true2">Детям</a>
            </li>
        </ul>
    </li>
    <li>Позвонить по номеру <a href="tel:<?= \ishop\App::$app->getProperty('shop_tel_link'); ?>"><?= \ishop\App::$app->getProperty('shop_tel'); ?></a></li>
</ol>
<p>Менеджеры контактного центра помогут вам выбрать подходящий товар и оформить заказ.</p>            
