jQuery(document).ready(function() {
    $(".summernote").summernote({
        height: 190,
        minHeight: null,
        maxHeight: null,
        focus: !1,
        codeviewFilter: false,
        codeviewIframeFilter: true,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ],
        fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana'],
        addDefaultFonts: false,
    }), $(".inline-editor").summernote({
        airMode: !0
    })
}), window.edit = function() {
    $(".click2edit").summernote()
    console.log("edit")
}, window.save = function() {
    $(".click2edit").summernote("destroy")
    console.log("save")
};
