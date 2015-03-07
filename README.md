Yii SyntaxHighlighter Widget
======================

This is Yii widget, that wraps SyntaxHighlighter by Alex Gorbatchev.

@see http://alexgorbatchev.com/


##Install

Because of this is fork, there is some more additional actions required for adding this extension into your Yii project.

1. Add to your project's *composer.json* file: 

		"repositories": [{
        "type": "package",
        "package": {
            "version": "dev-master",
            "name": "niremizov/yii2-syntaxhighlighter",
            "source": {
                "url": "https://github.com/Niremizov/yii2-syntaxhighlighter.git",
                "type": "git",
                "reference": "master"
            },
        "autoload": {
            "psr-4": { "niremizov\\yii2SyntaxHighlighter\\": "" }
				}
			}
		}],
  
2. Add to *composer.json* **"require"** section: `"niremizov/yii2-syntaxhighlighter": "dev-master"`.
3. Run composer update.

##Usage

For adding SyntaxHighliter autoloader add this lines to your view:

	use niremizov\yii2SyntaxHighlighter\SyntaxHighlighter;
	SyntaxHighlighter::widget(['theme' => 'rdark', 'gutter' => false]);

But you also can be more specific and use:

	use giovdk21\yii2SyntaxHighlighter\SyntaxHighlighter as SyntaxHighlighter;
	SyntaxHighlighter::begin(['brushes' => ['php']]);
	echo SyntaxHighlighter::getBlock('some code', 'php');
	SyntaxHighlighter::end();
