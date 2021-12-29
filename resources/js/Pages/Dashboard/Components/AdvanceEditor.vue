<template>

    <div>
        <div :class="{'err': error}">
            <div ref="edit"  class="edit prose sm:prose-sm md:prose-md lg:prose-lg xl:prose-xl" ></div>
        </div>
        <span v-if="error_message" class="text-xs text-red-500">{{error_message}}</span>
        <vs-dialog v-model="mediaMangerActive"  :loading="mediaMangerLoading">
            <MediaManager :picker="true" :type="mediaMangerType" :multiCheck="false" @onPick="onPickMediaPicker($event)" @onLoading="onLoadingMediaPicker($event)"/>
        </vs-dialog>
    </div>

</template>
<script>
import MediaManager from '@/Pages/Dashboard/Components/MediaManager';

window.FroalaEditor = require('froala-editor');
// Load a plugin.
require('froala-editor/js/plugins/align.min.js');
require('froala-editor/js/plugins/char_counter.min.js');
require('froala-editor/js/plugins/code_beautifier.min.js');
require('froala-editor/js/plugins/code_view.min.js');
require('froala-editor/js/plugins/colors.min.js');
require('froala-editor/js/plugins/draggable.min.js');
require('froala-editor/js/plugins/edit_in_popup.min.js');
require('froala-editor/js/plugins/emoticons.min.js');
require('froala-editor/js/plugins/entities.min.js');
require('froala-editor/js/plugins/file.min.js');
require('froala-editor/js/plugins/files_manager.min.js');
require('froala-editor/js/plugins/font_family.min.js');
require('froala-editor/js/plugins/font_size.min.js');
require('froala-editor/js/plugins/forms.min.js');
require('froala-editor/js/plugins/fullscreen.min.js');
require('froala-editor/js/plugins/help.min.js');
require('froala-editor/js/plugins/image.min.js');
require('froala-editor/js/plugins/image_manager.min.js');
require('froala-editor/js/plugins/inline_class.min.js');
require('froala-editor/js/plugins/inline_style.min.js');
require('froala-editor/js/plugins/line_breaker.min.js');
require('froala-editor/js/plugins/line_height.min.js');
require('froala-editor/js/plugins/link.min.js');
require('froala-editor/js/plugins/lists.min.js');
require('froala-editor/js/plugins/markdown.min.js');
require('froala-editor/js/plugins/paragraph_format.min.js');
require('froala-editor/js/plugins/paragraph_style.min.js');
require('froala-editor/js/plugins/print.min.js');
require('froala-editor/js/plugins/quick_insert.min.js');
require('froala-editor/js/plugins/quote.min.js');
require('froala-editor/js/plugins/save.min.js');
require('froala-editor/js/plugins/special_characters.min.js');
require('froala-editor/js/plugins/table.min.js');
require('froala-editor/js/plugins/track_changes.min.js');
require('froala-editor/js/plugins/trim_video.min.js');
require('froala-editor/js/plugins/url.min.js');
require('froala-editor/js/plugins/video.min.js');
require('froala-editor/js/plugins/word_paste.min.js');

import 'froala-editor/css/froala_editor.css'
import 'froala-editor/css/froala_style.css'
import 'froala-editor/css/froala_editor.pkgd.css'
import 'froala-editor/css/themes/gray.css'


