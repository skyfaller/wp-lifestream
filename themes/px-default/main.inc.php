<?php
$day = '';
$counter = 0;
$newline = "\n\t\t\t\t\t\t";
if (count($events))
{
    $today = date('m d Y');
    $yesterday = date('m d Y', time()-86400);
    
    if ($has_paging)
    {
        echo $newline . '<p class="lifestream-paging">';
        if ($has_prev_page) echo '<a href="' . $lifestream->get_previous_page_url($page) . '">Downstream</a>';
        echo '</ul>';
    }
    
    echo $newline .  '<ol id="lifestream">';
    foreach ($events as $event)
    {
        $timestamp = $event->get_date();
//	$timestamp = current_time('timestamp',0);

//	echo "<h1 id='fuck'>". $timestamp."</h1>";
//	echo "<h1 id='shit'>". current_time( 'timestamp' , 1) ."</h1>";
//	echo "<h1 id='cunt'>". current_time( 'timestamp' , 0 ) ."</h1>";
        if ($today == date('m d Y', $timestamp)) $this_day = $lifestream->__('Today');
        else if ($yesterday == date('m d Y', $timestamp)) $this_day = $lifestream->__('Yesterday');
        else $this_day = $lifestream->__(ucfirst(htmlentities(date($lifestream->get_option('day_format'), $timestamp))));
        if ($day != $this_day)
        {
            if ($counter) echo $newline .  '</ol>';      
            echo $newline . "\t" . '<li class="' . date('j F Y', $timestamp) . '">';
            echo $newline . "\t" . '<h2>' . $this_day . '</h2>';
            echo $newline . "\t" . '<ol>';
            $day = $this_day;
            $counter++;
        }
        echo $newline . "\t\t" . '<li class="lifestream-feed-' . $event->feed->get_constant('ID') . '">';
        echo $event->render($options);
        echo '<p class="lifestream-label">' . $event->get_label($options) . '</p>';
        echo '<p class="lifestream-meta">';
        echo '&nbsp;&nbsp;<img class="lifestream-icon" src="' . $event->feed->get_icon_url() . '" /> ';
        echo '<span class="lifestream-hour">';

//        echo ($today == date('m d Y', $timestamp)) ? $lifestream->timesince( current_time('timestamp',0) ) : date($lifestream->get_option('hour_format'), current_time('timestamp',0) );
//        echo  date($lifestream->get_option('hour_format'), current_time('',0) );
	$evtTime = $event->timestamp;
//	$evtTime = current_time('timestamp',0);
//	echo $evtTime;
	$seconds_offset = get_option( 'gmt_offset' ) * 3600;
//	echo "<h1 id='fuck'>".get_option( 'gmt_offset')."</h1>";
	echo '&nbsp;<a rel="nofollow" class="lifestream-permalink" href="'. get_permalink( $event->post_id)  .'">&nbsp;#&nbsp;';
	echo date('Y.m.d g:i a', $evtTime + $seconds_offset );
	echo '</a>&nbsp;';
        echo '</span> ';

        echo '<span class="lifestream-via">via &nbsp;' . $event->get_feed_label($options) . '</span>';
//	echo '<a href="'. $event->get_url() .'">&nbsp;#';	echo '</a>';


	echo '&nbsp;--&nbsp;<a rel="nofollow" class="lifestream-comment" href="'. get_permalink( $event->post_id)  .'#respond">';
	echo wp_count_comments($event->post_id)->approved;
	echo '&nbsp;Comment(s)';	echo '</a>';

        echo '</p>'; // .lifestream-meta
        echo '</li>';
    }
    echo $newline . "\t" . '</ol><!-- /#lifestream -->';
    if ($has_paging)
    {
        echo $newline . '<p class="lifestream-paging">';
        if ($has_next_page) echo '<a href="' . $lifestream->get_next_page_url($page) . '">Upstream</a>';
        echo '</ul>';
    }    
}
else
{
	?>
	<p id="lifestream"><?php $lifestream->_e('There are no events to show at this time.'); ?></p>
	<?php
}
?>