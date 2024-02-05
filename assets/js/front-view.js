jQuery(document).ready(function($) {
    // Append the html view to the end of the first <header> tag.
    if( undefined !== typeof wcpp_template ){
        $('header:first').append(wcpp_template);
    }
});