<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/custom/qintess/templates/paragraphs/paragraph--bloque-formulario-contacto.html.twig */
class __TwigTemplate_5fb60fc00ae2316dd394011b5660e646 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension(SandboxExtension::class);
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 2
        yield "<div class=\"container\">
\t<div class=\"contenedor-bloque-formulario-contacto\">
\t\t<div class=\"row\">
\t\t\t<div class=\"col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6\">
\t\t\t\t<h2 class=\"titulo-contacto\">Contact Us!</h2>
\t\t\t\t<p class=\"descripcion-contacto\">
\t\t\t\t\t";
        // line 8
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "field_descripcion_contacto", [], "any", false, false, true, 8), 8, $this->source), "html", null, true);
        yield "
\t\t\t\t</p>
\t\t\t</div>
\t\t\t<div class=\"col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6\">
\t\t\t\t";
        // line 12
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "field_formulario_contacto", [], "any", false, false, true, 12), 12, $this->source), "html", null, true);
        yield "
\t\t\t</div>
\t\t</div>
\t</div>
</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["content"]);        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "themes/custom/qintess/templates/paragraphs/paragraph--bloque-formulario-contacto.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  55 => 12,  48 => 8,  40 => 2,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/qintess/templates/paragraphs/paragraph--bloque-formulario-contacto.html.twig", "/var/www/html/web/themes/custom/qintess/templates/paragraphs/paragraph--bloque-formulario-contacto.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array();
        static $filters = array("escape" => 8);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                [],
                ['escape'],
                [],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
