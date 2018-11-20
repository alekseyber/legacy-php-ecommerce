<div class="top-carusel-wrap">
    <!-- Swiper -->
    <div class="swiper-container js-swiper-top-slide">
        <div class="swiper-wrapper">
            <?php foreach ($slider as $v): ?>  
                <div class="swiper-slide">
                    <img class="img-fluid" src="<?= $v['img']; ?>" alt="<?= $v['name']; ?>">
                    <div class="swiper-slide-text-item-wrap">
                        <div class="swiper-slide-text-item">
                            <div class="swiper-slide-text-item-block">
                                <?= $v['content']; ?>
                            </div>
                        </div>
                    </div>
                </div>   <!-- swiper-slide -->
            <?php endforeach; ?>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination-top-slide-wrap">
            <div class="swiper-pagination swiper-pagination-top-slide"></div>
        </div>
    </div>
</div> <!-- top-carusel-wrap -->