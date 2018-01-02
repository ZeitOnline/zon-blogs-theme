<form accept-charset="utf-8" method="get" class="search" role="search" action="<?php bloginfo('url'); ?>/">
	<label for="s" class="visually-hidden">suchen</label>
	<input class="search__input" id="s" name="s" type="search" placeholder="Suche">
	<button class="search__button" type="submit">
		<svg class="search__icon" role="img" aria-labelledby="title">
        <use xlink:href="<?php echo get_stylesheet_directory_uri(); ?>/images/icons/icons.svg?312e36312e322e64657630#svg-search"></use>
    </svg>
	</button>
</form>
