<?php

/**
 * @file
 * Given HTML for this field, parse finding certain tags and create a FeedQueue-Task for each
 */

$plugin = array(
  'form' => 'feeds_tamper_markup_to_feeds_queue_form',
  'callback' => 'feeds_tamper_markup_to_feeds_queue_callback',
  'name' => 'Create FeedQueue-Task based on markup',
  'multi' => 'loop',
  'category' => 'Other',
);

function feeds_tamper_markup_to_feeds_queue_form($importer, $element_key, $settings) {

    $form = array();
    
    $form['xpathquery'] = array(
        '#type' => 'textfield',
        '#title' => 'Parse this field (which is expected to contain HMTL), and use the result(s) of this XPath-query as the Parameter(s) of a new FeedQueue-Task ',
        '#default_value' => '//a/@href',
        '#description' => 'A new FeedQueue-Task will be created, the result(s) of this XPath query on the markup given in this field shall be used as the Parameter(s)<br/><b>Remember:</b> You will want to write an XPath-query that returns links/text, thus, it may be wise to end your XPath-query with a <b>/text()</b> or <b>/@href</b> expression (if applicable) to ensure child divs/tags are not returned',
    );
    if ( isset($settings['xpathquery']) ) {
        $form['xpathquery']['#default_value'] = $settings['xpathquery'];
    }
    
    $form['xpathalter'] = array(
        '#type' => 'textarea',
        '#title' => 'Alter the Parameter list with this PHP code/script (optional)',
        '#default_value' => "// Note: \$params is a defined array containg all results of the XPath-query.\n\nreturn \$params;",
        '#description' => 'This script <b>must return</b> an array [<small>of parameters</small>]. <b>Do NOT use &lt;?php ?&gt; flag in this field</b>',
    );
    if ( isset($settings['xpathalter']) ) {
        $form['xpathalter']['#default_value'] = $settings['xpathalter'];
    }
    
    // Get a list of all feed-importers in the system
    $feedImporters = array();
    $results = db_query("SELECT id FROM feeds_importer ORDER BY id"); // Get a list of all Feed-Importers
    foreach ( $results as $record ) {
        $feedImporters[$record->id] = $record->id;
    };

    // Task Directive
	$form['FeedsQueueItem'] = array(
		'#type' => 'fieldset',
		'#title' => 'FeedQueue-Task to create',
		'#collapsible' => 1,
		'#collapsed' => 0
    );
    $form['FeedsQueueItem']['feedqueue_directive'] = array(
		'#type' => 'fieldset',
		'#title' => 'Task Directive',
		'#collapsible' => 1,
		'#collapsed' => 0
    );
    $form['FeedsQueueItem']['feedqueue_directive']['feedqueue_title'] = array(
        '#type' => 'textfield',
        '#title' => 'Description (title) for this task',
    );
    if ( isset($settings['FeedsQueueItem']['feedqueue_directive']['feedqueue_title']) ) {
        $form['FeedsQueueItem']['feedqueue_directive']['feedqueue_title']['#default_value'] = $settings['FeedsQueueItem']['feedqueue_directive']['feedqueue_title'];
    }
    $form['FeedsQueueItem']['feedqueue_directive']['feedqueue_targetfeed'] = array(
        '#type' => 'select',
        '#title' => 'Feed-Importer to Execute',
        '#multiple' => false,
        '#options' => $feedImporters,
		'#description' => 'Select from this list which Feed-Importer you wish to execute. <b>If you cannot find a Feed-Importer in this list</b> that should exist, then <a href="/admin/structure/feeds/queue/update-feeds-list">click here</a> to update the allowed-values list for this field, and refresh this page.'
    );
    if ( isset($settings['FeedsQueueItem']['feedqueue_directive']['feedqueue_targetfeed']) ) {
        $form['FeedsQueueItem']['feedqueue_directive']['feedqueue_targetfeed']['#default_value'] = $settings['FeedsQueueItem']['feedqueue_directive']['feedqueue_targetfeed'];
    }
    $form['FeedsQueueItem']['feedqueue_directive']['feedqueue_runas'] = array(
        '#type' => 'select',
        '#title' => 'Run As (assume fetcher)',
		'#description' => 'Sets how the Feed-Importer shall run, either as a Feed-Importer that consumes data from a target URL, or a file. The value in this field determines how the value in the Parameter field is handled.<br/>NOTICE: The target Feed-Importer MUST have the appropriate fetcher set to what value is given here. This field CANNOT be used to try to override what Fetcher the Feed-Importer is using (this field is only meant to inform the Feeds-Queue module which Fetcher a target Feed-Importer is using).',
        '#multiple' => false,
        '#options' => array(
            'FeedsHTTPFetcher' => 'HTTP Fetching Feed-Importer',
            'FeedsFileFetcher' => 'File Consuming Feed-Importer'
        )
    );
    if ( isset($settings['FeedsQueueItem']['feedqueue_directive']['feedqueue_runas']) ) {
        $form['FeedsQueueItem']['feedqueue_directive']['feedqueue_runas']['#default_value'] = $settings['FeedsQueueItem']['feedqueue_directive']['feedqueue_runas'];
    }
    $form['FeedsQueueItem']['feedqueue_directive']['feedqueue_params'] = array(
        '#type' => 'textarea',
        '#title' => 'Parameter(s)',
        '#default_value' => 'The result(s) of the XPath-query given above will be placed here',
        '#disabled' => true,
		'#description' => '<b>Purpose:</b> This shall be the target URL, or File-Path, the Feed Importer shall be executed with. The Feed Importer will target the given value for consumption when executed.<br/><b>Multiple Parameters:</b> You may specify multiple URLs/file-paths by writing a parameter no each line (<small>separate parameters with new-lines</small>). When there are multiple parameters, the target Feed Importer will be executed for each parameter, one after the other, when this Feed Queue Tasks is called/executed.'
    );

    // Scheduling 
    $form['FeedsQueueItem']['feedqueue_sched'] = array(
		'#type' => 'fieldset',
		'#title' => 'Schedualing',
		'#collapsible' => 1,
		'#collapsed' => 0
    );
    $form['FeedsQueueItem']['feedqueue_sched']['feedqueue_execdate'] = array(
		'#type' => 'textfield',
		'#title' => 'Due execution date (in unix-time)',
		'#description' => 'This field should hold a <a href="http://www.epochconverter.com/">Unix Time stamp</a> that states what date/time this Feed-Importer shall be executed NO EARLIER by.  As this date/time passes, the Feed-Importer shall be executed on the next call of the Feeds-Queue cron-task.<br/>Set this value to 0 to execute this task after this currently executing FeedQueue-Task has completed running.',
    );
    if ( isset($settings['FeedsQueueItem']['feedqueue_sched']['feedqueue_execdate']) ) {
        $form['FeedsQueueItem']['feedqueue_sched']['feedqueue_execdate']['#default_value'] = $settings['FeedsQueueItem']['feedqueue_sched']['feedqueue_execdate'];
    }
    $form['FeedsQueueItem']['feedqueue_sched']['feedqueue_reoccurringwait'] = array(
		'#type' => 'textfield',
		'#title' => 'Schedule reoccurring execution every X hours',
		'#description' => 'If this value is set, then after this FeedQueue-Task has ran, the "Due execution date" field will be incremented by this given value, and [unless otherwise effected] will not be unpublished.<br/>If this value is not set, then this FeedQueue-Task will be unpublished after it has run.',
    );
    if ( isset($settings['FeedsQueueItem']['feedqueue_sched']['feedqueue_reoccurringwait']) ) {
        $form['FeedsQueueItem']['feedqueue_sched']['feedqueue_reoccurringwait']['#default_value'] = $settings['FeedsQueueItem']['feedqueue_sched']['feedqueue_reoccurringwait'];
    }
    $form['FeedsQueueItem']['feedqueue_sched']['feedqueue_nonrecact'] = array(
        '#type' => 'radios',
		'#title' => 'Action on completion (only for non-reoccurring scheduling)',
		'#description' => 'This action will be taken if this FeedQueue-Task is a non-reoccurring task. In this situation, this action will be done after the FeedQueue-Task has completed.',
        '#default_value' => 'delete',
        '#options' => array(
            'unpublish' => 'Unpublish this FeedQueue-Task node',
            'delete' => 'Delete this FeedQueue-Task node'
        )
    );
    if ( isset($settings['FeedsQueueItem']['feedqueue_sched']['feedqueue_nonrecact']) ) {
        $form['FeedsQueueItem']['feedqueue_sched']['feedqueue_nonrecact']['#default_value'] = $settings['FeedsQueueItem']['feedqueue_sched']['feedqueue_nonrecact'];
    }
    
    // Parameter Blocking 
    $form['FeedsQueueItem']['feedqueue_paramblock'] = array(
		'#type' => 'fieldset',
		'#title' => 'Parameter Blocking',
		'#collapsible' => 1,
		'#collapsed' => 1
    );
    $form['FeedsQueueItem']['feedqueue_paramblock']['feedqueue_blockfutureparam'] = array(
        '#title' => "Block given Parameter in the future",
        '#type' => 'checkbox',
        '#description' => 'If set to blocking, then the Feeds Queue module will not invoke this target Feed Importer with this same Parameter (URL or file) again in the future. This is useful for when a Feed Importer needs to run against a specific target only once.<br/>Keep in mind however, this restriction <i>only</i> applies to the Feeds Queue module. The Feed Importer can still be executed manually with the same target if so desired.<br/>The Feeds Queue module keeps track of what Parameters, for each Feed Importers, need to be ignored in the future in a special table - if this table needs to be erased, this can be done from the <a href="/admin/structure/feeds/queue">Feeds Queues Configuration page</a>.',
    );
    if ( isset($settings['FeedsQueueItem']['feedqueue_paramblock']['feedqueue_blockfutureparam']) ) {
        $form['FeedsQueueItem']['feedqueue_paramblock']['feedqueue_blockfutureparam']['#default_value'] = $settings['FeedsQueueItem']['feedqueue_paramblock']['feedqueue_blockfutureparam'];
    }
    $form['FeedsQueueItem']['feedqueue_paramblock']['feedqueue_blocklength'] = array(
        '#title' => "Blocking Duration (in hours)",
        '#type' => 'textfield',
        '#description' => 'If the previous field ("Block this Parameter") is set to Blocking the given Parameter (URL or file) from running with this Feed Importer again in the future - this field determines how long this Parameter will be blocked for (in hours).<br/><b>WARNING:</b> If the previous field ("Block this Parameter") is set to Blocking, and no value is given for this field (<small>or 0 is given</small>), then this block will remain indefinitely.<br/>Blocks which are set to never die (indefinitely) can be manually flushed by the Administrator on <a href="/admin/structure/feeds/queue">this page</a>.',
    );
    if ( isset($settings['FeedsQueueItem']['feedqueue_paramblock']['feedqueue_blocklength']) ) {
        $form['FeedsQueueItem']['feedqueue_paramblock']['feedqueue_blocklength']['#default_value'] = $settings['FeedsQueueItem']['feedqueue_paramblock']['feedqueue_blocklength'];
    }

    // Scripting and Hooks
    $form['FeedsQueueItem']['feedqueue_procscript'] = array(
		'#type' => 'fieldset',
		'#title' => 'Scripting and Hooks',
		'#collapsible' => 1,
		'#collapsed' => 1
    );
    $form['FeedsQueueItem']['feedqueue_procscript']['feedqueue_postexec'] = array(
        '#type' => 'textarea',
        '#title' => 'Pre-Execution and Validation (PHP code)',
        '#default_value' => '',
		'#description' => '<b>Do NOT use &lt;?php ?&gt; flag in this field</b><br/>Here you can write a PHP script that shall execute before the execution of the Feed Importer. If multiple parameters for this FeedQueue-Task are given in the \'Parameter(s)\' field, this script will be executed once for <b>each</b> parameter.<br/>If PHP code is given in this field, you may use <b>return true;</b> or <b>return false;</b> in order to allow, or skip/prevent, the Feed Importer from running with the given parameter.<br/>You man also alter the Parameter that is about to be executed against the target Feed Importer is so desired by changing the appropriate $variable<br/><br/><b>AVAILABLE VARIABLES AND SPECIAL CODES</b><br/><b>$thisParam</b> - string - The parameter that is about to be executed with the target Feed Importer<br/><b>$allParams</b> - array - an array of string(s) - this is an array of all parameters that was, is, and will be executed with the target Feed Importer according to <i>this</i> FeedQueue-Task.<br/><b>return true;</b> - allows this Feed Importer to be executed with the given parameter, and continue as expected.<br/><b>return false;</b> - skips this FeedQueue-Task\'s process of executing the Feed Importer with this parameter.<br/><b>return "-STOP-CHAIN-";</b> - only useful when there are multiple parameters given for this FeedQueue-Task. This is the same as <i>return false;</i>, but will also prevent all future parameters in this FeedQueue-Task from executing.<br/>'
    );
    if ( isset($settings['FeedsQueueItem']['feedqueue_procscript']['feedqueue_postexec']) ) {
        $form['FeedsQueueItem']['feedqueue_procscript']['feedqueue_postexec']['#default_value'] = $settings['FeedsQueueItem']['feedqueue_procscript']['feedqueue_postexec'];
    }
    $form['FeedsQueueItem']['feedqueue_procscript']['feedqueue_preexec'] = array(
        '#type' => 'textarea',
        '#title' => 'Post-Execution (PHP code)',
        '#default_value' => '',
		'#description' => '<b>Do NOT use &lt;?php ?&gt; flag in this field</b><br/>This script shall be executed after the Feed Importer has finished running.'
    );
    if ( isset($settings['FeedsQueueItem']['feedqueue_procscript']['feedqueue_preexec']) ) {
        $form['FeedsQueueItem']['feedqueue_procscript']['feedqueue_preexec']['#default_value'] = $settings['FeedsQueueItem']['feedqueue_procscript']['feedqueue_preexec'];
    }
    
    $form['debugging'] = array(
        '#title' => "Send debugging messages with verbosity to php's error log",
        '#type' => 'checkbox',
        '#description' => 'With this checkbox ticked, error_log() will be called to report what FeedQueue-Task nodes are created.',
    );
    if ( isset($settings['debugging']) ) {
        $form['debugging']['#default_value'] = $settings['debugging'];
    }
    
    return $form;
}