export default {
    components: {
        MediaManager
    },
    props: {
        value:String,
        error: {
            type: Boolean,
            default: false
        },
        error_message: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            body: '',
            editor: null,
            mediaMangerActive: false,
            mediaMangerLoading: false,
            mediaMangerType: '',
            conentValue: '',
        }
    },
    methods: {
        onLoadingMediaPicker(isLoading) {
            this.mediaMangerLoading = isLoading;
        },
        onPickMediaPicker(media) {
            this.mediaMangerActive = false;
            let _html = '';
            switch(media.type) {
                case 'image':
                    _html = `<img src="${media.media_url}" alt="${media.title}"/>`;
                    break;
                case 'audio':
                    _html = `<audio controls>
                              <source src="${media.media_url}" type="audio/mpeg">
                              Your browser does not support the audio tag.
                            </audio>`;
                    break;
                case 'video':
                    // _html = `<table><video  class="fr-fil fr-dib" controls>
                    //           <source src="${media.media_url}" type="video/mp4">
                    //           Your browser does not support the video tag.
                    //         </video></table>`;
                    _html = `<span class="fr-video fr-deletable fr-fvc fr-dvi fr-draggable" contenteditable="false">
                            <video class="fr-fil fr-dib fr-draggable fr-fvc fr-dvb" controls>
                              <source src="${media.media_url}" type="video/mp4">
                              Your browser does not support the video tag.
                            </video></span>`;
                    break;
                case 'document':
                    _html = ` <a href="${media.media_url}">${media.title} `;
                    break;
            }
            this.editor.html.insert(_html);
            this.onChangeEditorContent();
        },
        onChangeEditorContent() {
            this.$emit('input', this.editor.html.get())
        }
    },
    mounted() {
        this.conentValue = this.value;
        let that = this;
        FroalaEditor.DefineIcon('alert', {NAME: 'info', SVG_KEY: 'help'});
        FroalaEditor.RegisterCommand('alert', {
            title: 'Hello',
            focus: false,
            undo: false,
            refreshAfterCallback: false,
            callback: function () {
                alert('Hello!');
            }
        });

        FroalaEditor.DefineIcon('insert_image', {NAME: 'insert_image', SVG_KEY: 'insertImage'});
        FroalaEditor.RegisterCommand('insert_image', {
            title: 'Image',
            focus: false,
            undo: true,
            refreshAfterCallback: false,
            callback: function () {
                that.mediaMangerActive = true;
                that.mediaMangerType = 'image';
            }
        });

        FroalaEditor.DefineIcon('insert_video', {NAME: 'insert_video', SVG_KEY: 'insertVideo'});
        FroalaEditor.RegisterCommand('insert_video', {
            title: 'Video',
            focus: false,
            undo: true,
            refreshAfterCallback: false,
            callback: function () {
                that.mediaMangerActive = true;
                that.mediaMangerType = 'video';
            }
        });

        FroalaEditor.DefineIcon('insert_file', {NAME: 'insert_file', SVG_KEY: 'insertFile'});
        FroalaEditor.RegisterCommand('insert_file', {
            title: 'Docuemnt',
            focus: false,
            undo: true,
            refreshAfterCallback: false,
            callback: function () {
                that.mediaMangerActive = true;
                that.mediaMangerType = 'document';
            }
        });

        FroalaEditor.DefineIconTemplate('bx_icons', '<i class="bx bxs-[NAME]"></i>');
        FroalaEditor.DefineIcon('bxMusic', {NAME: 'music', template: 'bx_icons'});
        FroalaEditor.DefineIcon('insert_audio', {NAME: 'insert_audio'});
        FroalaEditor.RegisterCommand('insert_audio', {
            title: 'Audio',
            icon: 'bxMusic',
            focus: false,
            undo: true,
            refreshAfterCallback: false,
            callback: function () {
                that.mediaMangerActive = true;
                that.mediaMangerType = 'audio';
            }
        });

        /*
        Available Buttons
        'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'color', 'formatBlock', 'blockStyle', 'inlineStyle', 'align', 'insertOrderedList', 'insertUnorderedList', 'outdent', 'indent', 'selectAll', 'createLink', 'insertImage', 'insertVideo', 'table', 'undo', 'redo', 'html', 'save', 'insertHorizontalRule', 'uploadFile', 'removeFormat', 'fullscreen'
        */
        /*
        Icons
        ["add", "advancedImageEditor", "alignCenter", "alignJustify", "alignLeft", "alignRight",
            "anchors", "back", "backgroundColor", "blockquote", "bold", "cellBackground",
            "cellBorderColor", "cellOptions", "cellStyle", "clearFormatting", "close", "codeView",
            "cogs", "columns", "editLink", "exitFullscreen", "fontAwesome", "fontFamily",
            "fontSize", "fullscreen", "help", "horizontalLine", "imageAltText", "imageCaption",
            "imageClass", "imageDisplay", "imageManager", "imageSize", "indent", "inlineClass",
            "inlineStyle", "insertEmbed", "insertFile", "insertImage", "insertLink", "insertMore",
            "insertTable", "insertVideo", "upload", "italic", "search", "lineHeight", "linkStyles",
            "mention", "more", "openLink", "orderedList", "outdent", "pageBreaker",
            "paragraphFormat", "paragraphMore", "paragraphStyle", "pdfExport", "print",
            "redo", "removeTable", "remove", "replaceImage", "row", "selectAll", "smile",
            "spellcheck", "star", "strikeThrough", "subscript", "superscript", "symbols", "tags",
            "tableHeader", "tableStyle", "textColor", "textMore", "underline", "undo", "unlink",
            "unorderedList", "verticalAlignBottom", "verticalAlignMiddle", "verticalAlignTop"]
        */
        this.editor = new FroalaEditor(this.$refs.edit, {
            // Add the custom buttons in the toolbarButtons list, after the separator.
            // toolbarButtons: [
            //     ['paragraphFormat','paragraphStyle', '|',
            //         'bold', 'italic', 'underline', 'strikeThrough', '|', 'subscript', 'superscript', '|',
            //         'fontFamily', 'fontSize', 'color', 'formatBlock', 'blockStyle', 'inlineStyle', 'align',
            //         'insertOrderedList', 'insertUnorderedList', 'outdent', 'indent', 'selectAll', 'createLink',
            //         'table', 'undo', 'redo', 'html', 'insertHorizontalRule',
            //         'uploadFile', 'removeFormat', 'fullscreen',
            //     ],
            //     ['insert_image', 'insert_video', 'insert_audio', 'insert_file' ]
            // ],

            toolbarButtons: {
                // Key represents the more button from the toolbar.
                moreText: {
                    buttons: ['paragraphFormat', '|', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting'],
                    align: 'left',
                    buttonsVisible: 4
                },
                moreParagraph: {
                    buttons: ['alignLeft', 'alignCenter', 'formatOLSimple', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'paragraphStyle', 'lineHeight', 'outdent', 'indent', 'quote'],
                    align: 'left',
                    buttonsVisible: 3
                },
                moreRich: {
                    buttons: ['insertLink', 'insert_image', 'insert_video', 'insert_audio', 'insert_file' , 'insertTable', 'emoticons', 'fontAwesome', 'specialCharacters', 'embedly' , 'insertHR'],
                    align: 'left',
                    buttonsVisible: 5
                },
                moreMisc: {
                    buttons: ['undo', 'redo', 'fullscreen', 'print', 'getPDF', 'spellChecker', 'selectAll', 'html', 'help'],
                    align: 'right',
                    buttonsVisible: 2
                }
            },
            quickInsertButtons: ['table', 'ol', 'ul'],
            events: {
                'contentChanged': function () {
                    that.onChangeEditorContent();
                }
            },

        });
        setTimeout(function() {
            that.editor.html.set(that.conentValue);
        },1000)

    }
}
</script>
<style >
.edit {
    min-width: 100%;
    background: #FFF;
}
.err {
    /*min-width: 100%;*/
    /*background: #ff000017;*/
    box-shadow: 0px 0px 9px #ff000073;
}
.fr-wrapper{
    min-height: 23em;
}
.fr-floating-btn {
    padding-top: 1px;
}
.fr-buttons button:first-child {
    display: none !important;
}
.fr-toolbar {
    background: transparent !important;
}
.fr-wrapper {
    background: transparent !important;
}
.fr-second-toolbar{
    display: flex;
    justify-content: space-between;
    background: #101827;
    align-items: center;
    color: #FFF;
}
.fr-second-toolbar>*{
    width:100%
}
.fr-counter {
    width: 100%;
    text-align: right;
    font-size: 12px;
    margin-right: 1em;
}
</style>

