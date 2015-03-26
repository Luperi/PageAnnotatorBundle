/*
 * File creato da Luca Perico
 * Include funzioni utili nell'utilizzo di Annotator
 * Support at: https://www.npmjs.com/package/annotator
 */

function deleteAllAnnotations(){
    $.post("/annotations/deleteAll/")
        .done(function (data) {
            //console.info("Page returned %o", data)
        });
    $.each($('.annotator-hl'), function () {
        $(this).replaceWith($(this).html());
    });
}

function saveAnnotation (annotation){
    var ranges = annotation.ranges[0];
    annotation.text = selectorValue;

    $.post( "/annotations/save/",
        {   start : ranges.start,
            startOffset : ranges.startOffset,
            end : ranges.end,
            endOffset : ranges.endOffset,
            quote : annotation.quote,
            url : "/annotations/",
            text : annotation.text
        })
        .done(function( data ) {
            //console.info("Page /save/ returned %o", data)
        });
}

function deleteAnnotation (annotation){
    var ranges = annotation.ranges[0];

    $.post( "/annotations/delete/",
        {   start : ranges.start,
            startOffset : ranges.startOffset,
            end : ranges.end,
            endOffset : ranges.endOffset,
            url : "/annotations/"
        })
        .done(function( data ) {
            //console.info("Page returned %o", data)
        });
}