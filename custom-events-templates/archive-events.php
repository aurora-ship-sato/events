<?php get_header(); ?>

<!-- layout -->
<div class="layout archive_events">

  <!-- main -->
  <main class="layout__main">

    <!-- メインコンテンツ -->
    <div class="layout__main-left width">

      <section class="page-ttl container">
        <span class="page-ttl__ruby"><?php echo my_body_info('slug'); ?>&FLIER</span>
        <h2 class="page-ttl__h2"><?php echo my_body_info('name'); ?>一覧</h2>
      </section>

      <div class="sub-page">
        <div class="sub-page__<?php echo my_body_info('pt_slug'); ?> container">
          <?php
            $posttype = 'achv';
            $filedir = get_template_directory().'/template-parts/';
            $filename = $filedir.'content-'.my_body_info('pt_slug').'-'.$posttype.'.php';
            if ( file_exists($filename) ) {
              get_template_part('template-parts/content',my_body_info('pt_slug').'-'.$posttype);
            } else {
              echo $filename;
              get_template_part('template-parts/content','common-'.$posttype);
            }
          ?>
        </div>
      </div>
      <!-- ページャー -->
      <div class="pager">
			<?php original_pager(); ?>
      </div>
    </div>

  </main>
  <!-- /main -->

</div>
<!-- /layout -->
<?php get_footer(); ?>