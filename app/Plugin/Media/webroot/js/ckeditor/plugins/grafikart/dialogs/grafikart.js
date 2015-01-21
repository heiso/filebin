CKEDITOR.dialog.add('grafikart', function(editor)
{
console.log(editor);
	return {
		title : 'Insérer un medium',
		minWidth : 1000,
		minHeight : 600,
 
		contents :
		[
			{
				id : 'iframe',
				label : '',
				expand : true,
				elements :
				[ {
					type   : 'iframe',
					src    : 'http://www.google.com',
					width  : '100%',
					height : '100%',
					//src    : editor.config.grafikartPath,
				} ]
			},
		]
	};
});