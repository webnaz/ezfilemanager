tinymce.PluginManager.add('ezfilemanager', function(editor) {
    function openmanager() {
        var win, data, dom = editor.dom,
            imgElm = editor.selection.getNode();
        var width, height, imageListCtrl;
        win = editor.windowManager.open({
            title: ezfmTitle,
            file: ezfmURL + 'index.php', 
            filetype: 'all',
            width: ezfmWidth,
            height: ezfmHeight,
            inline: 1
        })
    }
    editor.addButton('ezfilemanager', {
        icon: 'browse',
        tooltip: ezfmTitle,
        onclick: openmanager,
        stateSelector: 'img:not([data-mce-object])'
    });
});
