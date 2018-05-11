/**
 * Created by tobi on 19.11.15.
 */

// variables for configurator:
var selectedFontOptionVal = 1;
var selectedColorOptionVal = 1;

function openConfigurator() {
    // console.log("open product configurator ...");

    // put values of options into input fields of configurator:
    var startText = jQuery(".customize-text input").val();
    jQuery( ".esp-configurator-controls #input-text" ).val(startText);

    selectedFontOptionVal = jQuery(".customize-font select").val();

    // update configurator image:
    if (userHasCustomized) {
        updateConfiguratorImg();
    }

    jQuery("#esp-configurator-bg").fadeIn( 400, function() {
        jQuery("#esp-configurator-outer-wrapper").fadeIn( 400, function() {
            // console.log("Animation complete");
            jQuery(".esp-configurator-controls #input-text").focus();
        });
    });

}

function resetConfigurator() {

    // if validation msg is still showing up, hide it
    jQuery( ".esp-below-input #esp-validation-msg").hide();

    // clear all input field of configurator:
    jQuery( ".esp-configurator-controls #input-text" ).val("");

    // clear all input fields of magento-options and reset font:
    jQuery(".customize-text input").val("");
    jQuery(".customize-font select").val(0);

    // reset button-text and text before button
    jQuery("#product-customization-before-btn-text").html(espcTextBeforeBtn0);
    jQuery("#product-customization-before-btn ul").hide();
    jQuery(".product-customization-btn-wrapper span").html(espcTextCustomizeIt);


    // close configurator:
    closeConfigurator();
}

function closeConfigurator() {
    // console.log("close configurator ...");
    jQuery("#esp-configurator-bg").fadeOut();
    jQuery("#esp-configurator-outer-wrapper").fadeOut();

    // reload price to update:
    opConfig.reloadPrice();
}

function saveAndCloseConfigurator() {
    // console.log("close and save configurator ...");

    // update configurator image:
    updateConfiguratorImg();

    // check weather the right variables are put:
    // get value of input text field:
    var textValue = jQuery( ".esp-configurator-controls #input-text" ).val();
    if (textValue == "") {
        jQuery( ".esp-below-input #esp-validation-msg").fadeIn();
    } else {
        // validation passed:
        jQuery( ".esp-below-input #esp-validation-msg").hide();
        /*jQuery(".customize-yes-no input").prop('checked',true);*/
        jQuery(".customize-text input").val(textValue);
        jQuery(".customize-font select").val(selectedFontOptionVal);
        var selectedFontText = jQuery(".customize-font select option:selected").text();
        jQuery(".customize-color select").val(selectedColorOptionVal);
        var selectedColorText = jQuery(".customize-color select option:selected").text();

        // change Button-Text and text before button
        jQuery("#product-customization-before-btn-text").html(espcTextBeforeBtn1);
        jQuery("#product-customization-before-btn ul").show();
        jQuery("#espc-dd-customization-text").html(textValue);
        jQuery("#espc-dd-customization-font").html(selectedFontText);
        jQuery("#espc-dd-customization-color").html(selectedColorText);
        jQuery(".product-customization-btn-wrapper span").html(espcTextChangeCustomization);

        closeConfigurator();
        opConfig.reloadPrice();
    }

}

function updateConfiguratorImg() {
    // console.log( "function updateConfiguratorImg()" );
    userHasCustomized = true;

    /*
     * updateText in Configurator:
     */
    var textValue = jQuery( ".esp-configurator-controls #input-text" ).val();
    jQuery( "#esp-configurator-text").html(textValue);

    /*
     * update font style in configurator
     */

    // get font value from font-picker:
    var selectedFontValue = jQuery("input:radio[name ='esp-radioBtn-font']:checked").val();
    // console.log( "new font is: " + selectedFontValue );

    // go through color picker input fields. If selected add class preview-text, if not selected, remove class from preview-text
    jQuery( "input:radio[name ='esp-radioBtn-font']" ).each(function( index ) {

        var currentValue = jQuery( this ).val();
        // console.log( index + ": " + currentValue );
        var currentId = this.id;
        var mageOptionIdFont = currentId.replace("esp-font-id-", "");

        var classForPreviewText = currentValue.replace("font-picker-value", "font");
        if ( currentValue == selectedFontValue ) {
            jQuery( "#esp-configurator-text").addClass(classForPreviewText);
            selectedFontOptionVal = mageOptionIdFont;
        } else {
            jQuery( "#esp-configurator-text").removeClass(classForPreviewText);
        }
    });

    /*
     * update color in configurator
     */

    // get color value from color-picker:
    var selectedColorValue = jQuery("input:radio[name ='esp-radioBtn-color']:checked").val();
    // console.log( "new color is: " + selectedColorValue );

    // go through color picker input fields. If selected add class preview-text, if not selected, remove class from preview-text
    jQuery( "input:radio[name ='esp-radioBtn-color']" ).each(function( index ) {

        var currentValue = jQuery( this ).val();
        // console.log( index + ": " + currentValue );
        var currentId = this.id;
        var mageOptionIdColor = currentId.replace("esp-color-id-", "");

        var classForPreviewText = currentValue.replace("color-picker-value", "color");
        if ( currentValue == selectedColorValue ) {
            jQuery( "#esp-configurator-text").addClass(classForPreviewText);
            selectedColorOptionVal = mageOptionIdColor;
        } else {
            jQuery( "#esp-configurator-text").removeClass(classForPreviewText);
        }
    });

}

/*
 * add click actions to the conifgurator
 */

jQuery( document ).ready( function()  {

    jQuery("#esp-configurator-bg-dark-overlay").click( function() {
        closeConfigurator();
    });

    jQuery( ".esp-configurator-controls #input-text" ).bind('input', function(){
        // console.log('text-input-field is changed');
        updateConfiguratorImg();
    });

    jQuery( ".esp-configurator-controls fieldset input").change(function() {
        // console.log('fonts or colors were changed');
        updateConfiguratorImg();
    });

});


/*
function reloadStylesheets() {
    var queryString = '?reload=' + new Date().getTime();
    jQuery('link[rel="stylesheet"]').each(function () {
        this.href = this.href.replace(/\?.*|$/, queryString);
    });
}
*/


/*
.bind("change paste keyup", function() {
    alert($(this).val());
});

    */