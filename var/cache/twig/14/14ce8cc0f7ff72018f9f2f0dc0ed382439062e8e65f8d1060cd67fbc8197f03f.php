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

/* error.twig */
class __TwigTemplate_21ba5d805251afd96aa7230762fd1e0b039dd61b45e6e0f2c54bb997e950d53f extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'contents' => [$this, 'block_contents'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layouts/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("layouts/layout.html.twig", "error.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_contents($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "
<h1>Error!</h1>

<p>sorry, we encountered some errors...</p>

";
        // line 9
        if (($context["title"] ?? null)) {
            // line 10
            echo "    <p style=\"font-weight: bold;\">";
            echo ($context["title"] ?? null);
            echo "</p>
";
        }
        // line 12
        echo "
";
        // line 13
        if (($context["detail"] ?? null)) {
            // line 14
            echo "    <hr>
    <p style=\"font-weight: normal;\">";
            // line 15
            echo nl2br(($context["detail"] ?? null));
            echo "</p>
";
        }
        // line 17
        echo "
";
    }

    public function getTemplateName()
    {
        return "error.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 17,  73 => 15,  70 => 14,  68 => 13,  65 => 12,  59 => 10,  57 => 9,  50 => 4,  46 => 3,  35 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "error.twig", "/Users/asao/Documents/dev/slim4-starter/app/templates/error.twig");
    }
}
