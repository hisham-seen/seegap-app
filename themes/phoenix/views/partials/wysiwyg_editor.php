<?php defined('SEEGAP') || die() ?>

<!-- Quill WYSIWYG Editor -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
/**
 * Initialize WYSIWYG Editor
 * @param {string} editorId - The ID of the editor container
 * @param {string} hiddenInputId - The ID of the hidden input field
 * @param {Object} options - Configuration options
 */
function initWysiwygEditor(editorId, hiddenInputId, options = {}) {
    // Default configuration
    const defaultConfig = {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    };
    
    // Merge with custom options
    const config = Object.assign({}, defaultConfig, options);
    
    // Initialize Quill editor
    const quill = new Quill('#' + editorId, config);
    
    // Update hidden input when content changes
    quill.on('text-change', function() {
        const hiddenInput = document.getElementById(hiddenInputId);
        if (hiddenInput) {
            hiddenInput.value = quill.root.innerHTML;
        }
    });
    
    // Return the quill instance for further customization if needed
    return quill;
}

/**
 * Initialize WYSIWYG Editor with form submission handling
 * @param {string} editorId - The ID of the editor container
 * @param {string} hiddenInputId - The ID of the hidden input field
 * @param {string} formId - The ID of the form (optional)
 * @param {Object} options - Configuration options
 */
function initWysiwygEditorWithForm(editorId, hiddenInputId, formId = null, options = {}) {
    const quill = initWysiwygEditor(editorId, hiddenInputId, options);
    
    // Handle form submission
    if (formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function() {
                const hiddenInput = document.getElementById(hiddenInputId);
                if (hiddenInput) {
                    hiddenInput.value = quill.root.innerHTML;
                }
            });
        }
    }
    
    return quill;
}

/**
 * Initialize WYSIWYG Editor for modal forms
 * @param {string} modalId - The ID of the modal
 * @param {string} editorId - The ID of the editor container
 * @param {string} hiddenInputId - The ID of the hidden input field
 * @param {string} formSelector - The form selector (optional)
 * @param {Object} options - Configuration options
 */
function initWysiwygEditorForModal(modalId, editorId, hiddenInputId, formSelector = null, options = {}) {
    $('#' + modalId).on('shown.bs.modal', function() {
        const quill = initWysiwygEditor(editorId, hiddenInputId, options);
        
        // Handle form submission if form selector is provided
        if (formSelector) {
            const form = document.querySelector(formSelector);
            if (form) {
                form.addEventListener('submit', function() {
                    const hiddenInput = document.getElementById(hiddenInputId);
                    if (hiddenInput) {
                        hiddenInput.value = quill.root.innerHTML;
                    }
                });
            }
        }
        
        return quill;
    });
}

/**
 * Initialize Minimalistic Editor (Simple contenteditable with basic toolbar)
 * @param {string} editorId - The ID of the editor container
 * @param {string} hiddenInputId - The ID of the hidden input field
 * @param {Object} options - Configuration options
 */
function initMinimalisticEditor(editorId, hiddenInputId, options = {}) {
    const editor = document.getElementById(editorId);
    const hiddenInput = document.getElementById(hiddenInputId);
    
    if (!editor || !hiddenInput) {
        console.error('Minimalistic editor: Editor or hidden input not found');
        return null;
    }
    
    // Default configuration
    const defaultConfig = {
        toolbar: ['bold', 'italic', 'underline', 'insertUnorderedList', 'insertOrderedList', 'createLink'],
        placeholder: 'Enter text...'
    };
    
    // Merge with custom options
    const config = Object.assign({}, defaultConfig, options);
    
    // Set placeholder if provided
    if (config.placeholder && !editor.textContent.trim()) {
        editor.setAttribute('data-placeholder', config.placeholder);
    }
    
    // Create toolbar if it doesn't exist
    let toolbar = editor.previousElementSibling;
    if (!toolbar || !toolbar.classList.contains('minimalistic-editor-toolbar')) {
        toolbar = createMinimalisticToolbar(editorId, config.toolbar);
        editor.parentNode.insertBefore(toolbar, editor);
    }
    
    // Update hidden input when content changes
    editor.addEventListener('input', function() {
        const htmlContent = editor.innerHTML;
        hiddenInput.value = htmlContent;
        updatePlaceholder(editor, config.placeholder);
        console.log('Editor content updated:', htmlContent); // Debug log
    });
    
    // Set initial content
    hiddenInput.value = editor.innerHTML;
    updatePlaceholder(editor, config.placeholder);
    
    // Toolbar button handlers with improved HTML generation
    toolbar.addEventListener('click', function(e) {
        if (e.target.closest('[data-action]')) {
            e.preventDefault();
            const button = e.target.closest('[data-action]');
            const action = button.dataset.action;
            
            editor.focus();
            
            // Use more reliable methods for formatting
            if (action === 'bold') {
                document.execCommand('bold', false, null);
            } else if (action === 'italic') {
                document.execCommand('italic', false, null);
            } else if (action === 'underline') {
                document.execCommand('underline', false, null);
            } else if (action === 'insertUnorderedList') {
                document.execCommand('insertUnorderedList', false, null);
            } else if (action === 'insertOrderedList') {
                document.execCommand('insertOrderedList', false, null);
            } else if (action === 'createLink') {
                const url = prompt('Enter URL:');
                if (url) {
                    document.execCommand('createLink', false, url);
                }
            }
            
            // Force update hidden input after command
            setTimeout(() => {
                const htmlContent = editor.innerHTML;
                hiddenInput.value = htmlContent;
                console.log('After command - HTML content:', htmlContent); // Debug log
                updateMinimalisticToolbarState(toolbar, editor);
                updatePlaceholder(editor, config.placeholder);
            }, 10);
        }
    });
    
    // Update toolbar state on selection change
    editor.addEventListener('mouseup', function() {
        updateMinimalisticToolbarState(toolbar, editor);
    });
    
    editor.addEventListener('keyup', function() {
        updateMinimalisticToolbarState(toolbar, editor);
        // Update hidden input on keyup as well
        const htmlContent = editor.innerHTML;
        hiddenInput.value = htmlContent;
    });
    
    // Handle paste events to clean up formatting
    editor.addEventListener('paste', function(e) {
        e.preventDefault();
        const text = (e.originalEvent || e).clipboardData.getData('text/plain');
        document.execCommand('insertText', false, text);
        setTimeout(() => {
            const htmlContent = editor.innerHTML;
            hiddenInput.value = htmlContent;
            updatePlaceholder(editor, config.placeholder);
        }, 10);
    });
    
    return {
        editor: editor,
        toolbar: toolbar,
        getContent: () => {
            const content = editor.innerHTML;
            console.log('getContent called, returning:', content); // Debug log
            return content;
        },
        setContent: (content) => {
            editor.innerHTML = content;
            hiddenInput.value = content;
            updatePlaceholder(editor, config.placeholder);
        }
    };
}