function feeds_tamper_markup_to_feeds_queue_callback($result, $item_key, $element_key, &$field, $settings, $source) {

    // Debugging and verbosity
    $writeDebugMsgs = intval($settings['debugging']);
    if ( $writeDebugMsgs ) {
        error_log("feeds_tamper_markup_to_feeds_queue_callback has been invoked with the following settings: " . print_r($settings, true));
        error_log("and it will tamper with the following value: " . $field);
    }
    
    // Initialize HTML parsing
    $doc = new DOMDocument();
    @$doc->loadHTML($field);
    $xpath = new DomXPath($doc);
    $queryXPath = trim($settings['xpathquery']);
    
    // Run XPath query
    $foundTags = $xpath->query($queryXPath);
    $params = array();
    foreach ( $foundTags as $foundTag ) {
        $params[] = $foundTag->nodeValue;
    }
    if ( $writeDebugMsgs ) {
        error_log('Parameters found based on XPath expression: ' . print_r($params, true));
    }
    
    // Alter XPath query
    if ( trim($settings['xpathalter']) !== '' ) {
        $phpCode = $settings['xpathalter'];
        if ( $writeDebugMsgs ) {
            error_log('Altering parameter(s) with PHP code: ' . $phpCode);
        }
        $params = eval($phpCode);
        if ( $writeDebugMsgs ) {
            error_log('Parameters after alteration by PHP: ' . print_r($params, true));
        }
    }
    
    // Construct new node
    $node = new StdClass();
    $node->type = 'feeds_queue_exec';
    $node->title = $settings['FeedsQueueItem']['feedqueue_directive']['feedqueue_title'];
    $node->status = 1;
    $node->uid = 0;
    $node->field_feedqueue_targetfeed = array(
        'und' => array(
            0 => array(
                'value' => $settings['FeedsQueueItem']['feedqueue_directive']['feedqueue_targetfeed']
            )
        )
    );    
    $node->field_feedqueue_blockfutureparam = array(
        'und' => array(
            0 => array(
                'value' => $settings['FeedsQueueItem']['feedqueue_paramblock']['feedqueue_blockfutureparam']
            )
        )
    );
    $node->field_feedqueue_blocklength = array(
        'und' => array(
            0 => array(
                'value' => $settings['FeedsQueueItem']['feedqueue_paramblock']['feedqueue_blocklength']
            )
        )
    );
    $node->field_feedqueue_execdate = array(
        'und' => array(
            0 => array(
                'value' => $settings['FeedsQueueItem']['feedqueue_sched']['feedqueue_execdate']
            )
        )
    );
    $node->field_feedqueue_reoccurringwait = array(
        'und' => array(
            0 => array(
                'value' => ( empty($settings['FeedsQueueItem']['feedqueue_sched']['feedqueue_reoccurringwait']) ? null : intval($settings['FeedsQueueItem']['feedqueue_sched']['feedqueue_reoccurringwait']) )
            )
        )
    );
    $node->field_feedqueue_nonrecact = array(
        'und' => array(
            0 => array(
                'value' => $settings['FeedsQueueItem']['feedqueue_sched']['feedqueue_nonrecact']
            )
        )
    );
    $node->field_feedqueue_params = array(
        'und' => array(
            0 => array(
                'value' => implode("\n", $params)
            )
        )
    );
    $node->field_feedqueue_postexec = array(
        'und' => array(
            0 => array(
                'value' => $settings['FeedsQueueItem']['feedqueue_procscript']['feedqueue_postexec'],
                'format' => null,
                'safe_value' => $settings['FeedsQueueItem']['feedqueue_procscript']['feedqueue_postexec']
            )
        )
    );
    $node->field_feedqueue_preexec = array(
        'und' => array(
            0 => array(
                'value' => $settings['FeedsQueueItem']['feedqueue_procscript']['feedqueue_preexec'],
                'format' => null,
                'safe_value' => $settings['FeedsQueueItem']['feedqueue_procscript']['feedqueue_preexec']
            )
        )
    );
    $node->field_feedqueue_runas = array(
        'und' => array(
            0 => array(
                'value' => $settings['FeedsQueueItem']['feedqueue_directive']['feedqueue_runas']
            )
        )
    );
    
    // Save this new node
    node_save($node);
    
}









