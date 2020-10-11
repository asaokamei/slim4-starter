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
        }
        a.header-title {
            font-size: 2rem;
        }
    </style>
</head>
<body>
<header>
    <a class=\"header-title\" href=\"/\">Slim4 Starter</a>
    <hr>
</header>

";
        // line 22
        $this->displayBlock('contents', $context, $blocks);
        // line 24
        echo "
</body>
</html>";
    }

    // line 22
    public function block_contents($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function getTemplateName()
    {
        return "layouts/layout.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  69 => 22,  63 => 24,  61 => 22,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "layouts/layout.html.twig", "/Users/asao/Documents/dev/slim4-starter/app/templates/layouts/layout.html.twig");
    }
}
