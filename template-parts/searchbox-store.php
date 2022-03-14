<div id="store-searchbox" class="store-search-form">
    <form method="get" action="<?php echo home_url(); ?>/" role="search">
        <button type="submit">
            <i class="lni lni-search-alt"></i>
            <span><?php _e('Search', 'salesloo'); ?></span>
        </button>
        <input type="search" name="s" placeholder="<?php _e('Cari Produk', 'marketpress'); ?>" required>
        <input type="hidden" name="type" value="marketpress-store" />
    </form>
    <div class="result">
        <label>Tidak di temukan hasil</label>
        <div class="resultbox">
        </div>
    </div>
</div>