/*
 * File creato da Luca Perico
 * Include funzioni utili nell'utilizzo di Annotator
 * Support at: https://www.npmjs.com/package/annotator
 */

function deleteAllAnnotations(){
    $.post("/annotations/deleteAll/");
    $.each($('.annotator-hl'), function () {
        $(this).replaceWith($(this).html());
    });
}

function saveAnnotation (annotation){
    var ranges = annotation.ranges[0];

    $.post( "/annotations/save/",
        {   start : ranges.start,
            startOffset : ranges.startOffset,
            end : ranges.end,
            endOffset : ranges.endOffset,
            quote : annotation.quote,
            url : "/annotations/",
            text : annotation.text
        });
}

function saveAnnotationWithComment (annotation, comment){
    var ranges = annotation.ranges[0];
    annotation.text = comment;

    $.post( "/annotations/save/",
        {   start : ranges.start,
            startOffset : ranges.startOffset,
            end : ranges.end,
            endOffset : ranges.endOffset,
            quote : annotation.quote,
            url : "/annotations/",
            text : annotation.text
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
        });
}

function setAnnotatorLanguage(lang){
    switch (lang){
        case 'it':
            $('.annotator-cancel').html("Annulla");
            $('.annotator-save').html("Salva");
            break;
        case 'fr':
            $('.annotator-cancel').html("Annule");
            $('.annotator-save').html("Sauver");
            break;
        case 'es':
            $('.annotator-cancel').html("Cancela");
            $('.annotator-save').html("Salvar");
            break;
    }
}