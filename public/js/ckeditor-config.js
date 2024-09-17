document.addEventListener('DOMContentLoaded', function() {
    // Ensure CKEditor is loaded
    if (typeof ClassicEditor === 'undefined') {
        console.error('ClassicEditor is not defined. Ensure CKEditor is properly included.');
        return;
    }

    // Define the MathSymbolsPlugin
    class MathSymbolsPlugin {
        constructor(editor) {
            this.editor = editor;
            this.init();
        }

        init() {
            const editor = this.editor;

            editor.ui.componentFactory.add('mathSymbols', locale => {
                const view = new ButtonView(locale);

                view.set({
                    label: 'Insert Math Symbol',
                    icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zm0 7c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm-6 3c-.55 0-1-.45-1-1s.45-1 1-1c.55 0 1 .45 1 1s-.45 1-1 1zm12 0c-.55 0-1-.45-1-1s.45-1 1-1c.55 0 1 .45 1 1s-.45 1-1 1z"/></svg>',
                    tooltip: true
                });

                view.on('execute', () => {
                    const symbols = '\\(E = mc^2\\) \\(\\sum_{i=1}^n i\\) \\(\\int_{a}^{b} x^2 dx\\)';
                    const selectedSymbol = prompt('Enter or select a math symbol:', symbols);

                    if (selectedSymbol) {
                        editor.model.change(writer => {
                            const insertPosition = editor.model.document.selection.getFirstPosition();
                            writer.insertText(selectedSymbol, insertPosition);
                        });
                    }
                });

                return view;
            });
        }
    }

    ClassicEditor
        .create(document.querySelector('#editor'), {
            ckfinder: {
                uploadUrl: "{{ route('LembarSoal-upload.upload', ['_token' => csrf_token()]) }}"
            },
            image: {
                toolbar: [
                    'imageTextAlternative', 'imageStyle:full', 'imageStyle:side', '|',
                    'resizeImage'
                ],
                resizeOptions: [
                    {
                        name: 'resizeImage:original',
                        value: null,
                        label: 'Original'
                    },
                    {
                        name: 'resizeImage:200',
                        value: '200',
                        label: '200px'
                    },
                    {
                        name: 'resizeImage:400',
                        value: '400',
                        label: '400px'
                    }
                ],
                resizeUnit: 'px'
            },
            toolbar: [ 'mathSymbols', /* other toolbar items */ ],
            extraPlugins: [ MathSymbolsPlugin ] // Register the custom plugin here
        })
        .then(editor => {
            function renderMathJax() {
                if (typeof MathJax !== 'undefined') {
                    MathJax.typesetPromise().catch(err => console.log(err));
                } else {
                    console.error('MathJax is not defined.');
                }
            }

            renderMathJax();

            editor.model.document.on('change:data', () => {
                setTimeout(renderMathJax, 0);
            });
        })
        .catch(error => {
            console.error(error);
        });
});
