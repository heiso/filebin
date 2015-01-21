CKEDITOR.plugins.add('grafikart',
{
	requires : [ 'iframedialog' ],
	lang : [ 'fr' ],

	init : function(editor)
	{

		var pluginName = 'grafikart';
		CKEDITOR.dialog.addIframe(
			'grafikart',
			'Insérer un medium',
			$('#explorer').val() + '/editor:ckeditor?id=' + editor.name,
			1000,
			600,
			{}, {}, {}
		);

		editor.addCommand( 'grafikart', new CKEDITOR.dialogCommand( 'grafikart' ) );

		editor.ui.addButton('Grafikart',
		{
				label : editor.lang.grafikart.title,
				command : pluginName,
				icon: this.path + 'images/grafikart.gif'
		});
	}
});

