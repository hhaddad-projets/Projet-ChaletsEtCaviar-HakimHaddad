<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

/**
 * This is the default template for the keyword suggestions
 *
 * The keyword should must always hold the 'asp_keyword' class and only
 * contain the keyword text as the content.
 *
 * You can use any WordPress function here.
 * Variables to mention:
 *      Array[]  $s_keywords - array of the keywords
 *      Array[]  $s_options - holding the search options
 *
 * You can leave empty lines for better visibility, they are cleared before output.
 *
 * MORE INFO: https://wp-dreams.com/knowledge-base/result-templating/
 *
 * @since: 4.0
 */
?>
<div class="asl_nores">

    <span class="asl_nores_header"><?php echo asl_icl_t('No results text', $s_options['noresultstext']) . " " . asl_icl_t('Did you mean text', $s_options['didyoumeantext']); ?></span>
    <br />
    <?php foreach($s_keywords as $keyword): ?>
        <span class='asl_keyword'><?php echo $keyword; ?></span>
    <?php endforeach; ?>

</div>