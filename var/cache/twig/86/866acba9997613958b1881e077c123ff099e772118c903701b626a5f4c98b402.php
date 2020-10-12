<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* layouts/layout.html.twig */
class __TwigTemplate_3cc58ad25014a09f14bfc5fb9136f2b8d156fea509c9a689ae92cab8dec75fb3 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'contents' => [$this, 'block_contents'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!Document html>
<html lang=\"en\">
<head>
    <title>Slim4 Starter</title>
    <meta charset=\"utf-8\">
    <style>
        body {
            font-family: \"Lucida Grande\", \"Lucida Sans Unicode\", Verdana, Arial, Helvetica, sans-serif;
            font-size: 16px;
            margin: 0;
        }
        header {
            margin: 0;
            padding: 1rem;
            border-bottom: 1px solid #cccccc;
        }
        a.header-title {
            font-size: 2rem;
            text-decoration: none;
        }
        div.contents {
            margin: 1rem;
        }
    </style>
</head>
<body>
<header>
    <a class=\"header-title\" href=\"/\">Slim4 Starter</a>
</header>

<div class=\"contents\">
    ";
        // line 32
        $this->displayBlock('contents', $context, $blocks);
        // line 34
        echo "</div>

</body>
</html>";
    }

    // line 32
    public function block_contents($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 33
        echo "    ";
    }

    public function getTemplateName()
    {
        return "layouts/layout.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  84 => 33,  80 => 32,  73 => 34,  71 => 32,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "layouts/layout.html.twig", "/Users/asao/Documents/dev/slim4-starter/app/templates/layouts/layout.html.twig");
    }
}
