<template>

    <DashboardLayout>

        <label for="rich-text" class="form-label">Content</label>
        <quill-editor v-model="form.body"
                      class="mb-3"
                      id="rich-text"
                      rows="20"
                      :options="editorOption"
                      ref="myQuillEditor"
                      @blur="onEditorBlur($event)"
                      @focus="onEditorFocus($event)"
                      @ready="onEditorReady($event)">
        </quill-editor>

        <button @click="submitForm">Save</button>

    </DashboardLayout>

</template>
<script>
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";

import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
import { quillEditor, Quill } from 'vue-quill-editor'
import ImageResize from 'quill-image-resize';

// Register ImageResize module
Quill.register('modules/imageResize', ImageResize);

// Set toolbar options
const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote', 'code-block'],

    [{'header': 1}, {'header': 2}],
    [{'list': 'ordered'}, {'list': 'bullet'}],
    [{'script': 'sub'}, {'script': 'super'}],
    [{'indent': '-1'}, {'indent': '+1'}],
    [{'direction': 'rtl'}],

    [{'size': ['small', false, 'large', 'huge']}],
    [{'header': [1, 2, 3, 4, 5, 6, false]}],

    [{'color': []}, {'background': []}],
    [{'font': []}],
    [{'align': []}],
    ['link', 'image', 'video'],
    [{ 'test': 1 } ],
    ['clean'],
];

export default {
    components: {
        quillEditor,
        DashboardLayout
    },
    data() {
        return {
            form: {
                body: ``
            },
            editorOption: {
                modules: {
                    toolbar: {
                        container: toolbarOptions,
                        handlers: {
                            // handlers object will be merged with default handlers object
                            'test': function() {
                                alert()
                            },
                            'image': function(value) {
                                // alert(value);
                                // if (value) {
                                //     var href = prompt('Enter the URL');
                                //     this.quill.format('link', href);
                                // } else {
                                //     this.quill.format('link', false);
                                // }

                                console.log(this.quill.getSelection())
                                let img = `<img src="https://www.w3schools.com/tags/img_girl.jpg" alt="Girl in a jacket" width="500" height="600">`;
                                const cursorPosition = this.quill.getSelection().index;
                                this.quill.pasteHTML(cursorPosition, img);

                                // const cursorPosition = this.quill.getSelection().index;
                                // this.quill.insertText(cursorPosition, img);
                                // this.quill.setSelection(cursorPosition + 1);
                            }
                        }
                    },
                    history: {
                        delay: 1000,
                        maxStack: 50,
                        userOnly: false
                    },
                    imageResize: {
                        displayStyles: {
                            backgroundColor: 'black',
                            border: 'none',
                            color: 'white'
                        },
                        modules: ['Resize', 'DisplaySize', 'Toolbar']
                    },
                }

            },
        }
    },
    methods: {
        onEditorBlur(editor) {
            // console.log('editor blur!', editor)
        },
        onEditorFocus(editor) {
            // console.log('editor focus!', editor)
        },
        onEditorReady(editor) {
            // console.log('editor ready!', editor)
        },
        submitForm(){
            console.log(this.form.body)
        }
    },
    computed: {
        editor(){
            return this.$refs.myQuillEditor.quill
        }
    },
    watch: {
    },
    mounted() {
        document.querySelector('.ql-test').innerHTML = 'Add Iamge';
    }
}
</script>
<style >
.ql-test{
    width: 80px !important;
    font-size: 13px !important;
}
.ql-test:hover{
    font-weight: bold !important;
}
</style>

