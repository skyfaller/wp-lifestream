<?php
$day = '';
if (count($events))
{
    $today = date('m d Y');
    $yesterday = date('m d Y', time()-86400);
    ?>
    <table class="lifestream">
    <?php
    foreach ($events as $result)
    {
        $timestamp = $result->get_date();
        if ($today == date('m d Y', $timestamp)) $this_day = 'Today';
        else if ($yesterday == date('m d Y', $timestamp)) $this_day = 'Yesterday';
        else $this_day = ucfirst(htmlentities(date($day_format, $timestamp)));
        if ($day != $this_day)
        {
            ?>
            <tr>
                <th colspan="3">
                    <h2 class="lifestream_date"><?php echo $this_day; ?></h2>
                </th>
            </tr>
            <?php
            $day = $this_day;
        }
        ?>
        <tr class="lifestream_feedid_<?php echo $result->feed->get_constant('ID'); ?>">
            <td class="lifestream_icon">
                <a href="<?php echo htmlspecialchars($item->link); ?>" title="<?php echo $result->feed->get_constant('ID'); ?>"><img src="<?php echo $lifestream_path . '/images/'. $result->feed->get_constant('ID'); ?>.png" alt="<?php echo $result->feed->get_constant('ID'); ?>" /></a>
            </td>
            <td class="lifestream_hour">
                <abbr title="<?php echo date("c", $timestamp); ?>"><?php echo date($hour_format, $timestamp); ?></abbr>
            </td>
            <td class="lifestream_text">
                <?php echo $result->render(); ?>
            </td>
        </tr>
        <?php
    }
    ?>
    </table>
    <?php
}
else
{
    ?>
    <p class="lifestream"><?php _e('There are no events to show at this time.', 'lifestream'); ?></p>
    <?php
}
?>