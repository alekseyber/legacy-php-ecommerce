<?php $webp_on = \ishop\App::$app->getProperty('webp_on'); ?>
<div id="carousetopslides" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php foreach ($slider as $k => $v): ?>		
		<?php $active = $k == 0 ? ' active' : ''; ?>
		<div class="carousel-item<?= $active; ?>" data-bs-interval="3000">
			<?php if ($v['href']): ?>
			<a href="<?= $v['href']; ?>" class="js-link-true2 carousel-item_link">
				<?php endif; ?>
				<div class="carousetopslides__item_filter">
					<div class="carousetopslides__item_content">                           
						<?= $v['content']; ?>                                
					</div>                      
				</div>
				<?php if ($webp_on) : ?>
				<picture>
					<source srcset="<?= imgSrcToWebP($v['img']) ?>" type="image/webp">
					<source srcset="<?= $v['img']; ?>" type="<?= getTypeImg($v['img']) ?>">
					<img src="<?= $v['img']; ?>" class="d-block w-100" alt="<?= $v['name']; ?>">
				</picture>
				<?php else : ?>
				<img src="<?= $v['img']; ?>" class="d-block w-100" alt="<?= $v['name']; ?>">
				<?php endif; ?>  
				<?php if ($v['href']): ?>
			</a>
			<?php endif; ?>
		</div>   
        <?php endforeach; ?>
	</div>
	<button class="carousel-control-prev" type="button" data-bs-target="#carousetopslides" data-bs-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Previous</span>
	</button>
	<button class="carousel-control-next" type="button" data-bs-target="#carousetopslides" data-bs-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Next</span>
	</button>
</div>