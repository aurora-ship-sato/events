<?php get_header() ?>
<?php if (get_field('EntryData_ContactBtn')) { ?>
  <?
  $url = network_home_url();
  // URLの末尾にスラッシュがない場合に追加する
  if (substr($url, -1) !== '/') {
    $url .= '/';
  }
  ?>

  <link rel="stylesheet" href="<?php echo $url; ?>css/form.css" />
  <link href="<?php echo $url; ?>css/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
  <script src="<?php echo $url; ?>js/jquery.validationEngine.js"></script>
  <script src="<?php echo $url; ?>js/jquery.validationEngine-ja.js"></script>
  <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
  <script>
    jQuery(document).ready(function($) {
      // カスタムバリデーションルールの追加
      $.validationEngineLanguage.allRules['japanese'] = {
        // 正規表現パターン: 日本語（全角）、全角スペース、半角スペースのみを許可
        "regex": /^[\u3040-\u309F\u30A0-\u30FF\u4E00-\u9FFF\s]+$/,
        "alertText": "* 漢字・カタカナ・ひらがなのいずれかで入力してください"
      };
      // フォームのバリデーションを有効にする
      $("#your-name").validationEngine(); // 適切なフォームIDを設定
    });
  </script>
  <script>
    (function($) {
      /*-----カプセル化-----*/

      $(function() {

        $("form").validationEngine('attach', {
          promptPosition: "bottomLeft"
        });
        $("input:not(.form_btn01,.form_btn02,.form_btn03),textarea").bind("keyup", function() {
          var inputObj = $(this);
          if (inputObj.val() !== "") {
            inputObj.css("background", "#FFFFE4");
          } else {
            inputObj.css("background", "#D9D9D9");
          }
        });

        $("textarea,input[type=text],input[type=email],input[type=tel]").change(function() {
          $(window).on('beforeunload', function() {
            return '入力内容が破棄されます。よろしいですか？';
          });
        });
        $("button[type=submit]").click(function() {
          $(window).off('beforeunload');
        });
      });

      /*-----/カプセル化-----*/
    })(jQuery);
  </script>

  <link rel="stylesheet" href="<?php echo home_url(); ?>/css/jquery.datetimepicker.css">
  <script src="<?php echo home_url(); ?>/js/jquery.datetimepicker.full.min.js"></script>
<?php } ?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo get_map_api_key(); ?>"></script>
<script>
  (function($) {
    /*
     *  new_map
     *
     *  This function will render a Google Map onto the selected jQuery element
     *
     *  @type    function
     *  @date    8/11/2013
     *  @since   4.3.0
     *
     *  @param   $el (jQuery element)
     *  @return  n/a
     */
    function new_map($el) {
      // var
      const $markers = $el.find('.marker');
      // vars
      const args = {
        zoom: 16,
        center: new google.maps.LatLng(0, 0),
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      // create map
      const map = new google.maps.Map($el[0], args);
      // add a markers reference
      map.markers = [];
      // add markers
      $markers.each(function() {
        add_marker($(this), map);

      });
      // center map
      center_map(map);
      // return
      return map;
    }

    /*
     *  add_marker
     *
     *  This function will add a marker to the selected Google Map
     *
     *  @type    function
     *  @date    8/11/2013
     *  @since   4.3.0
     *
     *  @param   $marker (jQuery element)
     *  @param   map (Google Map object)
     *  @return  n/a
     */
    function add_marker($marker, map) {
      // var
      const latlng = new google.maps.LatLng($marker.attr('data-lat'), $marker.attr('data-lng'));
      // create marker
      const marker = new google.maps.Marker({
        position: latlng,
        map: map
      });
      // add to array
      map.markers.push(marker);
      // if marker contains HTML, add it to an infoWindow
      if ($marker.html()) {
        // create info window
        const infowindow = new google.maps.InfoWindow({
          content: $marker.html()
        });
        // show info window when marker is clicked
        google.maps.event.addListener(marker, 'click', function() {
          infowindow.open(map, marker);
        });
      }
    }

    /*
     *  center_map
     *
     *  This function will center the map, showing all markers attached to this map
     *
     *  @type    function
     *  @date    8/11/2013
     *  @since   4.3.0
     *
     *  @param   map (Google Map object)
     *  @return  n/a
     */
    function center_map(map) {
      // vars
      const bounds = new google.maps.LatLngBounds();
      // loop through all markers and create bounds
      $.each(map.markers, function(i, marker) {
        const latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
        bounds.extend(latlng);
      });
      // only 1 marker?
      if (map.markers.length == 1) {
        // set center of map
        map.setCenter(bounds.getCenter());
        map.setZoom(16);
      } else {
        // fit to bounds
        map.fitBounds(bounds);
      }
    }

    /*
     *  document ready
     *
     *  This function will render each map when the document is ready (page has loaded)
     *
     *  @type    function
     *  @date    8/11/2013
     *  @since   5.0.0
     *
     *  @param   n/a
     *  @return  n/a
     */
    let map = null;
    $(document).ready(function() {
      $('.acf-map').each(function() {
        // create map
        map = new_map($(this));
      });
    });
  })(jQuery);
</script>
<!-- layout -->
<div class="layout single_events">

  <!-- main -->
  <main class="layout__main">

    <!-- メインコンテンツ -->
    <div class="layout__main-left">
      <article>

        <section class="page-ttl container">
          <span class="page-ttl__ruby">event</span>
          <h2 class="page-ttl__h2"><?php echo my_body_info('name'); ?></h2>
        </section>

        <div class="sub-page">

          <div class="sub-page__<?php echo my_body_info('pt_slug'); ?> container" style="margin-bottom: 5%;">
            <?php
            $sub_select = get_field('EntryData_StatusBtn');
            ?>

            <?php if ($sub_select == 'type-b') { ?>
              <section class="section-ttl coming">
                <h2 class="section-ttl__h2">こちらの情報は<br class="display-sp">只今準備中になります。</h2>
                <?php
                // CHANGED: 画像のフィールドに繰り返しフィールドを使用したためそれに応じて変更※構造そのままに
                $rows = get_field('EntryData_ListImage_gl');
                $count = 0;
                if ($rows) {
                  foreach ($rows as $row) {
                    $count++;
                  }
                }
                ?>
                <?php if (!$rows) : ?>
                  <div>
                    <img src="<?php echo home_url('/'); ?>images/coming-soon.jpg" class="switch" alt="comming soon">
                  </div>
                <?php else : ?>
                  <?php if ($count == 1) : ?>
                    <div class="single_events__img clearfix">
                      <div class="single_events__img--one-column">
                        <div class="my-gallery">
                          <figure>
                            <?php
                            // CHANGED: 画像のフィールドに繰り返しフィールドを使用したためそれに応じて変更※構造そのままに
                            $rows_post = get_field('EntryData_ListImage_gl');
                            if ($rows_post) {
                              foreach ($rows as $row) {
                                $image = $row['EntryData_ListImage'];
                                $img_url = $image['url'];
                                $img_thumb = $image['sizes']['large'];
                                $img_width = $image['width'];
                                $img_height = $image['height'];
                                $zoom = $row['EntryData_Imagezoom'];
                                if ($zoom == false) {
                                  $zoom_style = "pointer-events: none;";
                                  $zoom_class = "zoom_none";
                                }
                            ?>
                                <a style="<?php echo $zoom_style; ?>" href="<?php echo $img_url; ?>" class="<?php echo $zoom_class; ?>" data-size="<?php echo $img_width; ?>x<?php echo $img_height; ?>" data-lightbox="group1"><img src="<?php echo $img_thumb; ?>" alt="" /></a>
                            <?php
                              }
                            }
                            ?>
                          </figure>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                  <?php if ($count == 2) : ?>
                    <div class="single_events__img clearfix">
                      <div class="single_events__img--two-column">
                        <ul class="clearfix">
                          <?php
                          // CHANGED: 画像のフィールドに繰り返しフィールドを使用したためそれに応じて変更※構造そのままに
                          $rows_post = get_field('EntryData_ListImage_gl');
                          if ($rows_post) {
                            foreach ($rows as $row) {
                              $image = $row['EntryData_ListImage'];
                              $img_url = $image['url'];
                              $img_thumb = $image['sizes']['large'];
                              $img_width = $image['width'];
                              $img_height = $image['height'];
                              $zoom = $row['EntryData_Imagezoom'];
                              if ($zoom == false) {
                                $zoom_style = "pointer-events: none;";
                                $zoom_class = "zoom_none";
                              }
                          ?>
                              <li>
                                <div class="my-gallery">
                                  <figure>
                                    <a style="<?php echo $zoom_style; ?>" href="<?php echo $img_url; ?>" class="<?php echo $zoom_class; ?>" data-size="<?php echo $img_width; ?>x<?php echo $img_height; ?>" data-lightbox="group1"><img src="<?php echo $img_thumb; ?>" alt="" /></a>
                                  </figure>
                                </div>
                              </li>
                          <?php
                            }
                          }
                          ?>
                        </ul>
                      </div>
                    </div>
                  <?php endif; ?>
                  <?php if ($count == 3) : ?>
                    <div class="single_events__img clearfix">
                      <div class="single_events__img--three-column">
                        <ul class="clearfix">
                          <?php
                          // CHANGED: 画像のフィールドに繰り返しフィールドを使用したためそれに応じて変更※構造そのままに
                          $rows_post = get_field('EntryData_ListImage_gl');
                          if ($rows_post) {
                            foreach ($rows as $row) {
                              $image = $row['EntryData_ListImage'];
                              $img_url = $image['url'];
                              $img_thumb = $image['sizes']['large'];
                              $img_width = $image['width'];
                              $img_height = $image['height'];
                              $zoom = $row['EntryData_Imagezoom'];
                              if ($zoom == false) {
                                $zoom_style = "pointer-events: none;";
                                $zoom_class = "zoom_none";
                              }
                          ?>
                              <li>
                                <div class="my-gallery">
                                  <figure>
                                    <a style="<?php echo $zoom_style; ?>" href="<?php echo $img_url; ?>" class="<?php echo $zoom_class; ?>" data-size="<?php echo $img_width; ?>x<?php echo $img_height; ?>" data-lightbox="group1"><img src="<?php echo $img_thumb; ?>" alt="" /></a>
                                  </figure>
                                </div>
                              </li>
                          <?php
                            }
                          }
                          ?>
                        </ul>
                      </div>
                    </div>

                  <?php endif; ?>

                <?php endif; ?>
              </section>
            <?php } ?>

            <?php
            //テンプレートパーツの設定
            //content-{投稿タイプ名}-{階層}.phpになっている必要がある
            $posttype = 'single';
            $filedir = get_template_directory() . '/template-parts/';
            $filename = $filedir . 'content-' . my_body_info('pt_slug') . '-' . $posttype . '.php';

            if (file_exists($filename)) {
              //echo '該当ファイルあり'; //デバッグ用
              //テンプレートパーツがある場合、カスタムテンプレートを読み込む
              get_template_part('template-parts/content', my_body_info('pt_slug') . '-' . $posttype);
            } else {
              //echo '該当ファイル無し'; //デバッグ用
              //テンプレートパーツがない場合、記事本文を読み込み
              the_content();
            }
            ?>



            <?php if (get_field('EntryData_ContactBtn')) { ?>
              <?php
              if (get_field('EntryData_Fin') == false) {
              ?>
                <section class="section-ttl">
                  <span class="section-ttl__ruby">Application</span>
                  <h2 class="section-ttl__h2">イベントへのお申し込みはこちらから</h2>
                </section>
                <?php echo do_shortcode('[contact-form-7 id="3344" title="イベント参加"]') ?>
              <?php
              }
              ?>
            <?php } ?>
            <div class="sub-page__btn--pager">
              <div><a href="<?php echo get_post_type_archive_link('events'); ?>">一覧へ戻る</a></div>
            </div>
          </div>
        </div>
      </article>
    </div>

  </main>
  <!-- /main -->

  <!-- PC用 途中から１カラムへ変更 -->
  <aside class="layout__side">

    <?php
    //テンプレートパーツの設定
    //content-{投稿タイプ名}-{階層}.phpになっている必要がある
    $posttype = 'single';
    $filedir = get_template_directory() . '/template-parts/';
    $filename = $filedir . 'content-' . my_body_info('pt_slug') . '-' . $posttype . '.php';

    if (file_exists($filename)) {
      //echo '該当ファイルあり'; //デバッグ用
      //テンプレートパーツがある場合、カスタムテンプレートを読み込む
      get_template_part('template-parts/aside', my_body_info('pt_slug') . '-' . $posttype);
    } else {
      //echo '該当ファイル無し'; //デバッグ用
      //テンプレートパーツがある場合、カスタムテンプレートを読み込む
      get_template_part('template-parts/aside', 'common-' . $posttype);
    }
    ?>

  </aside>

</div>
<!-- /layout -->
<?php
$date_limit_type = get_field('date_type');
$specific_dates = get_field('events_period01');
$date_ranges = get_field('events_period02');

if ($date_limit_type == 'type01' && (empty($specific_dates) || is_null($specific_dates) || count($specific_dates) == 0 || count(array_filter($specific_dates, function ($item) {
  return !empty($item['events_period_date']) && !empty($item['events_period_time_start']) && !empty($item['events_period_time_end']);
})) == 0)) {
  $date_limit_type = 'type03';
} elseif ($date_limit_type == 'type02' && (empty($date_ranges) || is_null($date_ranges) || count($date_ranges) == 0 || count(array_filter($date_ranges, function ($item) {
  return !empty($item['events_period_date_start']) && !empty($item['events_period_date_end']) && !empty($item['events_period_time_start']) && !empty($item['events_period_time_end']);
})) == 0)) {
  $date_limit_type = 'type03';
}

if ($date_limit_type == 'type01') {
  $acf_data = array(
    'date_limit_type' => $date_limit_type,
    'specific_dates' => $specific_dates
  );
} elseif ($date_limit_type == 'type02') {
  $acf_data = array(
    'date_limit_type' => $date_limit_type,
    'date_ranges' => $date_ranges
  );
} else {
  $acf_data = array(
    'date_limit_type' => $date_limit_type
  );
}
?>
<?php /* echo json_encode($acf_data); */ ?>
<script>
  var acfData = <?php echo json_encode($acf_data); ?>;
  var eventDates = {};
  var earliestDate = null;
  var earliestTime = null;

  if (acfData.date_limit_type === "type01" && acfData.specific_dates && acfData.specific_dates.length > 0) {
    acfData.specific_dates.forEach(function(item) {
      var formattedDate = item.events_period_date.split('-').map(function(part) {
        return parseInt(part, 10);
      }).join('/');
      if (!eventDates[formattedDate]) {
        eventDates[formattedDate] = [];
      }
      var times = generateTimes(item.events_period_time_start, item.events_period_time_end);
      eventDates[formattedDate] = eventDates[formattedDate].concat(times);

      if (!earliestDate || new Date(formattedDate) < new Date(earliestDate)) {
        earliestDate = formattedDate;
        earliestTime = eventDates[formattedDate][0];
      }
    });
  } else if (acfData.date_limit_type === "type02" && acfData.date_ranges && acfData.date_ranges.length > 0) {
    acfData.date_ranges.forEach(function(range) {
      var startDate = new Date(range.events_period_date_start.split('-').join('/'));
      var endDate = new Date(range.events_period_date_end.split('-').join('/'));
      while (startDate <= endDate) {
        var formattedDate = startDate.getFullYear() + "/" + (startDate.getMonth() + 1) + "/" + startDate.getDate();
        if (!eventDates[formattedDate]) {
          eventDates[formattedDate] = [];
        }
        var times = generateTimes(range.events_period_time_start, range.events_period_time_end);
        eventDates[formattedDate] = eventDates[formattedDate].concat(times);

        if (!earliestDate || new Date(formattedDate) < new Date(earliestDate)) {
          earliestDate = formattedDate;
          earliestTime = eventDates[formattedDate][0];
        }

        startDate.setDate(startDate.getDate() + 1);
      }
    });
  } else if (acfData.date_limit_type === "type03" || !acfData.specific_dates && !acfData.date_ranges) {
    // type03の処理、全ての日付を許可するため、特別な処理は不要
  }

  function generateTimes(startTime, endTime) {
    var times = [];
    var start = new Date("1970-01-01T" + startTime + ":00");
    var end = new Date("1970-01-01T" + endTime + ":00");
    while (start <= end) {
      times.push(start.toTimeString().slice(0, 5));
      start.setMinutes(start.getMinutes() + 30);
    }
    return times;
  }

  jQuery(document).ready(function($) {
    $.datetimepicker.setLocale('ja');

    $('#preferred_date').datetimepicker({
      beforeShowDay: function(date) {
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        if (date < today) {
          return [false];
        }
        var d = date.getFullYear() + "/" + (date.getMonth() + 1) + "/" + date.getDate();
        if (eventDates[d] || acfData.date_limit_type === "type03") {
          return [true];
        } else {
          return [false];
        }
      },
      onShow: function() {
        if (earliestDate && earliestTime) {
          this.setOptions({
            value: earliestDate + " " + earliestTime,
            allowTimes: eventDates[earliestDate] || []
          });
        }
      },
      onSelectDate: function(date) {
        var d = date.getFullYear() + "/" + (date.getMonth() + 1) + "/" + date.getDate();
        this.setOptions({
          allowTimes: eventDates[d] || []
        });
      }
    });
  });
</script>



<?php get_footer(); ?>