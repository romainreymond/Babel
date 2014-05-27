/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
    config.language = 'fr';
    config.filebrowserImageUploadUrl = '/includes/kcfinder/upload.php?type=images';
    config.filebrowserImageBrowseUrl = '/includes/kcfinder/browse.php?type=images';
    config.height = 400;
	config.toolbar = 'simple';
	// Toolbar configuration generated automatically by the editor based on config.toolbarGroups.
	config.toolbar_simple = [
		[ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],
		[ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ],
		[ 'Link', 'Unlink' ],
		[ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar' ],
		[ 'Maximize', 'ShowBlocks' ],
		[ '-' ],
		'/',
		[ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ],
		[ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'Language' ],
		[ 'Format', 'FontSize' ],
		[ 'TextColor', 'BGColor' ]
	];

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Se the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';
    
	// Make dialogs simpler.
	config.removeDialogTabs = 'image:advanced;link:advanced;';
};
