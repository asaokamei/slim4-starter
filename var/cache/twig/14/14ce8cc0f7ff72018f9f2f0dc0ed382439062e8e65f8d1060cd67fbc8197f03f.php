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
        if (array_key_exists("exception", $context)) {
            // line 14
            echo "    <hr>
    <h2>Error Details</h2>
    <p>File: ";
            // line 16
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["exception"] ?? null), "file", [], "any", false, false, false, 16), "html", null, true);
            echo "</p>
    <p>Line: ";
            // line 17
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["exception"] ?? null), "line", [], "any", false, false, false, 17), "html", null, true);
            echo "</p>

<pre>";
            // line 19
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["exception"] ?? null), "traceAsString", [], "any", false, false, false, 19), "html", null, true);
            echo "</pre>

    <style>
        td, th {
            padding: .2em;
            border: 1px solid #999999;
        }
        .bottom {
            border-bottom: 2px solid #333333;
        }
    </style>
    <table style=\"border-collapse: collapse;\">
        <thead>
        <tr>
            <th rowspan=\"2\">#</th>
            <th colspan=\"6 \">#line @class -> method name</th>
        </tr>
        <tr class=\"bottom\">
            <th>file</th>
        </tr>
        </thead>
        ";
            // line 40
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["exception"] ?? null), "trace", [], "any", false, false, false, 40));
            foreach ($context['_seq'] as $context["key"] => $context["line"]) {
                // line 41
                echo "            <tr>
                <td rowspan=\"3\">";
                // line 42
                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                echo "</td>
                <td colspan=\"6\">#";
                // line 43
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["line"], "line", [], "any", false, false, false, 43), "html", null, true);
                echo " @ ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["line"], "class", [], "any", false, false, false, 43), "html", null, true);
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["line"], "type", [], "any", false, false, false, 43), "html", null, true);
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["line"], "function", [], "any", false, false, false, 43), "html", null, true);
                echo "</td>
            </tr>
            <tr>
                <td>";
                // line 46
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["line"], "file", [], "any", false, false, false, 46), "html", null, true);
                echo "</td>
            </tr>
            <tr class=\"bottom\">
                <td>";
                // line 49
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["line"], "args", [], "any", false, false, false, 49), "html", null, true);
                echo "
                    ";
                // line 50
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["line"], "args", [], "any", false, false, false, 50));
                foreach ($context['_seq'] as $context["_key"] => $context["arg"]) {
                    // line 51
                    echo "                        { { arg }}
                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['arg'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 53
                echo "                </td>
            </tr>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['line'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 56
            echo "    </table>
";
        }
        // line 58
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
        return array (  161 => 58,  157 => 56,  149 => 53,  142 => 51,  138 => 50,  134 => 49,  128 => 46,  118 => 43,  114 => 42,  111 => 41,  107 => 40,  83 => 19,  78 => 17,  74 => 16,  70 => 14,  68 => 13,  65 => 12,  59 => 10,  57 => 9,  50 => 4,  46 => 3,  35 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "error.twig", "/Users/asao/Documents/dev/slim4-starter/app/templates/error.twig");
    }
}