/**
 * Create minimalistic toolbar
 * @param {string} editorId - The ID of the editor
 * @param {Array} tools - Array of tool names
 */
function createMinimalisticToolbar(editorId, tools) {
    const toolbar = document.createElement('div');
    toolbar.className = 'minimalistic-editor-toolbar mb-2';
    
    const toolConfig = {
        bold: { icon: 'fas fa-bold', title: 'Bold' },
        italic: { icon: 'fas fa-italic', title: 'Italic' },
        underline: { icon: 'fas fa-underline', title: 'Underline' },
        insertUnorderedList: { icon: 'fas fa-list-ul', title: 'Bullet List' },
        insertOrderedList: { icon: 'fas fa-list-ol', title: 'Numbered List' },
        createLink: { icon: 'fas fa-link', title: 'Insert Link' }
    };
    
    let toolbarHTML = '';
    let separatorAdded = false;
    
    tools.forEach((tool, index) => {
        if (tool === '|') {
            if (!separatorAdded) {
                toolbarHTML += '<span class="mx-2">|</span>';
                separatorAdded = true;
            }
        } else if (toolConfig[tool]) {
            const config = toolConfig[tool];
            toolbarHTML += `
                <button type="button" class="btn btn-sm btn-outline-secondary" data-action="${tool}" title="${config.title}">
                    <i class="${config.icon}"></i>
                </button>
            `;
            separatorAdded = false;
        }
    });
    
    toolbar.innerHTML = toolbarHTML;
    return toolbar;
}

/**
 * Update minimalistic toolbar button states
 * @param {Element} toolbar - The toolbar element
 * @param {Element} editor - The editor element
 */
function updateMinimalisticToolbarState(toolbar, editor) {
    const buttons = toolbar.querySelectorAll('[data-action]');
    
    buttons.forEach(button => {
        const action = button.dataset.action;
        let isActive = false;
        
        try {
            isActive = document.queryCommandState(action);
        } catch (e) {
            // Some commands might not be supported
        }
        
        if (isActive) {
            button.classList.add('active');
            button.style.backgroundColor = '#007bff';
            button.style.color = 'white';
        } else {
            button.classList.remove('active');
            button.style.backgroundColor = '';
            button.style.color = '';
        }
    });
}

/**
 * Update placeholder visibility
 * @param {Element} editor - The editor element
 * @param {string} placeholder - The placeholder text
 */
function updatePlaceholder(editor, placeholder) {
    if (!placeholder) return;
    
    if (editor.textContent.trim() === '') {
        editor.setAttribute('data-placeholder', placeholder);
        editor.classList.add('empty');
    } else {
        editor.removeAttribute('data-placeholder');
        editor.classList.remove('empty');
    }
}

/**
 * Initialize Minimalistic Editor with form submission handling
 * @param {string} editorId - The ID of the editor container
 * @param {string} hiddenInputId - The ID of the hidden input field
 * @param {string} formId - The ID of the form (optional)
 * @param {Object} options - Configuration options
 */
function initMinimalisticEditorWithForm(editorId, hiddenInputId, formId = null, options = {}) {
    const editorInstance = initMinimalisticEditor(editorId, hiddenInputId, options);
    
    // Handle form submission
    if (formId && editorInstance) {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function() {
                const hiddenInput = document.getElementById(hiddenInputId);
                if (hiddenInput) {
                    hiddenInput.value = editorInstance.getContent();
                }
            });
        }
    }
    
    return editorInstance;
}
</script>

<!-- Minimalistic Editor Styles -->
<style>
.minimalistic-editor-toolbar .btn {
    margin-right: 2px;
}

.minimalistic-editor-toolbar .btn.active {
    background-color: #007bff !important;
    color: white !important;
}

.minimalistic-editor {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    line-height: 1.5;
    min-height: 100px;
    max-height: 200px;
    overflow-y: auto;
}

.minimalistic-editor:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.minimalistic-editor.empty:before {
    content: attr(data-placeholder);
    color: #6c757d;
    font-style: italic;
}

.minimalistic-editor p {
    margin-bottom: 0.5rem;
}

.minimalistic-editor p:last-child {
    margin-bottom: 0;
}

.minimalistic-editor ul, .minimalistic-editor ol {
    margin-bottom: 0.5rem;
    padding-left: 1.5rem;
}

.minimalistic-editor a {
    color: #007bff;
    text-decoration: underline;
}
</style>
