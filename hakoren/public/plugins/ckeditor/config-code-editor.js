
CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'ja';
	// config.uiColor = '#AADC6E';
	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
							
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'Image' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		'/',
		{ name: 'about', groups: [ 'about' ] }
							
							
		/*
{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
*/
	];
	config.height = 500;
	config.removeButtons = 'Subscript,Superscript,Cut,Copy,Paste,PasteText,PasteFromWord,Redo,Undo,Scayt,Unlink,Anchor,Maximize,Table,SpecialChar,About,Smiley,Flash,Save,Print,Preview,Templates,NewPage,ShowBlocks,Radio,Checkbox,Form,Iframe,HorizontalRule,PageBreak,CreateDiv,styles';
 // config.protectedSource.push(/<\?[\s\S]*?\?>/g); // PHP Code
 // config.protectedSource.push(/<code>[\s\S]*?<\/code>/gi); // Code tags
	config.tabSpaces = 4; // or some other value
	CKEDITOR.config.startupMode = 'source';
	
};