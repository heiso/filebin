/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	config.extraPlugins = 'syntaxhighlight,grafikart';
	// Define changes to default configuration here. For example:
	config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.toolbar = "Grafikart";
	config.height = 500;
	config.toolbar_Grafikart =
	[
		{ name: 'document', items : [ 'Source' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','-','RemoveFormat' ] },
		{ name: 'styles', items : [ 'Format' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'tools', items : [ 'Maximize' ] },
		{ name: 'code', items : ['Code'] },
		{ name: 'grafikart', items : ['Grafikart'] },
	];
};
