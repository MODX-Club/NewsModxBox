Ext.ns('Tiny');
Tiny.browserCallback = function(data) {
    var FileBrowserDialogue = {
        init : function () {}
        ,selectURL : function (url) {
            var win = tinyMCEPopup.getWindowArg('window');
            
            console.log('selectURL');
            console.log(url);
            
            /* insert information now */
            win.document.getElementById(tinyMCEPopup.getWindowArg('input')).value = url;
        
            if (typeof(win.ImageDialog) != 'undefined') {
                console.log('win.ImageDialog');
                /* for image browsers: update image dimensions */
                if (win.ImageDialog.getImageData) {
                    win.ImageDialog.getImageData();
                }
                win.ImageDialog.showPreviewImage(url);
            }
        
            else if (typeof(win.AeImageDialog) != 'undefined') {
                console.log('win.AeImageDialog');
                /* for image browsers: update image dimensions */
                if (win.AeImageDialog.getImageData) {
                    win.AeImageDialog.getImageData();
                }
                win.AeImageDialog.showPreviewImage(url);
            }
        
            /* close popup window */
            tinyMCEPopup.close();
            win.focus(); win.document.focus();
        }
    };
    var fileUrl;
    if (inRevo20) {
        fileUrl = unescape(data.relativeUrl);
    } else {
        fileUrl = data.fullRelativeUrl;
    }
    tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);

    function OpenFile(fileUrl){
        console.log('OpenFile');
        console.log(fileUrl);
        FileBrowserDialogue.selectURL(fileUrl);
    }

    OpenFile(fileUrl);
};