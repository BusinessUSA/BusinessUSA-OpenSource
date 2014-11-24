<?php
        // Enforce consistent identifiers
        switch ( $variables['widgetType'] ) {
        
            case 'radio':
            case 'radiobutton':
            case 'radio-button':
            case 'radiogroup':
            case 'radio-group':
                $variables['widgetType'] = 'radiogroup';
                break;
                
            case 'select':
            case 'drop-down':
            case 'selectbox':
                $variables['widgetType'] = 'selectbox';
                break;
                
            case 'image':
            case 'img':
            case 'art':
                $variables['widgetType'] = 'image';
                break;
                
            case 'text':
            case 'textbox':
            case 'textinput':
                $variables['widgetType'] = 'textbox';
                break;
                
            case 'check':
            case 'checkbox':
            case 'checkinput':
                $variables['widgetType'] = 'checkbox';
                break;
                
            case 'textarea':
            case 'text-area':
                $variables['widgetType'] = 'textarea';
                break;
        }
    ?>

    <?php if ( !in_array( $variables['widgetType'], array('image', 'button', 'nextbutton', 'prevbutton', 'html', 'checkbox') ) ): ?>
        <?php if ( trim($variables['label']) !== '' ): ?>
            <label for="wiztag-<?php print $variables['wizardTag']; ?>" class="question-label question-label-for-wiztag-<?php print $variables['wizardTag']; ?>" rendersource="<?php print __FILE__; ?>">
                <?php print $variables['label']; ?>
            </label>
        <?php endif; ?>
    <?php endif; ?>

    <div class="question-controle-container question-controle-for-wiztag-<?php print $variables['wizardTag']; ?> question-controle-widget-type-<?php print cssFriendlyString($variables['widgetType']); ?>" rendersource="<?php print __FILE__; ?>">

        <?php switch ( trim($variables['widgetType']) ):
                case 'radiogroup': ?>
                    <?php foreach ( $variables['options'] as $optionWizTag => $optionLabel ): ?>
                        <?php 
                            if ( is_numeric($optionWizTag) ) {
                                $optionWizTag = $variables['wizardTag'] . '-' . cssFriendlyString($optionLabel);
                            }
                        ?>
                        <div class="wizard-inputlabel-container">
                            <input type="radio" id="wiztag-<?php print $optionWizTag; ?>" name="<?php print $variables['wizardTag']; ?>" value="<?php print $optionWizTag; ?>" class="question-controle wiztag-<?php print $optionWizTag; ?> <?php print $variables['inputClass']; ?>" <?php print $variables['inputAttrs']; ?>>
                            <label for="<?php print $variables['wizardTag'] . '-' . cssFriendlyString($optionLabel); ?>">
                                <?php print $optionLabel; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php break; ?>
                
            <?php case 'selectbox': ?>
                <select id="wiztag-<?php print $variables['wizardTag']; ?>" class="question-controle wiztag-<?php print $variables['wizardTag']; ?> <?php print $variables['inputClass']; ?>" <?php print $variables['inputAttrs']; ?>>
                    <?php foreach ( $variables['options'] as $optionWizTag => $optionLabel ): ?>
                        <option id="wiztag-<?php print $optionWizTag; ?>" value="<?php print $optionWizTag; ?>">
                            <?php print $optionLabel; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php break; ?>
                
            <?php case 'textbox': ?>
                <?php
                    if ( is_array($variables['options']) ) {
                        $textBoxValue = implode(' ', $variables['options']);
                    } else {
                        $textBoxValue = $variables['options'];
                    }
                ?>
                <input type="text" id="wiztag-<?php print $variables['wizardTag']; ?>" value="<?php print $textBoxValue; ?>" class="question-controle wiztag-<?php print $variables['wizardTag']; ?> <?php print $variables['inputClass']; ?>" <?php print $variables['inputAttrs']; ?> style="border: 1px solid gray;" note="inline border style added by yawizard.tpl.php" />
                <?php break; ?>
                
            <?php case 'nextbutton': ?>
                <input type="button" value="<?php print $variables['label']; ?>" onclick="yawizard.next();" id="wiztag-<?php print $variables['wizardTag']; ?>" class="question-controle wiztag-<?php print $variables['wizardTag']; ?> <?php print $variables['inputClass']; ?>" <?php print $variables['inputAttrs']; ?>/>
                <?php break; ?>
                
            <?php case 'prevbutton': ?>
                <input type="button" value="<?php print $variables['label']; ?>" onclick="yawizard.back();" id="wiztag-<?php print $variables['wizardTag']; ?>" class="question-controle wiztag-<?php print $variables['wizardTag']; ?> <?php print $variables['inputClass']; ?>" <?php print $variables['inputAttrs']; ?>/>
                <?php break; ?>
                
            <?php case 'html': ?>
                <?php print $variables['label']; ?>
                <?php print $variables['options']; ?>
                <?php break; ?>
                
            <?php case 'button': ?>
                <input type="button" value="<?php print $variables['label']; ?>" id="wiztag-<?php print $variables['wizardTag']; ?>" class="question-controle wiztag-<?php print $variables['wizardTag']; ?> <?php print $variables['inputClass']; ?>" <?php print $variables['inputAttrs']; ?>/>
                <?php break; ?>
                
            <?php case 'checkbox': ?>
                <input type="checkbox" id="wiztag-<?php print $variables['wizardTag']; ?>" class="question-controle wiztag-<?php print $variables['wizardTag']; ?> <?php print $variables['inputClass']; ?>" <?php print $variables['inputAttrs']; ?>/>
                <label for="wiztag-<?php print $variables['wizardTag']; ?>" class="question-label question-label-for-wiztag-<?php print $variables['wizardTag']; ?>" rendersource="<?php print __FILE__; ?>">
                    <?php print $variables['label']; ?>
                </label>                
                <?php break; ?>
                
            <?php case 'image': ?>
                <img src="<?php print $variables['label']; ?>" id="wiztag-<?php print $variables['wizardTag']; ?>" class="question-controle wiztag-<?php print $variables['wizardTag']; ?> <?php print $variables['inputClass']; ?>" <?php print $variables['inputAttrs']; ?>/>
                <?php break; ?>
                
            <?php case 'textarea': ?>
                <textarea id="wiztag-<?php print $variables['wizardTag']; ?>" class="question-controle wiztag-<?php print $variables['wizardTag']; ?> <?php print $variables['inputClass']; ?>" <?php print $variables['inputAttrs']; ?>></textarea>
                <?php break; ?>
                
        <?php endswitch; ?>
    </div>