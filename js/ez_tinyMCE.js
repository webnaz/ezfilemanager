/* File Browser Plugin call for TinyMCE v4.x
 * Copyright (c) Nazaret Armenagian (Naz)
 * Project home: http://www.webnaz.net
  * Version: 1.0
 */
function CustomFileBrowser(field_name, url, type, win) {
tinymce.activeEditor.windowManager.open({
    title: ezfmTitle,
    url: ezfmURL+ "index.php?type=" + type,
    width: ezfmWidth,
    height: ezfmHeight,
    resizable: "yes",
    scrollbars: "no",
    inline: 1,
    buttons: [{
        text: 'X',
        onclick: 'close'
        }]
    },{
        window: win,
        input: field_name,
        editor_id: tinymce.Editor.id
        });

    return false;
}
