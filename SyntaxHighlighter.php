<?php

/**
 * SyntaxhigHlighter class file.
 *
 * @author  Giovanni Derks
 * @license MIT License
 * http://derks.me.uk
 */

namespace niremizov\yii2SyntaxHighlighter;

use Yii;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\Widget as Widget;

class SyntaxHighlighter extends Widget
{
    public $theme = 'default';

    private $_assetUrl;

    public $brushes = [];
    public $brushAliases = [
        'js' => 'JScript',
    ];

    // config:
    public $bloggerMode = false;
    public $strings = [];
    public $stripBrs = false;
    public static $tagName = 'pre';

    // defaults:
    public $showToolbar = false;
    public $tabSize = 4;
    public $blockClassName = '';
    public $autoLinks = true;
    public $collapse = false;
    public $showGutter = true;
    public $smartTabs = true;
    public $firstLine = 1;
    public $gutter = true;

    /**
     * Publishes the assets
     */
    public function publishAssets()
    {
        $this->_assetUrl = SyntaxHighlighterAsset::register($this->getView())->baseUrl;
    }

    /**
     * Run the widget
     */
    public function run()
    {

        SyntaxHighlighterAsset::$extraCss[] = 'styles/shCore'.ucfirst($this->theme).'.css';

        foreach ($this->brushes as $brushName) {
            $brushFile = (!empty($this->brushAliases[$brushName])
                ? $this->brushAliases[$brushName]
                : ucfirst(
                    $brushName
                ));
            SyntaxHighlighterAsset::$extraJs[] = 'scripts/shBrush'.$brushFile.'.js';
        }

        if (empty($this->brushes)) {
          // If no brushes was selected, force Asset to include autoloader js.
          SyntaxHighlighterAsset::$extraJs[] = 'scripts/shAutoloader.js';
        }

        $this->publishAssets();

        $this->registerConfig();

        parent::run();
    }

    public static function getBlock($source, $type, $firstLine = 1)
    {
        $res = Html::tag(
            static::$tagName,
            htmlentities($source),
            [
                'class' => 'brush: '.$type.'; first-line: '.($firstLine).';',
            ]
        );

        return $res;
    }

    protected function registerConfig() {
      if (empty($this->brushes)) {
        // Add autoloader path callback if no brushes was selected.
        $this->registerAutoloaderConfig();
      }

      $initJs = '';
      $initJs .= "SyntaxHighlighter.config.bloggerMode = ".($this->bloggerMode ? 'true' : 'false').";\n";
      if (!empty($this->strings)) {
        $initJs .= "SyntaxHighlighter.config.strings = ".Json::encode($this->strings).";\n";
      }
      $initJs .= "SyntaxHighlighter.config.stripBrs = ".($this->stripBrs ? 'true' : 'false').";\n";
      $initJs .= "SyntaxHighlighter.config.tagName = '".static::$tagName."';\n";
      $initJs .= "SyntaxHighlighter.defaults = {
      'tab-size': {$this->tabSize},
      'class-name': '{$this->blockClassName}',
      'auto-links': ".($this->autoLinks ? 'true' : 'false').",
          'collapse': ".($this->collapse ? 'true' : 'false').",
          'gutter': ".($this->showGutter ? 'true' : 'false').",
          'smart-tabs': ".($this->smartTabs ? 'true' : 'false').",
          'toolbar': ".($this->showToolbar ? 'true' : 'false').",
          'first-line': ". $this->firstLine .",
          'gutter': ".($this->gutter ? 'true' : 'false')."
        };\n";
      $initJs .= 'SyntaxHighlighter.all();'."\n";
      $this->getView()->registerJs($initJs, View::POS_READY, 'InitSyntaxHighlighter');
    }

    protected function registerAutoloaderConfig() {

      $initJs = 'function path()
                {
                    var args = arguments,
                    result = [];

                    for(var i = 0; i < args.length; i++)
                        result.push(args[i].replace("@", "' . $this->_assetUrl . '/scripts/"));
                        return result
                    };

                    SyntaxHighlighter.autoloader.apply(null, path(
                        "applescript            @shBrushAppleScript.js",
                        "actionscript3 as3      @shBrushAS3.js",
                        "bash shell             @shBrushBash.js",
                        "coldfusion cf          @shBrushColdFusion.js",
                        "cpp c                  @shBrushCpp.js",
                        "c# c-sharp csharp      @shBrushCSharp.js",
                        "css                    @shBrushCss.js",
                        "delphi pascal          @shBrushDelphi.js",
                        "diff patch pas         @shBrushDiff.js",
                        "erl erlang             @shBrushErlang.js",
                        "groovy                 @shBrushGroovy.js",
                        "java                   @shBrushJava.js",
                        "jfx javafx             @shBrushJavaFX.js",
                        "js jscript javascript  @shBrushJScript.js",
                        "perl pl                @shBrushPerl.js",
                        "php                    @shBrushPhp.js",
                        "text plain             @shBrushPlain.js",
                        "py python              @shBrushPython.js",
                        "ruby rails ror rb      @shBrushRuby.js",
                        "sass scss              @shBrushSass.js",
                        "scala                  @shBrushScala.js",
                        "sql                    @shBrushSql.js",
                        "vb vbnet               @shBrushVb.js",
                        "xml xhtml xslt html    @shBrushXml.js"
                    ));
                    SyntaxHighlighter.all();
                    SyntaxHighlighter.config.stripBrs = true;
                    SyntaxHighlighter.all();';

      $this->getView()->registerJs($initJs, View::POS_READY, 'InitSyntaxHighlighterAutoloader');
    }
}
